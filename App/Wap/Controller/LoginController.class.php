<?php
namespace Wap\Controller;
use Think\Controller;
class LoginController extends Controller{

    //登录
    public function login(){
        //已登录则自动跳转
        if(session('SG_User')){
            $this->redirect('Monitor/websocket');die;
        }
        $this->display();
    }

    //登录接口
    public function login_api(){
        $get = I('get.');
        if(D('Func')->check_code($get['code'])){
            $user = $get['username'].'/'.$get['password'];
            if($user === C('SG_User0') || $user === C('SG_User1') || $user === C('SG_User2')){
                if($user === C('SG_User0')){
                    $root = 0;
                }elseif($user === C('SG_User1')){
                    $root = 1;
                }else{
                    $root = 2;
                }
                session('SG_User', ['user' => $user,'root' => $root]);
                //判断session中是否设置了要返回的地址
                if(session('SG_ReturnUrl')){
                    $patch = ['SG_ReturnUrl' => session('SG_ReturnUrl')];
                    session('SG_ReturnUrl',null);
                }else{
                    $patch = [];
                }
                echo jejuu(array_merge([
                    'ret' => C('succ_ret'),
                    'msg' => '登录成功',
                ],$patch));
            }else{
                echo jejuu([
                    'ret' => C('fail_ret'),
                    'msg' => '登录失败：无效账号或密码',
                ]);
            }
        }else{
            echo jejuu([
                'ret' => C('fail_ret'),
                'msg' => '登录失败：无效验证码',
            ]);
        }
    }

    //生成验证码
    public function make_code(){
    	ob_clean();
        $verify = new \Think\Verify([
            'fontSize'    =>    25,
            'length'      =>    3,
            'codeSet'      =>   '0123456789',
            'useNoise'    =>    false,
            'useCurve'   =>     false,
            'useImgBg'   =>     true,
        ]);
        $verify->entry();
    }

}
