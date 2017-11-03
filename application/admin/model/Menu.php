<?php
namespace app\admin\model;

use think\Model;
use think\Cache;

class Menu extends Model{

    public function index(){
        $list = $this->select();
        return $list;
    }

    public function del($id){
        return $this->where('id',$id)->delete();
    }

    /**
     * 更新缓存
     * @param  $data
     * @return array
     */
    public function menuCache($data = null)
    {
        if (empty($data)) {
            $data = $this->order("list_order", "ASC")->column('');
            Cache::set('Menu', $data, 0);
        } else {
            Cache::set('Menu', $data, 0);
        }
        return $data;
    }

    //获取主菜单权限列表
    public function getMenuList($roleid){
        $where=array(
            'm.status' => 1,
            'm.ismenu'=>1,
            'a.role_id'=>$roleid
        );
        $menuList=db('access')->alias('a')->join('menu m','m.url = a.rule_name')->where($where)->order('m.list_order desc,m.id desc')->select();
        return $menuList;
    }
}