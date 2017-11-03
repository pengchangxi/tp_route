<?php
namespace app\admin\controller;

use think\Controller;

class Error extends Controller{

    //空操作
    public function _empty(){
        $this->error('当前控制器不存在');
    }
}