<?php
namespace Wap\Controller;
use Think\Controller;
class BaseController extends Controller{

    var $DB_CONFIG;

	public function __construct(){
		parent::__construct();
		if(!session('SG_User')){
            if(strpos(ACTION_NAME,'_api') === FALSE){
                //把要访问的地址保存到session中，这样登录成功之后会跳回来
                session('SG_ReturnUrl','http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']);
                $this->redirect('Login/login');
            }else{
                echo jejuu([
                    'ret' => C('fail_ret'),
                    'msg' => '未登录',
                ]);
            }
            die;
		}
        $this->DB_CONFIG = C('DB_CONFIG');
		foreach($this->DB_CONFIG as $k => $v){
		    unset($this->DB_CONFIG[$k]['DB_TYPE'],$this->DB_CONFIG[$k]['DB_HOST'],$this->DB_CONFIG[$k]['DB_USER'],$this->DB_CONFIG[$k]['DB_PWD'],$this->DB_CONFIG[$k]['DB_NAME']);
        }
	}

}
