<?php
namespace app\admin\model;

use think\Model;

class Role extends Model{

    public function index($where){
       return $this->order('id desc')->where($where)->paginate(15, false, [
           'query' => request()->param(),]);
    }

    public function del($id){
      return $this->where('id',$id)->delete();
    }
}