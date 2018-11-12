<?php
namespace app\api\service;
use think\facade\Request;
use think\facade\Cache;
use app\lib\exception\TokenException;
use app\lib\exception\ForbiddenException;
use app\lib\enum\ScopeEnum;

class Token {
    public static function generateToken() {
        // 32位随机一组字符串
        $randChars = getRandChar(32);
        // 用三组字符串进行md5加密；
        // 当前时间戳
        $timestamp = $_SERVER["REQUEST_TIME"];
        // 盐
        $salt = config('token_salt');
        return md5($randChars.$timestamp.$salt);
    }
    // 获取缓存中的token，根据所传key在token中获取其对应的val值
    public static function getCurrentTokenVal($key) {
        $token = Request::instance()
            ->header('token');
        $vars = Cache::get($token);
        if(!$vars) {
            throw new TokenException();
        } else {
            if(!is_array($vars)) {
                $vars = json_decode($vars, true);
            }
            if(array_key_exists($key, $vars)) {
                return $vars[$key];
            } else {
                throw new \Exception('尝试获取的token变量不存在');
            }
        }
    }
    // 获取用户uid
    public static function getCurrentUid() {
        // token;
        $uid = self::getCurrentTokenVal('uid');
        return $uid;
    }
    // 权限校验:用户管理员都可以访问
    public static function needPrimaryScope() {
        $scope = self::getCurrentTokenVal('scope');
        if($scope) {
            if($scope >= ScopeEnum::User) {
                return true;
            }else{
                throw new ForbiddenException();
            }
        } else {
            throw new TokenException();
        }
    }
    // 权限校验:只有用户可以访问
    public static function needExclusiveScope() {
        $scope = self::getCurrentTokenVal('scope');
        if($scope) {
            if($scope == ScopeEnum::User) {
                return true;
            }else{
                throw new ForbiddenException();
            }
        } else {
            throw new TokenException();
        }
    }

    // 检测传进来的uid是否和token里的uid一致
    public function isValidOperate($checkedUID) {
        if(!$checkedUID) {
            throw new Exception('检测UID是否一致时，必须传入一个UID');
        }
        $currentOperateUID = self::getCurrentUid();
        if($currentOperateUID == $checkedUID) {
            return true;
        }
        return false;
    }
}