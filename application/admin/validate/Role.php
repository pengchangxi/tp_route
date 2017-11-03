<?php
namespace app\admin\validate;

use think\Validate;

class Role extends Validate{

    protected $rule = [
        'name'  =>  'min:2|require|unique:role',
    ];

    protected $message  =   [
        'name.min' => '角色名称长度不能小于2',
        'name.require' => '角色名称不能为空',
        'name.unique' => '角色名称已存在',
    ];
}