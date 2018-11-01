<?php

namespace app\api\model;

use think\Model;

class ProductImage extends BaseModel{
    //
    protected $hidden = ['delete_time', 'product_id', 'img_id'];
    public function imgUrl() {
        // 一对一belongsTo
        return $this->belongsTo('Image', 'img_id', 'id');
    }
}
