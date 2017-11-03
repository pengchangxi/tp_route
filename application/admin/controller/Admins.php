<?php
namespace app\admin\controller;

use think\Loader;
use app\admin\model\Admins as M;

class Admins extends Base{

    protected function _search(){
        $where = array();
        $fields=array('account','realname','status');
        foreach ($fields as $key => $val) {
            if (isset($_REQUEST[$val]) && $_REQUEST[$val] != '') {
                switch ($val) {
                    case 'account':
                        $where [$val] = array('like','%'.$_REQUEST[$val].'%');
                        break;
                    case 'realname':
                        $where [$val] = array('like','%'.$_REQUEST[$val].'%');
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
        $where = $this->_search();
        $admins = new M();
        $list = $admins->index($where);
        $this->assign('list',$list);
        $this->assign('total',$list->total());
        $this->assign('page',$list->render());
        $roles = db('role')->select();
        $this->assign('roles',$roles);
        return $this->fetch();
    }

    public function add(){
        if (request()->isPost()){
            $data = input('post.');
            $validate = Loader::validate('Admins');
            if(!$validate->check($data)){//表单验证
                $this->error($validate->getError()); die;
            }
            $salt = random(6);
            $data['password'] = md5($data['password'].$salt);
            $data['salt'] = $salt;
            $data['create_time'] = time();
            $insert_id = db('admins')->insert($data);
            if ($insert_id){
                $this->success('添加成功','/admin/admins/index');
            }else{
                $this->error('添加失败');
            }
        }
        $roleList = db('role')->select();
        $this->assign('roleList',$roleList);
        return $this->fetch('edit');
    }

    public function edit(){
        if (request()->isPost()){
            $data = input('post.');
            $validate = Loader::validate('Admins');
            if(!$validate->check($data)){//表单验证
                $this->error($validate->getError()); die;
            }
            if ($data['password']){
                $salt = random(6);
                $data['password'] = md5($data['password'].$salt);
                $data['salt'] = $salt;
            }
            $data['update_time'] = time();
            $edit = db('admins')->where('id',$data['id'])->update($data);
            if ($edit){
                $this->success('添加成功','/admin/admins/index');
            }else{
                $this->error('添加失败');
            }
        }
        $id = $this->request->param("id");;
        $info = db('admins')->where('id',$id)->find();
        $roleList = db('role')->select();
        $this->assign('roleList',$roleList);
        $this->assign('info',$info);
        return $this->fetch('edit');
    }

    public function del(){
        $admin = new M();
        $id = $this->request->param("id");;
        $edit = $admin->del($id);
        if ($edit){
            $this->success('删除成功');
        }else{
            $this->error('删除失败');
        }
    }
}