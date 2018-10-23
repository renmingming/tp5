<?php
namespace app\api\validate;
use think\Validate;
use think\facade\Request;
use think\Exception;
use app\lib\exception\ParameterException;

class BaseValidate extends Validate{
    public function goCheck() {
        // 获取http传入的参数，对其进行检验
        $request = Request::instance();
        $param = $request->param();

        $result = $this->check($param);
        if(!$result) {
            $e = new ParameterException([
                'msg' => $this->error
            ]);
            // $e->msg = $this->error;
            throw $e;
            // $error = $this->error;
            // throw new Exception($error);
        }else{
            return true;
        }
    }
}