<?php

namespace app\api\validate;

class Count extends BaseValidate{
    protected $rule = [
        'count' => 'isPostiveInteger|between:1,15',
    ];
    protected $message = [
        'count' => 'count必须为1-15的正整数'
    ];
}