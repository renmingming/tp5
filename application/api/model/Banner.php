<?php
namespace app\api\model;

use think\Exception;
use think\Db;
use think\Model;

class Banner extends Model{
    public static function getBannerById($id) {
        // $result = Db::query('select * from banner_item where banner_id=?', [$id]);
        // $result = Db::table('banner_item')->where('id',$id)->select();
        // dump($result);
        $result = Db::table('banner_item')
            // ->where('banner_id','=', $id)
            // 闭包方式
            ->where(function($query) use ($id){
                $query->where('banner_id', '=', $id);
            })
            ->select();

        return $result;
    }
}