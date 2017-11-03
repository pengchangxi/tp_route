<?php
namespace app\admin\model;

use think\Model;

class Article extends Model{

    public function index(){
       return $this->paginate();
    }
}