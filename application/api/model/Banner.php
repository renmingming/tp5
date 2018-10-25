<?php
namespace app\api\model;

use think\Exception;
use think\Db;
use think\Model;

class Banner extends BaseModel{
    // protected $table = 'category';
    protected $hidden = ['delete_time']; // 隐藏
    // protected $visible = [] 只显示
    public function items() {
        // 参数1、关联模型名    2、banner于banner_item关联名字：外健   3、当前模型id
        // 一对多hasMany
        return $this->hasMany('BannerItem', 'banner_id', 'id');
    }
    public static function getBannerById($id) {
        // $result = Db::query('select * from banner_item where banner_id=?', [$id]);
        // $result = Db::table('banner_item')->where('id',$id)->select();
        // $result = Db::table('banner_item')
        //     // ->where('banner_id','=', $id)
        //     // 闭包方式
        //     ->where(function($query) use ($id){
        //         $query->where('banner_id', '=', $id);
        //     })
        //     ->select();
        $banner = self::with(["items", "items.img"])->find($id);
        return $banner;
    }
}