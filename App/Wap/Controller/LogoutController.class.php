<?php
namespace Wap\Controller;
class LogoutController extends BaseController{

    //退出
    public function logout(){
        session('SG_User',null);
        echo '<script>window.location.href = "'.U('Login/login').'";</script>';die;
    }

}