<?php
namespace app\lib\exception;

class WeChatException extends BaseException{
    public $code = 400;
<<<<<<< HEAD
    public $msg = 'Token令牌获取失败';
=======
    public $msg = '微信内部错误';
>>>>>>> 6abbf5501ab121f9900344d6935e2663d0b49e21
    public $errorCode = 30000;
}