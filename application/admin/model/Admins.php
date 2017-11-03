<?php
namespace app\admin\model;

use think\Model;

class Admins extends Model{

    public function role(){
        return $this->belongsTo('Role', 'role_id', 'id')->field('name,id');
    }

    public function index($where){
       $list = self::with('role')->order('id desc')->where($where)->paginate(15, false, [
           'query' => request()->param(),]);
       return $list;
    }

    //根据添加查询管理员信息
    public function find($where){
        return $this->where($where)->find();
    }

    /**
     * 根据ID修改管理员信息
     * @param $id
     * @param $data
     * @return $this
     */
    public function updateAdmin($id,$data){
        return $this->where('id',$id)->update($data);
    }

    /**
     * 根据ID删除管理员信息
     * @param $id
     * @return int
     */
    public function del($id){
        return $this->where('id',$id)->delete();
    }


}