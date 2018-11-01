<?php
namespace app\lib\exception;

class OrderException extends BaseException {
    public $code = 404;
    public $msg = '订单不存在，请检查ids';
    public $errorCode = 800000;
}