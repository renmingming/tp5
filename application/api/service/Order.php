<?php
namespace app\api\service;

use app\api\model\Product;
use app\api\model\UserAddress;
use app\api\model\OrderProduct;
use think\Db;

class Order{
    // 订单的商品列表，也是客户端传过来的products参数
    protected $oProducts;

    // 真实的商品信息（包括库存量)
    protected $products;

    protected $uid;

    public function place($uid, $oProducts) {
        // oProducts和products做对比
        // products从数据库中查出来
        $this->oProducts = $oProducts;
        $this->products = $this->getProductsByOrder($oProducts);
        $this->uid = $uid;
        $status = $this->getOrderStatus();
        if(!$status['pass']) {
            // 对比不通过
            $status['order_id'] = -1;
            return $status;
        }

        // 开始创建订单
        $orderSnap = $this->snapOrder($status);
        $order = $this->createOrder($orderSnap);
        $order['pass'] = true;

        return $order;
    }
    
    // 将订单信息写入数据库
    private function createOrder($snap) {
        // 启动事务
        Db::startTrans();
        try {
            $orderNo = $this->makeOrderNo();
            $order = new \app\api\model\Order();
            $order->user_id = $this->uid;
            $order->order_no = $orderNo;
            $order->total_price = $snap['orderPrice'];
            $order->total_count = $snap['totalCount'];
            $order->snap_img = $snap['snapImg'];
            $order->snap_address = $snap['snapAddress'];
            $order->snap_items = json_encode($snap['pStatus']);
            // 写入数据库
            $order->save();
            $orderID = $order->id;
            $create_time = $order->create_time;
            // 向oProducts也就是传进来的商品信息添加order_id
            foreach($this->oProducts as &$p) {
                $p['order_id'] = $orderID;
            }

            $orderProduct = new OrderProduct();
            $orderProduct->saveAll($this->oProducts);
            Db::commit(); // 提交事务

            return [
                'order_no' => $orderNo,
                'order_id' => $orderID,
                'create_time' => $create_time
            ];
        } catch (\Exception $ex) {
            Db::rollback(); // 回滚事务
            throw $ex;
        }
    }
    // 生成订单号   
    public function makeOrderNo() {
        $yCode = array('A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J');
        $orderSn = $yCode[intval(date('Y')) - 2017] . strtoupper(dechex(date('m')))
            . date('d') . substr(time(), -5) . substr(microtime(), 2, 5) . 
            sprintf('%02d', rand(0,99));
        return $orderSn;
    }

    // 生成订单快照
    private function snapOrder($status) {
        $snap = [
            'orderPrice' => 0,
            'totalCount' => 0,
            'pStatus' => [],
            'snapAddress' => null,
            'snapName' => '',
            'snapImg' => ''
        ];

        $snap['orderPrice'] = $status['orderPrice'];
        $snap['totalCount'] = $status['totalCount'];
        $snap['pStatus'] = $status['pStatusArray'];
        $snap['snapAddress'] = json_encode($this->getUserAddress());
        $snap['snapName'] = $this->products[0]['name'];
        $snap['snapImg'] = $this->products[0]['main_img_url'];
        if(count($this->products)>1) {
            $snap['snapName'] .= '等';
        }
        return $snap;
    }
    // 根据uid获取用户收获地址
    private function getUserAddress() {
        $userAddress = UserAddress::where('user_id','=', $this->uid)->find();
        if(!$userAddress) {
            throw new UserException([
                'msg' => '用户收获地址不存在，下单失败',
                'errorCode' => 60001
            ]);
        }

        return $userAddress->toArray();
    }

    private function getOrderStatus() {
        $status = [
            'pass' => true, // 对比是否通过
            'orderPrice' => 0, // 商品总价
            'totalCount' => 0, 
            'pStatusArray' => [] // 保存商品信息
        ];

        foreach($this->oProducts as $oProduct) {
            $pStatus = $this->getProductStatus(
                $oProduct['product_id'],$oProduct['count'], $this->products
            );
            if(!$pStatus['haveStock']) {
                $status['pass'] = false;
                $status['errStock'] = $pStatus['errStock'];
            }
            $status['orderPrice'] += $pStatus['totalPrice'];
            $status['totalCount'] += $pStatus['count'];
            array_push($status['pStatusArray'], $oProduct);
        }
        return $status;
    }
    // 根据商品id，count于数据库中的商品信息对比，返回$pStatus;
    private function getProductStatus($oPID, $oCount, $products) {
        $pIndex = -1;
        $pStatus = [
            'id' => null,
            'haveStock' => false,
            'count' => 0,
            'name' => '',
            'totalPrice' => 0,
            'errStock' => ''
        ];
        for($i=0; $i<count($products);$i++) {
            if($oPID == $products[$i]['id']) {
                $pIndex = $i;
            }
        }
        if($pIndex == -1) {
            // 客户端传过来的商品id可能不存在
            throw new OrderException([
                'msg' => 'id为'.$oPID.'的商品不存在，创建订单失败'
            ]);
        } else {
            $product = $products[$pIndex];
            $pStatus['id'] = $product['id'];
            $pStatus['name'] = $product['name'];
            $pStatus['count'] = $oCount;
            $pStatus['totalPrice'] = $product['price']*$oCount;
            if($product['stock'] - $oCount >= 0) {
                $pStatus['haveStock'] = true;
                $pStatus['errStock'] = '';
            }else{
                $pStatus['errStock'] = '请确定商品Id:'.$product['id'].'库存是否充足';
            }
           
        }
        return $pStatus;
    }

    // 根据订单查询真实的商品信息
    private function getProductsByOrder($oProducts) {
        // foreach($oProducts as $oProduct) {
        //     // 避免->循环的查询数据库
        // }
        $oPIDs = [];
        foreach($oProducts as $item) {
            array_push($oPIDs, $item['product_id']);
        }

        $products = Product::all($oPIDs)
            ->visible(['id', 'price', 'stock', 'name', 'main_img_url'])
            ->toArray();

        return $products;
    }
}