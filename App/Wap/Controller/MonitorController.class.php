<?php
namespace Wap\Controller;
//生产监控
class MonitorController extends BaseController{

    public function websocket(){
        $get = I('get.');
        if(isset($get['db_config_index'])){
            session('SG_User.DB',$get['db_config_index']);
            $db_config_index = (int)$get['db_config_index'];
        }else{
            $db_config_index = (int)session('SG_User.DB')?(int)session('SG_User.DB'):0;
        }
        $this->assign([
            'LayoutTitle' => '生产监控（'.$this->DB_CONFIG[$db_config_index]['DB_FLAG'].'）',
            'db_config_index' => $db_config_index,
            'DB_CONFIG' => jejuu($this->DB_CONFIG),
        ]);
        $this->display();
    }

    public function getSocketioDomain(){
        echo C('DB_CONFIG.'.$_GET['db_config_index'])['socketio']['domain'];
    }

    public function connect_error_api(){
        $db_config_index = (int)$_GET['db_config_index'];
        $socket = socket_create(AF_INET,SOCK_DGRAM,SOL_UDP);
        socket_set_option($socket,SOL_SOCKET,SO_RCVTIMEO,['sec' => 1,'usec' => 0]);
        socket_bind($socket,$this->DB_CONFIG[$db_config_index]['socket_bind']['address'],$this->DB_CONFIG[$db_config_index]['socket_bind']['port']);
        socket_recvfrom($socket,$buf,2048,0,$from,$port);
        socket_close($socket);
        $buf = unpack('C*',$buf);
        if($buf){
            system('RunBat'.$db_config_index.'.vbs',$return_var);
            echo jejuu(['msg' => '监控服务开启'.($return_var?'失败':'成功'),'time' => date('Y-m-d H:i:s',time())]);
        }else{
            echo jejuu(['msg' => '数据接收失败','time' => date('Y-m-d H:i:s',time())]);
        }
    }

}
