<?php
namespace app\admin\controller;

use app\admin\model\Guide as M;

class Guide extends Base{

    protected function _search(){
        $where = array();
        $fields = array('1','2');
        foreach ($fields as $key=>$val){
            if (isset($_REQUEST[$val]) && $_REQUEST[$val] != '') {
                switch ($val){
                    case '':
                        break;
                    default:
                        $where [$val] = $_REQUEST[$val];
                        break;
                }
                $this->assign($val, $_REQUEST[$val]);
            }
        }
        return $where;
    }

    public function index(){
        $guide = new M();
        $list = $guide->index();
        $this->assign('list',$list);
        $this->assign('page',$list->render());
        return $this->fetch();
    }

    public function add(){
        if (request()->isPost()){
            $data = input('post.');
            foreach ($data['photo'] as $k=>$v){
                $data['photo'][$k] = $v;
            }
            $data['photo'] = json_encode($data['photo'],JSON_FORCE_OBJECT);
            $data['create_time'] = time();
            $insert_id = db('guide')->insert($data);
            if ($insert_id){
                $this->success('添加成功','/admin/guide/index');
            }else{
                $this->error('添加失败','/admin/guide/index');
            }
        }
        return $this->fetch();
    }

    public function edit(){
        if (request()->isPost()){
            $data = input('post.');
            foreach ($data['photo'] as $k=>$v){
                $data['photo'][$k] =$v;
            }
            $data['photo'] = json_encode($data['photo'],JSON_FORCE_OBJECT);
            $data['update_time'] = time();
            $edit = db('guide')->where('id',$data['id'])->update($data);
            if ($edit){
                $this->success('修改成功','/admin/guide/index');
            }else{
                $this->error('修改失败','/admin/guide/index');
            }
        }
        $id = $this->request->param('id');
        $info = db('guide')->where('id',$id)->find();
        $photos = json_decode($info['photo'],true);
        $this->assign('info',$info);
        $this->assign('photos',$photos);
        return $this->fetch();

    }

    public function del(){
        $guide = new M();
        $id = $this->request->param('id');
        $edit = $guide->del($id);
        if ($edit){
            $this->success('删除成功');
        }else{
            $this->error('删除失败');
        }
    }


}