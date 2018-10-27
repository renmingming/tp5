<?php
namespace app\lib\exception;

class WeChatException extends BaseException{
    public $code = 400;
    public $msg = '微信内部错误';
    public $errorCode = 30000;
}