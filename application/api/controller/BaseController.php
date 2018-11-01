<?php
namespace app\api\controller;

use think\Controller;
use app\api\service\Token as TokenService;

class BaseController extends Controller{
    protected function checkPrimaryScope() {
        $scope = TokenService::needPrimaryScope();
    }
    protected function checkExclusiveScope() {
        $scope = TokenService::needExclusiveScope();
    }
}