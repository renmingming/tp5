<?php
namespace app\lib\exception;
use think\Exception;

class BaseException extends Exception {
    // http 状态吗 400 200 
    public $code = 400;

    // 错误信息
    public $msg = '参数错误';

    // 错误状态吗
    public $errorCode = 10000;
}