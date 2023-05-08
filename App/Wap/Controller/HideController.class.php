<?php
namespace Wap\Controller;
use Think\Controller;
//隐藏功能
class HideController extends Controller{

    var $DB_CONFIG;

    public function __construct(){
        parent::__construct();
        $this->DB_CONFIG = C('DB_CONFIG');
        foreach($this->DB_CONFIG as $k => $v){
            unset($this->DB_CONFIG[$k]['DB_TYPE'],$this->DB_CONFIG[$k]['DB_HOST'],$this->DB_CONFIG[$k]['DB_USER'],$this->DB_CONFIG[$k]['DB_PWD'],$this->DB_CONFIG[$k]['DB_NAME']);
        }
    }

    //探测接口
    public function detect_api(){
        $get = I('get.');
        $r = [];
        foreach($this->DB_CONFIG as $v){
            $socket = socket_create(AF_INET,SOCK_DGRAM,SOL_UDP);
            socket_set_option($socket,SOL_SOCKET,SO_RCVTIMEO,['sec' => 1,'usec' => 0]);
            socket_bind($socket,$v['socket_bind']['address'],$v['socket_bind']['port']);
            socket_recvfrom($socket,$buf,2048,0,$from,$port);
            socket_close($socket);
            $buf = unpack('C*',$buf);
            $r[] = [
                'flag' => $v['DB_FLAG'],
                'status' => $buf?1:0,
            ];
        }
//        foreach($this->DB_CONFIG as $v){
//            $socket1 = socket_create(AF_INET,SOCK_DGRAM,SOL_UDP);
//            socket_set_option($socket1,SOL_SOCKET,SO_RCVTIMEO,['sec' => 1,'usec' => 0]);
//            socket_bind($socket1,$v['socket_bind']['address'],$v['socket_bind']['port']);
//            socket_recvfrom($socket1,$buf1,2048,0,$from,$port);
//            socket_close($socket1);
//            $buf1 = unpack('C*',$buf1);
//            if($buf1){
//                $r[] = [
//                    'flag' => $v['DB_FLAG'],
//                    'status' => 1,
//                ];
//            }else{
//                $socket2 = socket_create(AF_INET,SOCK_DGRAM,SOL_UDP);
//                socket_set_option($socket2,SOL_SOCKET,SO_RCVTIMEO,['sec' => 1,'usec' => 0]);
//                socket_bind($socket2,$v['socket_bind']['address'],$v['socket_bind']['port']);
//                socket_recvfrom($socket2,$buf2,2048,0,$from,$port);
//                socket_close($socket2);
//                $buf2 = unpack('C*',$buf2);
//                if($buf2){
//                    $r[] = [
//                        'flag' => $v['DB_FLAG'],
//                        'status' => 1,
//                    ];
//                }else{
//                    $socket3 = socket_create(AF_INET,SOCK_DGRAM,SOL_UDP);
//                    socket_set_option($socket3,SOL_SOCKET,SO_RCVTIMEO,['sec' => 1,'usec' => 0]);
//                    socket_bind($socket3,$v['socket_bind']['address'],$v['socket_bind']['port']);
//                    socket_recvfrom($socket3,$buf3,2048,0,$from,$port);
//                    socket_close($socket3);
//                    $buf3 = unpack('C*',$buf3);
//                    $r[] = [
//                        'flag' => $v['DB_FLAG'],
//                        'status' => $buf3?1:0,
//                    ];
//                }
//            }
//        }
        echo callback_jejuu($r,$get);
    }

    //调试
    public function debug(){
        $data = D('Func')->analyUDP($this->DB_CONFIG[0]);
        dump($data);
    }

}
