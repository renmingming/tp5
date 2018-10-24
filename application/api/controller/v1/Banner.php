<?php
/*
 * @Author: mingming 
 * @Date: 2018-10-16 17:44:24 
 * @Last Modified by: mingming
 * @Last Modified time: 2018-10-16 21:07:21
 */
namespace App\api\controller\v1;
use think\Validate;
use think\Exception;
use app\api\validate\IDMustBePositiveInt;
use app\api\model\Banner as BannerModel;
use app\lib\exception\BannerMissException;

class Banner {
    // 获取制定id的banner信息
    // @url /banner/:id
    // @http GET
    // @id banner的id
    public function getBanner($id) {
        (new IDMustBePositiveInt())->goCheck();
        // $banner = BannerModel::getBannerById($id);
        $banner = BannerModel::with("items")->find($id);
        // $banner1 = BannerModel::get($id);
        // $banner = $banner1->items; 获取到的是bannerItem数据
        // get find all select
       if(!$banner) {
           throw new BannerMissException();
       }
        return json($banner);
    }
}