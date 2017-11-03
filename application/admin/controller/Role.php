<?php
namespace app\admin\controller;

use think\Loader;
use app\admin\model\Role as M;

class Role extends Base{

    protected function _search(){
        $where = array();
        $fields=array('name','status');
        foreach ($fields as $key => $val) {
            if (isset($_REQUEST[$val]) && $_REQUEST[$val] != '') {
                switch ($val) {
                    case 'name':
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
        $role = new M();
        $list = $role->index($where);
        $this->assign('list',$list);
        $this->assign('page',$list->render());
        $this->assign('total',$list->total());
        return $this->fetch();
    }

    public function add(){
        if (request()->isPost()){
            $data = input('post.');
            $validate = Loader::validate('Role');
            if(!$validate->check($data)){//表单验证
                $this->error($validate->getError()); die;
            }
            $insert_id=db('role')->insert($data);
            if ($insert_id){
                $this->success('添加成功','/admin/role/index');
            }else{
                $this->error('添加失败');
            }
        }
        return $this->fetch('edit');
    }

    public function edit(){
        if (request()->isPost()){
            $data = input('post.');
            $validate = Loader::validate('Role');
            if(!$validate->check($data)){//表单验证
                $this->error($validate->getError()); die;
            }
            $edit=db('role')->where('id',$data['id'])->update($data);
            if ($edit){
                $this->success('修改成功','/admin/role/index');
            }else{
                $this->error('修改失败');
            }

        }
        $id = $this->request->param("id");;
        $info = db('role')->where('id',$id)->find();
        $this->assign('info',$info);
        return $this->fetch();
    }

    public function del(){
        $role = new M();
        $id = $this->request->param("id");;
        $edit = $role->del($id);
        if ($edit){
            $this->success('删除成功');
        }else{
            $this->error('删除失败');
        }
    }


}