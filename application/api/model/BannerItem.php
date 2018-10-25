<?php

namespace app\api\model;

use think\Model;

class BannerItem extends BaseModel
{
    protected $hidden = ['delete_time', 'id', 'img_id', 'banner_id'];
    public function img() {
        // 一对一belongsTo
        return $this->belongsTo('Image', 'img_id', 'id');
    }
}
