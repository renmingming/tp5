<?php

namespace app\api\controller\v1;

use app\api\validate\IDCollection;
use app\api\validate\IDMustBePositiveInt;
use app\api\model\Theme as ThemeModel;
use app\lib\exception\ThemeException;

class Theme{
    /**
     * 显示资源列表
     *
     * @url /theme?ids=id1,id2
     * @return 
     */
    public function getSimpleList($ids='') {
        (new IDCollection())->goCheck();
        $ids = explode(',', $ids);
        $result = ThemeModel::with(['topicImg', 'headImg'])
            ->select($ids);
        if($result->isEmpty()) {
            throw new ThemeException();
        }
        return json($result);
    }
    /**
     * @url /theme/:id
     */
    public function getComplexOne($id) {
        (new IDMustBePositiveInt())->goCheck();
        $result = ThemeModel::getThemeWithProducts($id);
        if(!$result) {
            throw new ThemeException();
        }
        return json($result);
    }
}
