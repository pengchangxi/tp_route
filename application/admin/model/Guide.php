<?php
namespace app\admin\model;

use think\Model;
use traits\model\SoftDelete;

class Guide extends Model{

    use SoftDelete;


    public function index(){
        $list = $this->paginate();
        return $list;
    }

    //软删除
    public function del($id){
      return $this->destroy($id);
    }
}