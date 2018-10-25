<?php

namespace app\api\model;

use think\Model;

class BaseModel extends Model
{
    // 读取器get字段名attr
    protected function prefixImgUrl($value, $data) {
        $finalUrl = $value;
        if($data['from'] == 1) {
            $finalUrl = config('img_prefix').$value;
        }
        return $finalUrl;
    }
}
