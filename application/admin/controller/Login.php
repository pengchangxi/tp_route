<?php
namespace app\admin\controller;

use app\admin\model\Admins;
use think\Controller;
use think\Request;
use think\Session;

class Login extends Controller{

    public function index(){
        return $this->fetch('/login');
    }

    //登录
    public function login(){
        $account = input('post.account');
        $password = input('post.password');
        $remember = input('post.remember');
        $where['account'] = $account;
        $where['status'] = 1;
        $admins = new Admins();
        $adminInfo = $admins->find($where);
        if (!$adminInfo){
            $this->error('账号不存在或被禁用');
        }else{
            if (md5($password.$adminInfo['salt'])!=$adminInfo['password']){
                $this->error('密码错误');
            }else{
                session('adminInfo',$adminInfo);
                $request = Request::instance();
                $data = array(
                    'error_num'=>0,
                    'login_time' => time(),
                    'login_ip' => $request->ip()
                );
                if($remember=='on'){//保存cookie
                    cookie('account',$account);
                    cookie('password',$password);
                }else{//清除cookie
                    cookie('account', null);
                    cookie('password',null);
                }
                $admins->updateAdmin($adminInfo['id'],$data);
                $this->success('登录成功！','/admin/index/index');
            }
        }
    }

    //退出
    public function logout() {
        if(Session::has('adminInfo')) {
            Session::delete('adminInfo');//清除session
            Session::destroy();
            if(isset($_GET['isajax'])){
                $this->success('超时退出！','/admin/login/index');
            }
            $this->success('退出成功！','/admin/login/index');
        }else {
            $this->success('已经退出！','/admin/login/index');
        }
    }
}