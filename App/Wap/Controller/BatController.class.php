<?php
namespace Wap\Controller;
use Think\Controller;
use Workerman\Worker;
use Workerman\Lib\Timer;
use PHPSocketIO\SocketIO;
require './res/vendor/autoload.php';
// bat 执行生产监控
class BatController extends Controller{

    var $DB_CONFIG;
    var $SocketIO;

    public function __construct(){
        parent::__construct();
        $this->DB_CONFIG = C('DB_CONFIG');
        foreach($this->DB_CONFIG as $k => $v){
            unset($this->DB_CONFIG[$k]['DB_TYPE'],$this->DB_CONFIG[$k]['DB_HOST'],$this->DB_CONFIG[$k]['DB_USER'],$this->DB_CONFIG[$k]['DB_PWD'],$this->DB_CONFIG[$k]['DB_NAME']);
        }
    }

    //10个方法决定最多支持10条生产线
    public function ws0(){
        // PHPSocketIO服务
        $this->SocketIO = new SocketIO($this->DB_CONFIG[0]['socketio']['port']);
        // 启动后监听一个http端口
        $this->SocketIO->on('workerStart', function(){
            // 定时接收“飞机”发出的UDP广播数据包，将其解析，推送
            Timer::add($this->DB_CONFIG[0]['socketio']['timer'], function(){
                //解析
                $data = D('Func')->analyUDP($this->DB_CONFIG[0]);
                //dump($data);die;
                //推送
                $this->SocketIO->emit('AnalyUdpData0',jejuu($data));
            });
        });
        if(!defined('GLOBAL_START')){
            Worker::runAll();
        }
    }
    public function ws1(){
        // PHPSocketIO服务
        $this->SocketIO = new SocketIO($this->DB_CONFIG[1]['socketio']['port']);
        // 启动后监听一个http端口
        $this->SocketIO->on('workerStart', function(){
            // 定时接收“飞机”发出的UDP广播数据包，将其解析，推送
            Timer::add($this->DB_CONFIG[1]['socketio']['timer'], function(){
                //解析
                $data = D('Func')->analyUDP($this->DB_CONFIG[1]);
                //dump($data);die;
                //推送
                $this->SocketIO->emit('AnalyUdpData1',jejuu($data));
            });
        });
        if(!defined('GLOBAL_START')){
            Worker::runAll();
        }
    }
    public function ws2(){
        // PHPSocketIO服务
        $this->SocketIO = new SocketIO($this->DB_CONFIG[2]['socketio']['port']);
        // 启动后监听一个http端口
        $this->SocketIO->on('workerStart', function(){
            // 定时接收“飞机”发出的UDP广播数据包，将其解析，推送
            Timer::add($this->DB_CONFIG[2]['socketio']['timer'], function(){
                //解析
                $data = D('Func')->analyUDP($this->DB_CONFIG[2]);
                //dump($data);die;
                //推送
                $this->SocketIO->emit('AnalyUdpData2',jejuu($data));
            });
        });
        if(!defined('GLOBAL_START')){
            Worker::runAll();
        }
    }
    public function ws3(){
        // PHPSocketIO服务
        $this->SocketIO = new SocketIO($this->DB_CONFIG[3]['socketio']['port']);
        // 启动后监听一个http端口
        $this->SocketIO->on('workerStart', function(){
            // 定时接收“飞机”发出的UDP广播数据包，将其解析，推送
            Timer::add($this->DB_CONFIG[3]['socketio']['timer'], function(){
                //解析
                $data = D('Func')->analyUDP($this->DB_CONFIG[3]);
                //dump($data);die;
                //推送
                $this->SocketIO->emit('AnalyUdpData3',jejuu($data));
            });
        });
        if(!defined('GLOBAL_START')){
            Worker::runAll();
        }
    }
    public function ws4(){
        // PHPSocketIO服务
        $this->SocketIO = new SocketIO($this->DB_CONFIG[4]['socketio']['port']);
        // 启动后监听一个http端口
        $this->SocketIO->on('workerStart', function(){
            // 定时接收“飞机”发出的UDP广播数据包，将其解析，推送
            Timer::add($this->DB_CONFIG[4]['socketio']['timer'], function(){
                //解析
                $data = D('Func')->analyUDP($this->DB_CONFIG[4]);
                //dump($data);die;
                //推送
                $this->SocketIO->emit('AnalyUdpData4',jejuu($data));
            });
        });
        if(!defined('GLOBAL_START')){
            Worker::runAll();
        }
    }
    public function ws5(){
        // PHPSocketIO服务
        $this->SocketIO = new SocketIO($this->DB_CONFIG[5]['socketio']['port']);
        // 启动后监听一个http端口
        $this->SocketIO->on('workerStart', function(){
            // 定时接收“飞机”发出的UDP广播数据包，将其解析，推送
            Timer::add($this->DB_CONFIG[5]['socketio']['timer'], function(){
                //解析
                $data = D('Func')->analyUDP($this->DB_CONFIG[5]);
                //dump($data);die;
                //推送
                $this->SocketIO->emit('AnalyUdpData5',jejuu($data));
            });
        });
        if(!defined('GLOBAL_START')){
            Worker::runAll();
        }
    }
    public function ws6(){
        // PHPSocketIO服务
        $this->SocketIO = new SocketIO($this->DB_CONFIG[6]['socketio']['port']);
        // 启动后监听一个http端口
        $this->SocketIO->on('workerStart', function(){
            // 定时接收“飞机”发出的UDP广播数据包，将其解析，推送
            Timer::add($this->DB_CONFIG[6]['socketio']['timer'], function(){
                //解析
                $data = D('Func')->analyUDP($this->DB_CONFIG[6]);
                //dump($data);die;
                //推送
                $this->SocketIO->emit('AnalyUdpData6',jejuu($data));
            });
        });
        if(!defined('GLOBAL_START')){
            Worker::runAll();
        }
    }
    public function ws7(){
        // PHPSocketIO服务
        $this->SocketIO = new SocketIO($this->DB_CONFIG[7]['socketio']['port']);
        // 启动后监听一个http端口
        $this->SocketIO->on('workerStart', function(){
            // 定时接收“飞机”发出的UDP广播数据包，将其解析，推送
            Timer::add($this->DB_CONFIG[7]['socketio']['timer'], function(){
                //解析
                $data = D('Func')->analyUDP($this->DB_CONFIG[7]);
                //dump($data);die;
                //推送
                $this->SocketIO->emit('AnalyUdpData7',jejuu($data));
            });
        });
        if(!defined('GLOBAL_START')){
            Worker::runAll();
        }
    }
    public function ws8(){
        // PHPSocketIO服务
        $this->SocketIO = new SocketIO($this->DB_CONFIG[8]['socketio']['port']);
        // 启动后监听一个http端口
        $this->SocketIO->on('workerStart', function(){
            // 定时接收“飞机”发出的UDP广播数据包，将其解析，推送
            Timer::add($this->DB_CONFIG[8]['socketio']['timer'], function(){
                //解析
                $data = D('Func')->analyUDP($this->DB_CONFIG[8]);
                //dump($data);die;
                //推送
                $this->SocketIO->emit('AnalyUdpData8',jejuu($data));
            });
        });
        if(!defined('GLOBAL_START')){
            Worker::runAll();
        }
    }
    public function ws9(){
        // PHPSocketIO服务
        $this->SocketIO = new SocketIO($this->DB_CONFIG[9]['socketio']['port']);
        // 启动后监听一个http端口
        $this->SocketIO->on('workerStart', function(){
            // 定时接收“飞机”发出的UDP广播数据包，将其解析，推送
            Timer::add($this->DB_CONFIG[9]['socketio']['timer'], function(){
                //解析
                $data = D('Func')->analyUDP($this->DB_CONFIG[9]);
                //dump($data);die;
                //推送
                $this->SocketIO->emit('AnalyUdpData9',jejuu($data));
            });
        });
        if(!defined('GLOBAL_START')){
            Worker::runAll();
        }
    }

}
