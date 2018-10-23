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

    public function __construct($params = []) {
        // $this为被继承的类，new的时候
        if(!is_array($params)) {
            return ;
            // throw new Exception('参数必须为整数');
        }
        if(array_key_exists('code', $params)) {
            $this->code = $params['code'];
        }

        if(array_key_exists('msg', $params)) {
            $this->msg = $params['msg'];
        }

        if(array_key_exists('errorCode', $params)) {
            $this->errorCode = $params['errorCode'];
        }
    }
}