<?php
namespace app\api\controller\v1;

use app\api\controller\BaseController;
use app\api\validate\OrderPlace;
use app\api\service\Order as OrderService;
use app\api\service\Token as TokenService;

class Order extends BaseController{
    // 1、用户在选择商品后，向api提交包含他所选商品的信息
    // 2、api在接收到信息后，需要检查订单相关商品的库存量 
    // 3、有库存，把订单数据存入数据库中，下单成功了，返回客户端信息，告诉其可以支付了
    // 4、调用我们的支付接口，进行支付
    // 5、还要再次检测库存数量
    // 6、服务端就可以掉用微信的支付接口进行支付
    // 7、微信会返回给我们一个支付结果
    //  根据返回的支付结果，
    // 成功：进行库存量检测
    // 成功：进行库存量的扣除，失败：返回一个支付失败结果

    protected $beforeActionList = [
        'checkExclusiveScope' => ['only' => 'placeOrder']
    ];

    public function placeOrder() {
        (new OrderPlace())->goCheck();
        $products = input('post.products/a');
        $uid = TokenService::getCurrentUid();

        $order = new OrderService();
        $status = $order->place($uid, $products);
        return $status;
    }
}