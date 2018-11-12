<?php

namespace app\api\service;

use think\Log;
use app\api\service\Order as OrderService;
use app\lib\exception\TokenException;
use app\api\model\Order as OrderModel;
use app\lib\enum\OrderStatusEnum;
use app\lib\exception\OrderException;
use wxpay\WxPayApi;
include "../extend/wxpay/WxPay.Api.php";
// Loader::import('WxPay.WxPay', EXTEND_PATH, '.Api.php');

class Pay extends Token{
    private $orderID;
    private $orderNo;

    public function __construct($orderID) {
        if(!$orderID) {
            throw new \Exception('订单号不能为空');
        }
        $this->orderID = $orderID;
    }

    public function pay() {
        // 订单号可能根本不存在 ---检测
        // 订单号确实存在，但是和当前用户不匹配-----检测
        // 订单可能已经支付过了---检测
        // 进行库存量检测
        $this->checkOrderValid();
        $orderService = new OrderService();
        $status = $orderService->checkOrderStock($this->orderID);
        if(!$status['pass']) {
            return $status;
        }
        return $this->makeWxPreOrder($status['orderPrice']);
    }
    // 订单预请求
    private function makeWxPreOrder($totalPrice) {
        // openid
        $openid = self::getCurrentTokenVal('openid');
        if(!$openid) {
            throw new TokenException();
        }

        $wxOrderData = new \WxPayUnifiedOrder();
        $wxOrderData->SetOut_trade_no($this->orderNo);
        $wxOrderData->SetTrade_type('JSAPI');
        $wxOrderData->SetTotal_fee($totalPrice*100);
        $wxOrderData->SetBody('零食商场');
        $wxOrderData->SetOpenid($openid);
        $wxOrderData->SetNotify_url(''); // 回调接口
        return $this->getPaySignature($wxOrderData);
    }

    private function getPaySignature($wxOrderData) {
        $wxOrder = \WxPayApi::unifiedOrder($wxOrderData);
                            
        if($wxOrder['return_code'] != 'SUCCESS' || $wxOrder['result_code'] != 'SUCCESS') {
            Log::record($wxOrder, 'error');
            Log::record('获取预支付订单失败', 'error');
        }
        // prepay_id
        return null;
    }

    private function checkOrderValid() {
        $orderID= $this->orderID;
        $order = OrderModel::where('id', '=', $this->orderID)
            ->find();
        if(!$order) {
            throw new OrderException();
        }
        // 检侧用户于订单是否一致
        $state = self::isValidOperate($order->user_id);
        if(!$state) {
            throw new TokenException([
                'msg' => '订单与用户不匹配',
                'errorCode' => 10003
            ]);
        }
        
        // 订单是否支付
        $orderStatus = $order->status;
        if($orderStatus != OrderStatusEnum::UNPAID) {
            throw new OrderException([
                'msg' => '订单已经支付',
                'errorCode' => 80003
            ]);
        }
        $this->orderNo = $order->order_no;
        return $this->orderNo;
    }
}