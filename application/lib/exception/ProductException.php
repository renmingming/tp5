<?php
namespace app\lib\exception;

class ProductException extends BaseException{
    public $code = 400;
    public $msg = '所查询商品不存在';
    public $errorCode = 20000;
}