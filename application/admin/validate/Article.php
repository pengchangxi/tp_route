<?php
namespace app\admin\validate;

use think\Validate;

class Article extends Validate{

    protected $rule = [
        'title'  =>  'min:2|require',
    ];

    protected $message = [
        'title.min' =>'标题长度不能小于2',
        'title.require' =>'标题不能为空'
    ];
}