<?php
namespace Wap\Model;
//函数库
class FuncModel{

    //解析UDP数据包
    public function analyUDP($DB){
        //“UDP通讯设置超时时间”链接：http://www.cnblogs.com/yuanlipu/p/7092667.html
        $socket = socket_create(AF_INET,SOCK_DGRAM,SOL_UDP);
        socket_set_option($socket,SOL_SOCKET,SO_RCVTIMEO,['sec' => 1,'usec' => 0]);
        socket_bind($socket,$DB['socket_bind']['address'],$DB['socket_bind']['port']);
        //$from = '';$port = 0;
        socket_recvfrom($socket,$buf,2048,0,$from,$port);
        socket_close($socket);
        $buf = unpack('C*',$buf);
        //dump($buf);die;
        $this_flag = '';
        for($i = 1;$i <= strlen($DB['socket_bind']['flag']);$i ++){
            $this_flag .= chr($buf[$i]);
        }
        if($this_flag === $DB['socket_bind']['flag']){
            if($DB['isnew']){
                if($DB['updown']){
                    return [
                        'ret' => C('succ_ret'),
                        'data' => [],
                    ];
                }else{
                    return [
                        'ret' => C('succ_ret'),
                        'data' => [
                            'class' => chr($buf[709]),
                            'qds' => byte_to_int($buf,53),
                            'scds' => byte_to_int($buf,17),
                            'syds' => byte_to_int($buf,21),
                            'ddsy' => round(byte_to_int($buf,33)/1000,0),
                            'blds' => byte_to_int($buf,25),
                            'ddc' => round(byte_to_int($buf,61)/1000,0),
                            'qc' => byte_to_int($buf,57),
                            'cs' => byte_to_int($buf,133),
                            'benban' => [
                                'zms' => byte_to_int($buf,149),
                                'sc' => byte_to_int($buf,153),
                                'sy' => byte_to_int($buf,157),
                                'bl' => byte_to_int($buf,161),
                                'js' => byte_to_int($buf,189)/10,
                                'scpf' => byte_to_int($buf,165),
                                'scsj' => byte_to_int($buf,193),
                                'tcsj' => byte_to_int($buf,197),
                                'tccs' => byte_to_int($buf,201),
                                'scjpf' => byte_to_int($buf,169),
                                'hzl' => byte_to_int($buf,181)/100,
                                'xbl' => byte_to_int($buf,185)/100,
                            ],
                            'benbi' => [
                                'zms' => byte_to_int($buf,205),
                                'sc' => byte_to_int($buf,209),
                                'sy' => byte_to_int($buf,213),
                                'bl' => byte_to_int($buf,217),
                                'js' => byte_to_int($buf,245)/10,
                                'scpf' => byte_to_int($buf,221),
                                'scsj' => byte_to_int($buf,249),
                                'tcsj' => byte_to_int($buf,253),
                                'tccs' => byte_to_int($buf,257),
                                'scjpf' => byte_to_int($buf,225),
                                'hzl' => byte_to_int($buf,237)/100,
                                'xbl' => byte_to_int($buf,241)/100,
                            ],
                            'huji' => [
                                'cs' => byte_to_int($buf,265),
                                'sy' => round(byte_to_int($buf,285)/1000,0) ,
                                'lj' => round(byte_to_int($buf,289)/1000,0),
                            ],
                            'SF1' => [
                                'cs' => byte_to_int($buf,393),
                                'sy' => round(byte_to_int($buf,413)/1000,0),
                                'lj' => round(byte_to_int($buf,417)/1000,0),
                            ],
                            'SF2' => [
                                'cs' => byte_to_int($buf,521),
                                'sy' => round(byte_to_int($buf,541)/1000,0),
                                'lj' => round(byte_to_int($buf,545)/1000,0),
                            ],
                            'SF3' => [
                                'cs' => byte_to_int($buf,649),
                                'sy' => round(byte_to_int($buf,669)/1000,0),
                                'lj' => round(byte_to_int($buf,673)/1000,0),
                            ],
                        ],
                    ];
                }
            }else{
                if($DB['updown']){
                    return [
                        'ret' => C('succ_ret'),
                        'data' => [
                            'class' => chr($buf[strlen($DB['socket_bind']['flag']) + 1]),
                            'qds1' => byte_to_int($buf,31),
                            'scds1' => byte_to_int($buf,35),
                            'syds1' => byte_to_int($buf,39),
                            'ddsy1' => byte_to_int($buf,43),
                            'blds1' => byte_to_int($buf,47),
                            'ddc1' => byte_to_int($buf,51),
                            'qc1' => byte_to_int($buf,55),
                            'qds2' => byte_to_int($buf,59),
                            'scds2' => byte_to_int($buf,63),
                            'syds2' => byte_to_int($buf,67),
                            'ddsy2' => byte_to_int($buf,71),
                            'blds2' => byte_to_int($buf,75),
                            'ddc2' => byte_to_int($buf,79),
                            'qc2' => byte_to_int($buf,83),
                            'xs' => byte_to_int($buf,87),
                            'benban' => [
                                'zms' => byte_to_int($buf,91),
                                'sc' => byte_to_int($buf,95),
                                'sy' => byte_to_int($buf,99),
                                'bl' => byte_to_int($buf,103),
                                'js' => byte_to_int($buf,107)/10,
                                'scpf' => byte_to_int($buf,111),
                                'scsj' => byte_to_int($buf,115),
                                'tcsj' => byte_to_int($buf,119),
                                'tccs' => byte_to_int($buf,123),
                                'scjpf' => byte_to_int($buf,127),
                                'hzl' => round(byte_to_int($buf,131)/10/byte_to_int($buf,111)*1000,1),
                                'xbl' => byte_to_int($buf,135)/10,
                            ],
                            'benbi' => [
                                'zms' => byte_to_int($buf,139),
                                'sc' => byte_to_int($buf,143),
                                'sy' => byte_to_int($buf,147),
                                'bl' => byte_to_int($buf,151),
                                'js' => byte_to_int($buf,155)/10,
                                'scpf' => byte_to_int($buf,159),
                                'scsj' => byte_to_int($buf,163),
                                'tcsj' => byte_to_int($buf,167),
                                'tccs' => byte_to_int($buf,171),
                                'scjpf' => byte_to_int($buf,175),
                                'hzl' => round(byte_to_int($buf,179)/10/byte_to_int($buf,159)*1000,1),
                                'xbl' => byte_to_int($buf,183)/10,
                            ],
                            'huji' => [
                                'cs' => byte_to_int($buf,187)/10,
                            ],
                            'SF1' => [
                                'cs' => byte_to_int($buf,307)/10,
                            ],
                            'SF2' => [
                                'cs' => byte_to_int($buf,427)/10,
                            ],
                            'SF3' => [
                                'cs' => byte_to_int($buf,547)/10,
                            ],
                        ],
                    ];
                }else{
                    return [
                        'ret' => C('succ_ret'),
                        'data' => [
                            'class' => chr($buf[strlen($DB['socket_bind']['flag']) + 1]),
                            'qds' => byte_to_int($buf,31),
                            'scds' => byte_to_int($buf,35),
                            'syds' => byte_to_int($buf,39),
                            'ddsy' => round(byte_to_int($buf,43)/1000,1),
                            'blds' => byte_to_int($buf,47),
                            'ddc' => byte_to_int($buf,51),
                            'qc' => byte_to_int($buf,55),
                            'cs' => round(byte_to_int($buf,59)/10,1),
                            'benban' => [
                                'zms' => byte_to_int($buf,63),
                                'sc' => byte_to_int($buf,67),
                                'sy' => byte_to_int($buf,71),
                                'bl' => byte_to_int($buf,75),
                                'js' => byte_to_int($buf,79)/10,
                                'scpf' => byte_to_int($buf,83),
                                'scsj' => byte_to_int($buf,87),
                                'tcsj' => byte_to_int($buf,91),
                                'tccs' => byte_to_int($buf,95),
                                'scjpf' => byte_to_int($buf,99),
                                'hzl' => round(byte_to_int($buf,103)/10/byte_to_int($buf,83)*1000,1),
                                'xbl' => byte_to_int($buf,107)/10,
                            ],
                            'benbi' => [
                                'zms' => byte_to_int($buf,111),
                                'sc' => byte_to_int($buf,115),
                                'sy' => byte_to_int($buf,119),
                                'bl' => byte_to_int($buf,123),
                                'js' => byte_to_int($buf,127)/10,
                                'scpf' => byte_to_int($buf,131),
                                'scsj' => byte_to_int($buf,135),
                                'tcsj' => byte_to_int($buf,139),
                                'tccs' => byte_to_int($buf,143),
                                'scjpf' => byte_to_int($buf,147),
                                'hzl' => round(byte_to_int($buf,151)/10/byte_to_int($buf,131)*1000,1),
                                'xbl' => byte_to_int($buf,155)/10,
                            ],
                            'huji' => [
                                'cs' => byte_to_int($buf,159)/10,
                            ],
                            'SF1' => [
                                'cs' => byte_to_int($buf,279)/10,
                            ],
                            'SF2' => [
                                'cs' => byte_to_int($buf,399)/10,
                            ],
                            'SF3' => [
                                'cs' => byte_to_int($buf,519)/10,
                            ],
                        ],
                    ];
                }
            }
        }else{
            return ['ret' => C('fail_ret')];
        }
    }

    //验证码检测
    public function check_code($code){
        $verify = new \Think\Verify();
        return $verify->check($code);
    }

}
