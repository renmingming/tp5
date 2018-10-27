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
    // 判断是否为正整数
    protected function isPostiveInteger($value, $rule = '', $data = '', $field = '') {
        if(is_numeric($value) && is_int($value + 0) && ($value + 0) > 0) {
            return true;
        } else {
            // return $field.'必须是正整数';
            return false;
        }
    }
    // 判断是否为空
    protected function isNotEmpty($value, $rule='', $date='', $field='') {
        if(empty($value)) {
            return false;
        }else{
            return true;
        }
    }
}