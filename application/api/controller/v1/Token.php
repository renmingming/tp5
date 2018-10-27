<?php
namespace app\api\controller\v1;

use app\api\validate\TokenGet;
use app\api\service\UserToken;

class Token {
    public function getToken($code='') {
        (new TokenGet())->goCheck();
        $ut = new UserToken($code);
        $openId = $ut->get();
        return $openId;
    }
}