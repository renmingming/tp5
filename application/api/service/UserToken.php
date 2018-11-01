<?php
namespace app\api\service;

use think\facade\Cache;
use app\lib\exception\WeChatException;
use app\api\model\User as UserModel;
use app\lib\exception\TokenException;
use app\lib\enum\ScopeEnum;

class UserToken extends Token{

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
                return $this->grantToken($wxResult);

            }
        }
    }

    private function grantToken($wxResult) {
        // 1拿到openid
        // 去数据库查看openid是否存在
        // 如果存在不处理，如果不存在新增一条用户信息
        // 生成令牌，准备缓存数据，写入缓存
        // 把令牌返回到客户端

        // key:令牌，value: wxResult+uid+scope
        $openid = $wxResult['openid'];
        $user = UserModel::getByOpenID($openid);
        if($user) {
            $uid = $user->id;
        }else{
            $uid = $this->newUser($openid);
        }
        $cachedValue = $this->prepareCachedValue($wxResult, $uid);
        $token = $this->saveToCache($cachedValue);
        return $token;
        // return $wxResult;
    }

    private function saveToCache($cachedValue) {
        $key = self::generateToken();
        $value = json_encode($cachedValue);
        $expire_in = config('token_expire_in');
        // 设置缓存，$key令牌
        $request = Cache::set($key, $value, $expire_in);
        if(!$request) {
            throw new TokenException([
                'msg' => '服务器缓存错误',
                'errorCode' => 10005
            ]);
        }
        return $key;
    }

    private function prepareCachedValue($wxResult, $uid) {
        $cachedValue = $wxResult;
        $cachedValue['uid'] = $uid;
        // scope16代表app用户的权限数值
        // 32代表管理人员
        $cachedValue['scope'] = ScopeEnum::User;
        return $cachedValue;
    }

    // 添加user
    private function newUser($openid) {
        $user = UserModel::create([
            'openid' => $openid
        ]);
        return $user->id;
    }

   
    private function processLoginError($wxResult) {
        throw new WeChatException([
            'msg' => $wxResult['errmsg'],
            'errorCode' => $wxResult['errcode']
        ]);
    }
}