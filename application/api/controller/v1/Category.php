<?php

namespace app\api\controller\v1;

use app\api\model\Category as categoryModel;
use app\lib\exception\CategoryException;

class Category {
    public function getAllCategories() {
        $result = categoryModel::all([], 'img');
        if(!$result) {
            throw new CategoryException();
        };
        return $result;
    }
    
}