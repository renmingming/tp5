<?php
namespace app\api\service;

use app\lib\exception\WeChatException;

class UserToken {
    protected $code;
    protected $wxAppID;
    protected $wxAppSecret;
    protected $wxLoginUrl;

    public function __construct($code) {
        $this->code = $code;
        $this->wxAppID = config('app_id');
        $this->wxAppSecret = config('app_secret');
        $this->wxLoginUrl = sprintf(config('login_url'), $this->wxAppID, $this->wxAppSecret, $this->code);
    }

    public function get() {
        $id = config('app_id');
        $appid = $this->wxAppID;
        $result = curl_get($this->wxLoginUrl);
        // 字符串转数组
        $wxResult = json_decode($result, true);
        if(empty($wxResult)) {
            throw new \Exception('获取session_key及openid时异常，微信内部错误');
        }else{
            $loginFail = array_key_exists('errcode', $wxResult);
            if($loginFail) {
                $this->processLoginError($wxResult);
            } else {
                $this->grantToken($wxResult);
            }
        }
    }

    private function grantToken($wxResult) {
        // 1拿到openid
        // 去数据库查看openid是否存在
        // 如果存在不处理，如果不存在新增一条用户信息
        // 生成令牌，准备缓存数据，写入缓存
        // 把令牌返回到客户端
        $openid = $wxResult['openid'];
        return $wxResult;
    }

    private function processLoginError($wxResult) {
        throw new WeChatException([
            'msg' => $wxResult['errmsg'],
            'errorCode' => $wxResult['errcode']
        ]);
    }
}