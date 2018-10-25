<?php
namespace app\api\validate;
class IDMustBePositiveInt extends BaseValidate {
    protected $rule = [
        'id' => 'require|isPostiveInteger'
    ];

    protected $message = [
        'id' => 'id必须是正整数'
    ];
}