<?php

namespace app\api\valitate;
use think\Validate;

class TestValitate extends Validate {
    protected $rule = [
        'name' => 'max:5',
        'email' => 'email'
    ];
}