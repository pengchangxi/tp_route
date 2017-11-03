<?php
namespace app\admin\controller;

use think\Controller;
use think\Url;
use think\Request;
use app\admin\model\Menu;
use rbac\Rbac;

class Base extends Controller{

    protected $ignoreAction=array('login'); // 不需要验证的动作

    public function _initialize(){
        if (!session('adminInfo')){
            $this->redirect(Url::build('/admin/login/index'));
        }
        $request = Request::instance();
        $module = $request->module();
        $controller = $request->controller();
        $controller = strtolower(preg_replace('/((?<=[a-z])(?=[A-Z]))/', '_', $controller));//将AbcDef转化为abc_def
        $action =$request->action();
        $url = '/' . $module . '/' . $controller . '/' . $action;
        $role_id = session('adminInfo')['role_id'];
        if (!in_array($request->controller(),$this->ignoreAction)) {
            if ($request->controller() != 'Index') {
                if ($role_id!=1){//超级管理员不用验证权限
                    $menuList = Rbac::getAccessList($role_id,$url);
                    if (!isset($menuList)){
                        $this->error('暂无权限操作');
                    }
                }
            }
        }
        $currentnode=db('menu')->where('url',$url)->find();
        if ($currentnode){
            if ($currentnode['islog']){//操作记录
                $this->adminlog();
            }
            $this->assign('menuid',$currentnode['ismenu']?$currentnode['id']:$currentnode['pid']);
        }else{
            $this->assign('menuid',0);
        }
        $this->get_menu();
        $crumbs = $this->crumbs();
        $this->assign('crumbs',$crumbs);
        $this->assign('adminInfo',session('adminInfo'));
    }

    //面包屑
    public function crumbs(){
        $request = Request::instance();
        $module = $request->module();
        $controller = $request->controller();
        $controller = strtolower(preg_replace('/((?<=[a-z])(?=[A-Z]))/', '_', $controller));//将AbcDef转化为abc_def
        $action =$request->action();
        $url = $module . '/' . $controller . '/' . $action;
        $r = db('menu')->where('url','like',"%$url")->field('id,pid,name')->find();
        if ($r['pid']){
            $parent = db('menu')->where('id',$r['pid'])->value('name');
            return $parent.' &gt; '.$r['name'];
        }
        return $r['name'];
    }

    //获取主菜单
    protected function get_menu(){
        if(isset($_SESSION['menulist'])){
            $menulist=$_SESSION['menulist'];
            $this->assign('menulist',$menulist);
        }else{
            $map['ismenu']=1;
            $map['status']=1;
            $roleid=session('adminInfo')['role_id'];
            if($roleid==1){ //超级管理员 一般只能有一个用户 或者干脆就限制 admin_id ==1
                $nodes=db('menu')->where($map)->order('list_order desc,id desc')->select();
            }else{
                $menuList=new Menu();
                $nodes=$menuList->getMenuList($roleid);
            }
            $menulist=array();
            if($nodes){
                foreach($nodes as $key=>$node){
                    unset($node->level);
                    //$nodes[$key]=get_object_vars($node);
                }
                $menulist = $this->list_to_tree($nodes,'id','pid');
            }
            $this->assign('menulist',$menulist);
        }
    }

    //将数组转化为树形结构
    protected function list_to_tree($list, $pk='id',$pid = 'pid',$child = '_child',$root=0,$iscurrent=false) {
        // 创建Tree
        $tree = array();
        if(is_array($list)) {
            // 创建基于主键的数组引用
            $refer = array();
            foreach ($list as $key => $data) {
                $refer[$data[$pk]] =& $list[$key];
                $refer[$data[$pk]]['current'][]=& $list[$key][$pk];
            }

            foreach ($list as $key => $data) {
                // 判断是否存在parent
                $parentId = $data[$pid];
                if ($root == $parentId) {
                    $tree[$data[$pk]] =& $list[$key];
                }else{
                    if (isset($refer[$parentId])) {
                        $parent =& $refer[$parentId];
                        $parent['current'][]=$list[$key][$pk];
                        if($parent[$pid]!=0){
                            $refer[$parent[$pid]]['current'][]=$list[$key][$pk];
                        }
                        $parent[$child][$data[$pk]] =& $list[$key];
                    }
                }
            }
        }
        return $tree;
    }

    /**
     * 操作日志
     */
    protected function adminlog(){
        $request = Request::instance();
        $adminlog=array();
        $adminlog['admin_id']=session('adminInfo')['id'];
        $adminlog['url']=$request->url();
        $data=array_merge($_GET,$_POST);
        $adminlog['create_time'] = time();
        $adminlog['data']=empty($data)?'':json_encode($data);
        if (!empty($data)){
            db('admin_log')->insert($adminlog);
        }
    }
}