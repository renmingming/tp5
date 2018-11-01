<?php
namespace app\api\controller\v1;

use think\Controller;
use app\api\validate\AddressNew;
use app\api\service\Token as TokenService;
use app\api\model\User as UserModel;
use app\lib\exception\ForbiddenException;
use app\lib\exception\TokenException;
use app\lib\enum\ScopeEnum;

class Address extends BaseController{
    // 前置方法
    // protected $beforeActionList = [
    //     'first' => ['only' => 'second']
    // ];
    protected $beforeActionList = [
        'checkPrimaryScope' => ['only' => 'createUpdateAddress']
    ];
    // 判断权限
    

    // 更新或创建用户收获地址
    public function createUpdateAddress() {
        $validate = new AddressNew();
        $validate->goCheck();
        // 1、根据token获取用户uid
        // 2、根据uid查找用户数据，判断用户是否存在，如果不存在抛出异常
        // 3、获取用户从客户端传来的信息
        // 4、根据用户地址信息是否存在，从而判断是添加地址还是更新地址
        $uid = TokenService::getCurrentUid();
        // 使用模型从usermodel中获取get
        $user = UserModel::get($uid);
        if(!$user) {
            throw new UserException();
        }

        $dataArray = $validate->getDataByRule(input('post.'));
        $userAddress = $user->address();
        $data = $userAddress->find();
        if(!$data) {
            // 关联属性不存在，新增
            $user->address()->save($dataArray);
        }else{
            // 新增的save来自于关联关系
            // 更新的save来自于模型
            $user->address->save($dataArray);
        }
        return [
            'msg' => 'ok',
            'errorCode'=> 0
        ];
    }
}