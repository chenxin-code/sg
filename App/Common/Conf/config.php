<?php
return array_merge([
    'URL_PATHINFO_DEPR' => '/',//配置URL生成的分隔符，对SEO更好
    'URL_HTML_SUFFIX' => '',//关闭URL伪静态功能   开发阶段最好使用此行代码  上线后可将此行代码注释掉
    'URL_CASE_INSENSITIVE' => true,//实现URL访问不区分大小写
    'URL_MODEL' => 2,//实现U()函数能自动去掉index.php
    'DEFAULT_FILTER' => 'trim,htmlspecialchars',//默认参数过滤方法 用于I()函数

    //规范起见，默认的数据库驱动类设置了字段名强制转换为小写
    //如果你的数据表字段名采用大小写混合方式的话，需要在配置文件中增加如下配置
    'DB_PARAMS' => [\PDO::ATTR_CASE => \PDO::CASE_NATURAL],

    'DEFAULT_MODULE'        =>  'Wap',  // 默认模块
    'DEFAULT_CONTROLLER'    =>  'Login', // 默认控制器名称
    'DEFAULT_ACTION'        =>  'login', // 默认操作名称

    'MODULE_DENY_LIST'      =>  ['Runtime'],

    'LAYOUT_ON'             =>  true, // 是否启用布局
    'LAYOUT_NAME'           =>  'Wap/View/layout', // 当前布局名称
    'TMPL_LAYOUT_ITEM' => '{__LAYOUT_CONTENT__}',//layout模板布局的内容替换标识【原来TP默认为{__CONTENT__}】

    'TMPL_ACTION_ERROR' => './res/error.html',

    'SESSION_OPTIONS'      =>  ['expire' => 86400 * 365], // session 过期时间

    'succ_ret' => '1',//(string)666,
    'fail_ret' => '0',//(string)rand(667,999),

    'TMPL_PARSE_STRING' => ['__RES__' => __ROOT__.'/res'],

    //最新阿里图标cdn
    'ali_iconfont_cdn' => '//at.alicdn.com/t/font_473504_0b29ohydgtyq.css',

],include('./config.php'));
