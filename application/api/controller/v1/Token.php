<?php
namespace app\api\controller\v1;

use app\api\validate\TokenGet;
use app\api\service\UserToken;

class Token {
    public function getToken($code='') {
        (new TokenGet())->goCheck();
        $ut = new UserToken($code);
<<<<<<< HEAD
<<<<<<< HEAD
        $token = $ut->get();
        return [
            'token' => $token
        ];
=======
        $openId = $ut->get();
        return $openId;
>>>>>>> 6abbf5501ab121f9900344d6935e2663d0b49e21
=======
        $openId = $ut->get();
        return $openId;
>>>>>>> 6abbf5501ab121f9900344d6935e2663d0b49e21
    }
}