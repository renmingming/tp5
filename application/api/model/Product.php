<?php

namespace app\api\model;

class Product extends BaseModel {
    protected $hidden = ['delete_time', 'pivot', 'category_id', 'from', 'update_time', 'img_id'];

    public function getMainImgUrlAttr($value, $data) {
        return $this->prefixImgUrl($value, $data);
    }

    public static function getMostRecent($count) {
        $products = self::limit($count)
            ->order('create_time desc')
            ->select();
        return $products;
    }

    public static function getProductsByCategoryId($categoryID) {
        $category = self::where('category_id', '=', $categoryID)
            ->select();
        return $category;
    }
}
