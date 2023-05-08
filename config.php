<?php
//请用编辑器配置
return [
    //生产线配置（最多支持10条生产线）
    'DB_CONFIG' => [
        //这是单独1条生产线的配置,需要配置多条生产线请复制粘贴修改！
        [
            'DB_FLAG' => '老单', //数据库标识（生产线名称）
            'DB_TYPE' => 'sqlsrv', //数据库类型（支持2005,2008）
            'DB_HOST' => '.\sqlserver', //服务器地址
            'DB_USER' => 'sa', //用户名
            'DB_PWD' => 'sakey', //密码
            'DB_NAME' => 'sgolddan', //数据库名
            'isnew' => 0, //老生管0 新生管1
            'updown' => 0, //单刀0 上下刀1
            'paperCodeNumber' => 1, //纸质代码占几个字符
            'paperCodeSpaceChar' => '-',
            'socketio' => [
                'port' => 8888,//这个端口号好像可以随意配置（不同线不一样）
                'timer' => 1,//php中间层接受udp的时间间隔（单位：秒，决定了前端页面显示的数据每隔多少秒刷新1次）
                'domain' => 'http://test.leaper.ltd:8888',
            ],
            'socket_bind' => [
                'address' => '192.168.1.211',//安装php环境的电脑内网ip
                'port' => 3001,//“飞机”配置的端口号 有可能要+1（不同线不一样）
                'flag' => 'Line1:',//标识符
            ],
        ],
        //这是单独1条生产线的配置,需要配置多条生产线请复制粘贴修改！
        [
            'DB_FLAG' => '老双', //数据库标识（生产线名称）
            'DB_TYPE' => 'sqlsrv', //数据库类型（支持2005,2008）
            'DB_HOST' => '.\sqlserver', //服务器地址
            'DB_USER' => 'sa', //用户名
            'DB_PWD' => 'sakey', //密码
            'DB_NAME' => 'sgoldshuang', //数据库名
            'isnew' => 0, //老生管0 新生管1
            'updown' => 1, //单刀0 上下刀1
            'paperCodeNumber' => 1, //纸质代码占几个字符
            'paperCodeSpaceChar' => '-',
            'socketio' => [
                'port' => 8888,//这个端口号好像可以随意配置（不同线不一样）
                'timer' => 1,//php中间层接受udp的时间间隔（单位：秒，决定了前端页面显示的数据每隔多少秒刷新1次）
                'domain' => 'http://test.leaper.ltd:8888',
            ],
            'socket_bind' => [
                'address' => '192.168.1.211',//安装php环境的电脑内网ip
                'port' => 3001,//“飞机”配置的端口号 有可能要+1（不同线不一样）
                'flag' => 'Line1:',//标识符
            ],
        ],
        //这是单独1条生产线的配置,需要配置多条生产线请复制粘贴修改！
        [
            'DB_FLAG' => '新', //数据库标识（生产线名称）
            'DB_TYPE' => 'sqlsrv', //数据库类型（支持2005,2008）
            'DB_HOST' => '.\sqlserver', //服务器地址
            'DB_USER' => 'sa', //用户名
            'DB_PWD' => 'sakey',//密码
            'DB_NAME' => 'sgnew', //数据库名
            'isnew' => 1, //老生管0 新生管1
            'updown' => 0, //单刀0 上下刀1
            'paperCodeNumber' => 1, //纸质代码占几个字符
            'paperCodeSpaceChar' => '-',
            'socketio' => [
                'port' => 8888,//这个端口号好像可以随意配置（不同线不一样）
                'timer' => 1,//php中间层接受udp的时间间隔（单位：秒，决定了前端页面显示的数据每隔多少秒刷新1次）
                'domain' => 'http://test.leaper.ltd:8888',
            ],
            'socket_bind' => [
                'address' => '192.168.1.211',//安装php环境的电脑内网ip
                'port' => 3001,//“飞机”配置的端口号 有可能要+1（不同线不一样）
                'flag' => 'Line1:',//标识符
            ],
        ],
    ],
    //厂商名称
    'FactoryName' => '测试',
    //显示客户名称，可使用修改功能
    'SG_User0' => 'karry/karry',
    //显示客户名称，不可使用修改功能
    'SG_User1' => 'good/good',
    //不显示客户名称，不可使用修改功能
    'SG_User2' => 'ok/ok',
];
