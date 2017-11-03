<?php
namespace app\admin\validate;

use think\Validate;

class Menu extends Validate{

    protected $rule = [
        'name'  =>  'require',
    ];

    protected $message = [
        'name.require' => '节点不能为空',
    ];
}