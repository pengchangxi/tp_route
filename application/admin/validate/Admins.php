<?php
namespace app\admin\validate;

use think\Validate;

class Admins extends Validate{

    protected $rule = [
        'account'  =>  'min:6|require|unique:admins',
        'mobile'=>'regex:/^(1)[0-9]{10}$/|unique:admins',
        'password'=>'require|min:6'
    ];

    protected $message  =   [
        'account.min' => '账户长度不能小于6',
        'account.require' => '账户不能为空',
        'account.unique' => '账户已存在',
        'mobile.regex'=>'手机号不正确',
        'mobile.unique'=>'手机号已存在',
        'password.require'=>'密码不能为空',
        'password.min'=>'密码不能少于6位数'
    ];
}