<?php
namespace app\admin\controller;

use tree\Tree;
use think\Loader;
use app\admin\model\Menu as M;


class Menu extends Base{

    public function index(){

        $objResult = db('menu')->select();

        $tree       = new Tree();
        $tree->icon = ['&nbsp;&nbsp;&nbsp;│ ', '&nbsp;&nbsp;&nbsp;├─ ', '&nbsp;&nbsp;&nbsp;└─ '];
        $tree->nbsp = '&nbsp;&nbsp;&nbsp;';
        $array = [];
        foreach ($objResult as $r) {
            $r['str_manage'] = "<a title=\"添加\" href='".url('/admin/menu/add',array('pid'=>$r['id']))."'  class=\"ml-5\" style=\"text-decoration:none\"><i class=\"icon-plus\"></i>添加子节点</a>
            <a title=\"编辑\" href='".url('/admin/menu/edit',array('id'=>$r['id']))."'  class=\"ml-5\" style=\"text-decoration:none\"><i class=\"icon-edit\"></i>编辑</a>
            <a title=\"删除\" href=\"javascript:;\" onclick=\"menu_del(this,'".$r['id']."')\" class=\"ml-5\" style=\"text-decoration:none\"><i class=\"icon-trash\"></i>删除</a>";
            $r['status']     = $r['status'] ? "<span class=\"label label-success radius\">显示</span>" : "<span class=\"label label-warning radius\">隐藏</span>";
            $array[]         = $r;
        }

        $tree->init($array);
        $str = "<tr >
            <td >\$id</td>
            <td >\$spacer\$name</td>
            <td>\$url</td>
            <td>\$status</td>
            <td>\$str_manage</td>
        </tr>";

        $categories = $tree->getTree(0, $str);

        $this->assign("categories", $categories);
        return $this->fetch();
    }

    public function add(){
        if (request()->isPost()){
            $data = input('post.');
            $validate = Loader::validate('Menu');
            if (!$validate->check($data)){
                $this->error($validate->getError());die();
            }
            $pmenu=db('menu')->where('id',$data['pid'])->find();
            $data['level']=$pmenu?$pmenu['level'] + 1 : 0;
            $data['list_order']= $data['list_order'] ? $data['list_order'] : 99;
            $insert_id = db('menu')->insert($data);
            if ($insert_id){
                $this->success('添加成功','/admin/menu/index');
            }else{
                $this->error('添加失败');
            }
        }
        $list = db('menu')->select();
        $intParentId  = $this->request->param("pid");
        $tree       = new Tree();
        $tree->icon = ['&nbsp;│ ', '&nbsp;├─ ', '&nbsp;└─ '];
        $tree->nbsp = '&nbsp;';
        $array      = [];
        foreach ($list as $r) {
            $r['selected']   = $r['id'] == $intParentId ? "selected" : "";
            $array[]         = $r;
        }
        $tree->init($array);
        $str      = "<option value='\$id' \$selected>\$spacer\$name</option>";
        $navTrees = $tree->getTree(0, $str);
        $this->assign("nav_trees", $navTrees);
        return $this->fetch('edit');
    }

    public function edit(){
        if (request()->isPost()){
            $data = input('post.');
            $validate = Loader::validate('Menu');
            if (!$validate->check($data)){
                $this->error($validate->getError());die();
            }
            $edit = db('menu')->where('id',$data['id'])->update($data);
            if ($edit){
                $this->success('修改成功','/admin/menu/index');
            }else{
                $this->error('修改失败');
            }
        }
        $list = db('menu')->select();
        $id  = $this->request->param("id");
        $info = db('menu')->where('id',$id)->find();
        $intParentId = $info['pid'];
        $tree       = new Tree();
        $tree->icon = ['&nbsp;│ ', '&nbsp;├─ ', '&nbsp;└─ '];
        $tree->nbsp = '&nbsp;';
        $array      = [];
        foreach ($list as $r) {
            $r['selected']   = $r['id'] == $intParentId ? "selected" : "";
            $array[]         = $r;
        }
        $tree->init($array);
        $str      = "<option value='\$id' \$selected>\$spacer\$name</option>";
        $navTrees = $tree->getTree(0, $str);
        $this->assign("nav_trees", $navTrees);
        $this->assign('info',$info);
        return $this->fetch();
    }

    public function del(){
        $menu = new M();
        $id = $this->request->param('id');
        $edit = $menu->del($id);
        if ($edit){
            $this->success('删除成功');
        }else{
            $this->error('删除失败');
        }
    }
}