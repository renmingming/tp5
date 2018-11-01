<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006~2018 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------

Route::get('think', function () {
    return 'hello,ThinkPHP5!';
});

Route::get('api/:version/banner/:id', 'api/:version.Banner/getBanner');
Route::get('api/:version/theme', 'api/:version.Theme/getSimpleList');
Route::get('api/:version/theme/:id', 'api/:version.Theme/getComplexOne');
// 最近新品
<<<<<<< HEAD
<<<<<<< HEAD
// 路由分组
Route::group('api/:version/product', function () {
    Route::get('/by_category/:id', 'api/:version.Product/getAllInCategory');
    Route::get('/:id', 'api/:version.Product/getProductDetail', [], ['id' => '\d+']);
    Route::get('/recent', 'api/:version.Product/getRecent');
});

=======
Route::get('api/:version/product/recent', 'api/:version.Product/getRecent');
Route::get('api/:version/product/by_category/:id', 'api/:version.Product/getAllInCategory');
>>>>>>> 6abbf5501ab121f9900344d6935e2663d0b49e21
=======
Route::get('api/:version/product/recent', 'api/:version.Product/getRecent');
Route::get('api/:version/product/by_category/:id', 'api/:version.Product/getAllInCategory');
>>>>>>> 6abbf5501ab121f9900344d6935e2663d0b49e21
//分类
Route::get('api/:version/category/all', 'api/:version.Category/getAllCategories');

Route::post('api/:version/token/user', 'api/:version.Token/getToken');
<<<<<<< HEAD
<<<<<<< HEAD
Route::post('api/:version/address/user', 'api/:version.Address/createUpdateAddress');

Route::post('api/:version/order', 'api/:version.Order/PlaceOrder');
=======
>>>>>>> 6abbf5501ab121f9900344d6935e2663d0b49e21
=======
>>>>>>> 6abbf5501ab121f9900344d6935e2663d0b49e21

return [

];
