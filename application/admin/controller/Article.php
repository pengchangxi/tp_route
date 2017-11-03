<?php
namespace app\admin\controller;

use think\Loader;
use app\admin\model\Article as M;

class Article extends Base{

    public function index(){
        $article = new M();
        $list = $article->index();
        $this->assign('list',$list);
        $this->assign('page',$list->render());
        return $this->fetch();
    }

    public function add(){
        if (request()->isPost()){
            $data = input('post.');
            $validate = Loader::validate('article');
            if (!$validate->check($data)){
                $this->error($validate->getError());die();
            }
            $data['create_time'] = time();
            $insert_id = db('article')->insert($data);
            if ($insert_id){
                $this->success('添加成功','/admin/article/index');
            }else{
                $this->error('添加失败','admin/article/index');
            }
        }
        return $this->fetch('edit');
    }

    public function edit(){
        if (request()->isPost()){
            $data = input('post.');
            $validate = Loader::validate('article');
            if (!$validate->check($data)){
                $this->error($validate->getError());die();
            }
            $data['update_time'] = time();
            $edit = db('article')->where('id',$data['id'])->update($data);
            if ($edit){
                $this->success('修改成功','/admin/article/index');
            }else{
                $this->error('修改失败','admin/article/index');
            }
        }
        $id = $this->request->param('id');
        $info = db('article')->where('id',$id)->find();
        $this->assign('info',$info);
        return $this->fetch();
    }
}