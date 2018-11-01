<?php

namespace app\api\controller\v1;

use app\api\validate\Count;
use app\api\validate\IDMustBePositiveInt;
use app\api\model\Product as ProductModel;
use app\lib\exception\ProductException;
use Exception;
class Product {
    public function getRecent($count=15) {
        (new Count())->goCheck();
        $products = ProductModel::getMostRecent($count);
        if($products->isEmpty()) {
            throw new ProductException();
        }
        $products = $products->hidden(['summary']);
        return $products;
    }


    public function getAllInCategory($id) {
        (new IDMustBePositiveInt())->goCheck();
        $products = ProductModel::getProductsByCategoryId($id);
        if($products->isEmpty()) {
            throw new ProductException();
        }
        return $products;
    }
<<<<<<< HEAD
<<<<<<< HEAD

    public function getProductDetail($id) {
        (new IDMustBePositiveInt())->goCheck();
        $products = ProductModel::getProductsDetailById($id);
        return $products;
    }
=======
>>>>>>> 6abbf5501ab121f9900344d6935e2663d0b49e21
=======
>>>>>>> 6abbf5501ab121f9900344d6935e2663d0b49e21
}