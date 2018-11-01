<?php
namespace app\api\model;

class Category extends BaseModel{
    public function img() {
        return self::belongsTo('Image', 'topic_img_id', 'id');
    }
}