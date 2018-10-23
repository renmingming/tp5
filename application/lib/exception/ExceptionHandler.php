<?php
namespace app\lib\exception;
use Exception;
use think\exception\Handle;
use think\facade\Request;
// use think\Log;
use think\facade\Log;

class ExceptionHandler extends Handle {
    private $code;
    private $msg;
    private $errorCode;
    // 返回客户端当前请求的url
    public function render(Exception $e) {
        if($e instanceof BaseException) {
            // 如果是自定义的异常
            $this->code = $e->code;
            $this->msg = $e->msg;
            $this->errorCode = $e->errorCode;
        } else {
            $switch = config('app_debug');
            if($switch) {
                // 调用原来的render
                return parent::render($e);
            } else{
                $this->code = 500;
                $this->msg = '服务器内部错误';
                $this->errorCode = 999;
                $this->recordErrorLog($e);
            }
        }
        $request = Request::instance();
        $result = [
            'msg' => $this->msg,
            'error_code' => $this->errorCode,
            'request_url' => $request->url()
        ];
        return json($result, $this->code);
    }

    public function recordErrorLog(Exception $e) {
        Log::record($e->getMessage(), 'error');
    }
}