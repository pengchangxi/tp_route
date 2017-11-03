<?php
namespace app\admin\controller;


use think\Controller;

class Upload extends Controller{

    /**
     * 上传图
     * */
    public function uploadify(){
        // 获取表单上传文件 例如上传了001.jpg
        $file = request()->file('file');
        // 移动到框架应用根目录/public/uploads/ 目录下,且同名不覆盖
        $info = $file->move(ROOT_PATH. 'public'  . DS . 'uploads',true,false);
        if($info){
            $host = 'http://'.$_SERVER['HTTP_HOST'].'/';
            $url = $host.'uploads'. DS .$info->getSaveName();
            return json(array('status'=>'1','msg'=>'上传成功','url'=>$url,'alt'=>$info->getFilename()));
        }else{
            // 上传失败获取错误信息
            echo $file->getError();
        }
    }
}