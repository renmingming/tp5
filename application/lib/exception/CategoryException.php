<?php
namespace app\lib\exception;

class CategoryException extends BaseException{
    public $code = 400;
    public $msg = '暂无分类';
    public $errorCode = 50000;
}