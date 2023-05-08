<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1.0,user-scalable=no">
    <meta name="format-detection" content="telephone=no">
    <link href="/sg/res/logo.png" rel="shortcut icon">
    <title><?php echo ($LayoutTitle); ?></title>
    <script src="/sg/res/vue.js"></script>
    <!-- jQuery -->
    <script src="/sg/res/jquery.min.js"></script>
    <!-- jQuery WeUI -->
    <link rel="stylesheet" href="/sg/res/jqweui/css/weui.min.css">
    <link rel="stylesheet" href="/sg/res/jqweui/css/jquery-weui.min.css">
    <script src="/sg/res/jqweui/js/jquery-weui.min.js"></script>
    <!-- ElementUI -->
    <link rel="stylesheet" href="/sg/res/ElementUI/index.css">
    <script src="/sg/res/ElementUI/index.js"></script>
    <!-- mint-ui -->
    <link rel="stylesheet" href="/sg/res/mint-ui/style.css">
    <script src="/sg/res/mint-ui/index.js"></script>
    <!-- 阿里图标cdn -->
    <link rel="stylesheet" href="<?php echo C('ali_iconfont_cdn');?>">
    <!-- common 样式 -->
    <link rel="stylesheet" href="/sg/res/common.css?time=<?php echo time();?>">
    <!-- 函数库 -->
    <script src="/sg/res/function.js?time=<?php echo time();?>"></script>
</head>
<body>
<script src="/sg/res/socket.io.js"></script>
<!-- 引入highcharts插件 -->
<script src="/sg/res/highcharts/highcharts.js"></script>
<script src="/sg/res/highcharts/highcharts-3d.js"></script>
<script src="/sg/res/highcharts/exporting.js"></script>
<script src="/sg/res/highcharts/highcharts-zh_CN.js"></script>
<script src="/sg/res/highcharts/offline-exporting.js"></script>

<style>
    table {
        width: 100%;
        border-collapse: collapse;
        table-layout: fixed;
    }
    table td {
        font-size: 13px;
        border: 1px solid #e0e0e0;
        border-top: none;
        text-align: center;
    }
    table td button {
        color: #3598dc;
        font-size: 16px;
        border: none;
        background: none;
        cursor: pointer;
    }
    /*****************************************************/
    .ShowChartsClass {
        background-color: #3598dc !important;
        color: #fff !important;
        border: none;
        border-radius: 10px;
        padding: 0 3px;
    }
    /*****************************************************/
    .msg-box {
        margin: 0 3px;
        padding: 8px 0;
        height: 40px;
        border-bottom: 1px solid #e0e0e0;
        overflow: hidden;
        position: relative;
    }
    .msg-box .left {
        padding: 0 12px;
        font-size: 14px;
        line-height: 40px;
        border-right: 1px solid #e0e0e0;
        float: left;
    }
    .msg-box .left .iconfont {
        font-size: 18px;
    }
    .msg-box .mid {
        padding: 0 15px;
        line-height: 40px;
        font-size: 13px;
        text-align: center;
        white-space: nowrap;
        overflow: auto;
    }
    .msg-box .right {
        padding: 0 5px;
        height: 40px;
        width: 40px;
        border-left: 1px solid #e0e0e0;
        cursor: pointer;
        float: right;
        position: relative;
    }
    .msg-box .right::before {
        content: '';
        height: 9px;
        width: 9px;
        border-width: 1.8px 1.8px 0 0;
        border-style: solid;
        border-color: #c8c8cd;
        transform: matrix(0.71, 0.71, -0.71, 0.71, 0, 0);
        margin: -5px -4px 0 0;
        position: absolute;
        top: 50%;
        right: 50%;
    }
</style>

<div id="VueBox">
    <div class="sg-header">
        <div @click="ChangeDBSelect = true" class="filter">
            <span :style="{'color':ChangeDBSelect?'#3598dc':'#3d4245'}">{{DB_CONFIG[db_config_index].DB_FLAG}}</span>
            <i class="iconfont icon-xialajiantou"></i>
        </div>
    </div>
    <div class="drop-select-box">
        <div @click="ChangeDBSelect = false" @touchmove.prevent class="shadow" :style="{'display':ChangeDBSelect?'block':'none'}"></div>
        <div class="select" :style="{'display':ChangeDBSelect?'block':'none'}">
            <div v-for="(v,k) in DB_CONFIG" @click="ChangeDB(k)" class="option" :class="{'selected-blue':k === db_config_index}">
                {{v.DB_FLAG}}
                <i class="iconfont icon-xuanzhong"></i>
            </div>
        </div>
    </div>
    <div v-if="Number(DB_CONFIG[db_config_index].updown)">
        <div class="pane-box" style="margin-top: 40px;">
            <div class="item">
                <p class="up">切刀数</p>
                <button @click="BuildCharts('切刀数（上刀）','刀数','qds1',2)">{{UdpData_updown1.qds1}}</button>
                <div class="line"></div>
                <button @click="BuildCharts('切刀数（下刀）','刀数','qds2',2)">{{UdpData_updown1.qds2}}</button>
            </div>
            <div class="item">
                <p class="up">生产刀数</p>
                <button @click="BuildCharts('生产刀数（上刀）','刀数','scds1',2)">{{UdpData_updown1.scds1}}</button>
                <div class="line"></div>
                <button @click="BuildCharts('生产刀数（下刀）','刀数','scds2',2)">{{UdpData_updown1.scds2}}</button>
            </div>
            <div class="item">
                <p class="up">剩余刀数</p>
                <button @click="BuildCharts('剩余刀数（上刀）','刀数','syds1',2)">{{UdpData_updown1.syds1}}</button>
                <div class="line"></div>
                <button @click="BuildCharts('剩余刀数（下刀）','刀数','syds2',2)">{{UdpData_updown1.syds2}}</button>
            </div>
            <div class="item">
                <p class="up">不良刀数</p>
                <button @click="BuildCharts('不良刀数（上刀）','刀数','blds1',2)">{{UdpData_updown1.blds1}}</button>
                <div class="line"></div>
                <button @click="BuildCharts('不良刀数（下刀）','刀数','blds2',2)">{{UdpData_updown1.blds2}}</button>
            </div>
        </div>
        <!--<div class="pane-box">
            <div class="item">
                <p class="up">订单剩余(m)</p>
                <button @click="BuildCharts('订单剩余（上刀）','米(m)','ddsy1',2)">{{UdpData_updown1.ddsy1}}</button>
                <div class="line"></div>
                <button @click="BuildCharts('订单剩余（下刀）','米(m)','ddsy2',2)">{{UdpData_updown1.ddsy2}}</button>
            </div>
            <div class="item">
                <p class="up">订单长(m)</p>
                <button @click="BuildCharts('订单长（上刀）','米(m)','ddc1',2)">{{UdpData_updown1.ddc1}}</button>
                <div class="line"></div>
                <button @click="BuildCharts('订单长（下刀）','米(m)','ddc2',2)">{{UdpData_updown1.ddc2}}</button>
            </div>
            <div class="item">
                <p class="up">切长(mm)</p>
                <button @click="BuildCharts('切长（上刀）','毫米(mm)','qc1',2)">{{UdpData_updown1.qc1}}</button>
                <div class="line"></div>
                <button @click="BuildCharts('切长（下刀）','毫米(mm)','qc2',2)">{{UdpData_updown1.qc2}}</button>
            </div>
            <div class="item">
                <p class="up">限速(m/s)</p>
                <button @click="BuildCharts('限速','米/秒(m/s)','xs',2)">{{UdpData_updown1.xs}}</button>
            </div>
        </div>-->
        <table>
            <tr>
                <td>坑机</td>
                <td>糊机</td>
                <td>SF1</td>
                <td>SF2</td>
                <td>SF3</td>
            </tr>
            <tr>
                <td>车速</td>
                <td><button @click="BuildCharts('糊机/车速','','huji_cs',2)">{{UdpData_updown1.huji['cs']}}</button></td>
                <td><button @click="BuildCharts('SF1/车速','','SF1_cs',2)">{{UdpData_updown1.SF1['cs']}}</button></td>
                <td><button @click="BuildCharts('SF2/车速','','SF2_cs',2)">{{UdpData_updown1.SF2['cs']}}</button></td>
                <td><button @click="BuildCharts('SF3/车速','','SF3_cs',2)">{{UdpData_updown1.SF3['cs']}}</button></td>
            </tr>
        </table>
        <table>
            <tr>
                <td>订单</td>
                <td>{{UdpData_updown1.class}}班</td>
                <td>本笔</td>
            </tr>
            <tr>
                <td>总米数(m)</td>
                <td><button @click="BuildCharts('？班/总米数','米(m)','benban_zms',2)">{{UdpData_updown1.benban['zms']}}</button></td>
                <td><button @click="BuildCharts('本笔/总米数','米(m)','benbi_zms',2)">{{UdpData_updown1.benbi['zms']}}</button></td>
            </tr>
            <tr>
                <td>生产(m)</td>
                <td><button @click="BuildCharts('？班/生产','米(m)','benban_sc',2)">{{UdpData_updown1.benban['sc']}}</button></td>
                <td><button @click="BuildCharts('本笔/生产','米(m)','benbi_sc',2)">{{UdpData_updown1.benbi['sc']}}</button></td>
            </tr>
            <tr>
                <td>剩余(m)</td>
                <td><button @click="BuildCharts('？班/剩余','米(m)','benban_sy',2)">{{UdpData_updown1.benban['sy']}}</button></td>
                <td><button @click="BuildCharts('本笔/剩余','米(m)','benbi_sy',2)">{{UdpData_updown1.benbi['sy']}}</button></td>
            </tr>
            <tr>
                <td>不良(m)</td>
                <td><button @click="BuildCharts('？班/不良','米(m)','benban_bl',2)">{{UdpData_updown1.benban['bl']}}</button></td>
                <td><button @click="BuildCharts('本笔/不良','米(m)','benbi_bl',2)">{{UdpData_updown1.benbi['bl']}}</button></td>
            </tr>
            <tr>
                <td>均速(m/min)</td>
                <td><button @click="BuildCharts('？班/均速','米/分(m/min)','benban_js',2)">{{UdpData_updown1.benban['js']}}</button></td>
                <td><button @click="BuildCharts('本笔/均速','米/分(m/min)','benbi_js',2)">{{UdpData_updown1.benbi['js']}}</button></td>
            </tr>
            <tr>
                <td>生产平方(㎡)</td>
                <td><button @click="BuildCharts('？班/生产平方','平方米(㎡)','benban_scpf',2)">{{UdpData_updown1.benban['scpf']}}</button></td>
                <td><button @click="BuildCharts('本笔/生产平方','平方米(㎡)','benbi_scpf',2)">{{UdpData_updown1.benbi['scpf']}}</button></td>
            </tr>
            <tr>
                <td>停车次数</td>
                <td><button @click="BuildCharts('？班/停车次数','次数','benban_tccs',2)">{{UdpData_updown1.benban['tccs']}}</button></td>
                <td><button @click="BuildCharts('本笔/停车次数','次数','benbi_tccs',2)">{{UdpData_updown1.benbi['tccs']}}</button></td>
            </tr>
            <!--<tr>
                <td>生产净平方(㎡)</td>
                <td><button @click="BuildCharts('？班/生产净平方','平方米(㎡)','benban_scjpf',2)">{{UdpData_updown1.benban['scjpf']}}</button></td>
                <td><button @click="BuildCharts('本笔/生产净平方','平方米(㎡)','benbi_scjpf',2)">{{UdpData_updown1.benbi['scjpf']}}</button></td>
            </tr>-->
            <tr>
                <td>坏纸率(%)</td>
                <td><button @click="BuildCharts('？班/坏纸率','%','benban_hzl',2)">{{UdpData_updown1.benban['hzl']}}</button></td>
                <td><button @click="BuildCharts('本笔/坏纸率','%','benbi_hzl',2)">{{UdpData_updown1.benbi['hzl']}}</button></td>
            </tr>
            <tr>
                <td>修边率(%)</td>
                <td><button @click="BuildCharts('？班/修边率','%','benban_xbl',2)">{{UdpData_updown1.benban['xbl']}}</button></td>
                <td><button @click="BuildCharts('本笔/修边率','%','benbi_xbl',2)">{{UdpData_updown1.benbi['xbl']}}</button></td>
            </tr>
            <tr>
                <td>生产时间</td>
                <td>{{secondsFormat(UdpData_updown1.benban['scsj'])}}</td>
                <td>{{secondsFormat(UdpData_updown1.benbi['scsj'])}}</td>
            </tr>
            <tr>
                <td>停车时间</td>
                <td>{{secondsFormat(UdpData_updown1.benban['tcsj'])}}</td>
                <td>{{secondsFormat(UdpData_updown1.benbi['tcsj'])}}</td>
            </tr>
        </table>
    </div>
    <div v-else>
        <div class="pane-box" style="margin-top: 40px;">
            <div class="item">
                <p class="up">切刀数</p>
                <button @click="BuildCharts('切刀数','刀数','qds',1)">{{UdpData_updown0.qds}}</button>
            </div>
            <div class="item">
                <p class="up">生产刀数</p>
                <button @click="BuildCharts('生产刀数','刀数','scds',1)">{{UdpData_updown0.scds}}</button>
            </div>
            <div class="item">
                <p class="up">剩余刀数</p>
                <button @click="BuildCharts('剩余刀数','刀数','syds',1)">{{UdpData_updown0.syds}}</button>
            </div>
            <div class="item">
                <p class="up">不良刀数</p>
                <button @click="BuildCharts('不良刀数','刀数','blds',1)">{{UdpData_updown0.blds}}</button>
            </div>
        </div>
        <div class="pane-box" v-if="Number(DB_CONFIG[db_config_index].isnew)">
            <div class="item">
                <p class="up">订单剩余(m)</p>
                <button @click="BuildCharts('订单剩余','米(m)','ddsy',1)">{{UdpData_updown0.ddsy}}</button>
            </div>
            <div class="item">
                <p class="up">订单长(m)</p>
                <button @click="BuildCharts('订单长','米(m)','ddc',1)">{{UdpData_updown0.ddc}}</button>
            </div>
            <div class="item">
                <p class="up">切长(mm)</p>
                <button @click="BuildCharts('切长','毫米(mm)','qc',1)">{{UdpData_updown0.qc}}</button>
            </div>
            <div class="item">
                <p class="up">车速</p>
                <button @click="BuildCharts('车速','','cs',1)">{{UdpData_updown0.cs}}</button>
            </div>
        </div>
        <table>
            <tr>
                <td>坑机</td>
                <td>糊机</td>
                <td>SF1</td>
                <td>SF2</td>
                <td>SF3</td>
            </tr>
            <tr>
                <td>车速</td>
                <td><button @click="BuildCharts('糊机/车速','','huji_cs',2)">{{UdpData_updown0.huji['cs']}}</button></td>
                <td><button @click="BuildCharts('SF1/车速','','SF1_cs',2)">{{UdpData_updown0.SF1['cs']}}</button></td>
                <td><button @click="BuildCharts('SF2/车速','','SF2_cs',2)">{{UdpData_updown0.SF2['cs']}}</button></td>
                <td><button @click="BuildCharts('SF3/车速','','SF3_cs',2)">{{UdpData_updown0.SF3['cs']}}</button></td>
            </tr>
            <tr v-if="Number(DB_CONFIG[db_config_index].isnew)">
                <td>剩余</td>
                <td><button @click="BuildCharts('糊机/剩余','','huji_sy',2)">{{UdpData_updown0.huji['sy']}}</button></td>
                <td><button @click="BuildCharts('SF1/剩余','','SF1_sy',2)">{{UdpData_updown0.SF1['sy']}}</button></td>
                <td><button @click="BuildCharts('SF2/剩余','','SF2_sy',2)">{{UdpData_updown0.SF2['sy']}}</button></td>
                <td><button @click="BuildCharts('SF3/剩余','','SF3_sy',2)">{{UdpData_updown0.SF3['sy']}}</button></td>
            </tr>
            <tr v-if="Number(DB_CONFIG[db_config_index].isnew)">
                <td>累计</td>
                <td><button @click="BuildCharts('糊机/累计','','huji_lj',2)">{{UdpData_updown0.huji['lj']}}</button></td>
                <td><button @click="BuildCharts('SF1/累计','','SF1_lj',2)">{{UdpData_updown0.SF1['lj']}}</button></td>
                <td><button @click="BuildCharts('SF2/累计','','SF2_lj',2)">{{UdpData_updown0.SF2['lj']}}</button></td>
                <td><button @click="BuildCharts('SF3/累计','','SF3_lj',2)">{{UdpData_updown0.SF3['lj']}}</button></td>
            </tr>
        </table>
        <table>
            <tr>
                <td>订单</td>
                <td>{{UdpData_updown0.class}}班</td>
                <td>本笔</td>
            </tr>
            <tr>
                <td>总米数(m)</td>
                <td><button @click="BuildCharts('？班/总米数','米(m)','benban_zms',1)">{{UdpData_updown0.benban['zms']}}</button></td>
                <td><button @click="BuildCharts('本笔/总米数','米(m)','benbi_zms',1)">{{UdpData_updown0.benbi['zms']}}</button></td>
            </tr>
            <tr>
                <td>生产(m)</td>
                <td><button @click="BuildCharts('？班/生产','米(m)','benban_sc',1)">{{UdpData_updown0.benban['sc']}}</button></td>
                <td><button @click="BuildCharts('本笔/生产','米(m)','benbi_sc',1)">{{UdpData_updown0.benbi['sc']}}</button></td>
            </tr>
            <tr>
                <td>剩余(m)</td>
                <td><button @click="BuildCharts('？班/剩余','米(m)','benban_sy',1)">{{UdpData_updown0.benban['sy']}}</button></td>
                <td><button @click="BuildCharts('本笔/剩余','米(m)','benbi_sy',1)">{{UdpData_updown0.benbi['sy']}}</button></td>
            </tr>
            <tr>
                <td>不良(m)</td>
                <td><button @click="BuildCharts('？班/不良','米(m)','benban_bl',1)">{{UdpData_updown0.benban['bl']}}</button></td>
                <td><button @click="BuildCharts('本笔/不良','米(m)','benbi_bl',1)">{{UdpData_updown0.benbi['bl']}}</button></td>
            </tr>
            <tr>
                <td>生产平方(㎡)</td>
                <td><button @click="BuildCharts('？班/生产平方','平方米(㎡)','benban_scpf',1)">{{UdpData_updown0.benban['scpf']}}</button></td>
                <td><button @click="BuildCharts('本笔/生产平方','平方米(㎡)','benbi_scpf',1)">{{UdpData_updown0.benbi['scpf']}}</button></td>
            </tr>
            <!--<tr>
                <td>生产净平方(㎡)</td>
                <td><button @click="BuildCharts('？班/生产净平方','平方米(㎡)','benban_scjpf',1)">{{UdpData_updown0.benban['scjpf']}}</button></td>
                <td><button @click="BuildCharts('本笔/生产净平方','平方米(㎡)','benbi_scjpf',1)">{{UdpData_updown0.benbi['scjpf']}}</button></td>
            </tr>-->
            <tr>
                <td>坏纸率(%)</td>
                <td><button @click="BuildCharts('？班/坏纸率','%','benban_hzl',1)">{{UdpData_updown0.benban['hzl']}}</button></td>
                <td><button @click="BuildCharts('本笔/坏纸率','%','benbi_hzl',1)">{{UdpData_updown0.benbi['hzl']}}</button></td>
            </tr>
            <tr>
                <td>修边率(%)</td>
                <td><button @click="BuildCharts('？班/修边率','%','benban_xbl',1)">{{UdpData_updown0.benban['xbl']}}</button></td>
                <td><button @click="BuildCharts('本笔/修边率','%','benbi_xbl',1)">{{UdpData_updown0.benbi['xbl']}}</button></td>
            </tr>
            <tr>
                <td>均速(m/min)</td>
                <td><button @click="BuildCharts('？班/均速','米/分(m/min)','benban_js',1)">{{UdpData_updown0.benban['js']}}</button></td>
                <td><button @click="BuildCharts('本笔/均速','米/分(m/min)','benbi_js',1)">{{UdpData_updown0.benbi['js']}}</button></td>
            </tr>
            <tr>
                <td>停车次数</td>
                <td><button @click="BuildCharts('？班/停车次数','次数','benban_tccs',1)">{{UdpData_updown0.benban['tccs']}}</button></td>
                <td><button @click="BuildCharts('本笔/停车次数','次数','benbi_tccs',1)">{{UdpData_updown0.benbi['tccs']}}</button></td>
            </tr>
            <tr>
                <td>生产时间</td>
                <td>{{secondsFormat(UdpData_updown0.benban['scsj'])}}</td>
                <td>{{secondsFormat(UdpData_updown0.benbi['scsj'])}}</td>
            </tr>
            <tr>
                <td>停车时间</td>
                <td>{{secondsFormat(UdpData_updown0.benban['tcsj'])}}</td>
                <td>{{secondsFormat(UdpData_updown0.benbi['tcsj'])}}</td>
            </tr>
        </table>
    </div>
    <div class="msg-box">
        <div class="right" @click="$.isEmptyObject(msg)?$.toast('没有通知','text'):showMsg = true"></div>
        <div class="left">
            <i class="iconfont icon-laba1"></i>
        </div>
        <div class="mid">
            <span v-if="msg[0]">
                <span style="color: #3598dc;">{{msg[0].msg}}</span>
                <span>[&nbsp;{{msg[0].time}}&nbsp;]</span>
            </span>
            <span style="color: grey;" v-else>没有通知</span>
        </div>
    </div>
    <div class="right-aside" :class="{'push':showMsg}">
        <div class="shadow" @click="showMsg = false"></div>
        <div class="msg">
            <div class="item" v-for="v in msg">
                <span style="color: #3598dc;">{{v.msg}}</span>
                <span>[&nbsp;{{v.time}}&nbsp;]</span>
            </div>
        </div>
    </div>
    <div id="container" style="margin-bottom: 60px;"></div>
</div>

<script>
    new Vue({
        el: '#VueBox',
        data: {
            DB_CONFIG: eval('(' + '<?php echo ($DB_CONFIG); ?>' + ')'),
            db_config_index: parseInt('<?php echo ($db_config_index); ?>'),
            ChangeDBSelect: false,
            UdpData_updown0: {
                'class': '？',
                'qds': 0,
                'scds':  0,
                'syds':  0,
                'ddsy':  0,
                'blds':  0,
                'ddc':  0,
                'qc':  0,
                'cs':  0,
                'benban': {
                    'zms': 0,
                    'sc': 0,
                    'sy': 0,
                    'bl': 0,
                    'js': 0,
                    'scpf': 0,
                    'scsj': 0,
                    'tcsj': 0,
                    'tccs': 0,
                    'scjpf': 0,
                    'hzl': 0,
                    'xbl': 0
                },
                'benbi': {
                    'zms': 0,
                    'sc': 0,
                    'sy': 0,
                    'bl': 0,
                    'js': 0,
                    'scpf': 0,
                    'scsj': 0,
                    'tcsj': 0,
                    'tccs': 0,
                    'scjpf': 0,
                    'hzl': 0,
                    'xbl': 0
                },
                'huji': {
                    'cs': 0,
                    'sy': 0,
                    'lj': 0
                },
                'SF1': {
                    'cs': 0,
                    'sy': 0,
                    'lj': 0
                },
                'SF2': {
                    'cs': 0,
                    'sy': 0,
                    'lj': 0
                },
                'SF3': {
                    'cs': 0,
                    'sy': 0,
                    'lj': 0
                }
            },
            UdpData_updown1: {
                'class': '？',
                'qds1': 0,
                'scds1':  0,
                'syds1':  0,
                'ddsy1':  0,
                'blds1':  0,
                'ddc1':  0,
                'qc1':  0,
                'qds2': 0,
                'scds2':  0,
                'syds2':  0,
                'ddsy2':  0,
                'blds2':  0,
                'ddc2':  0,
                'qc2':  0,
                'xs':  0,
                'benban': {
                    'zms': 0,
                    'sc': 0,
                    'sy': 0,
                    'bl': 0,
                    'js': 0,
                    'scpf': 0,
                    'scsj': 0,
                    'tcsj': 0,
                    'tccs': 0,
                    'scjpf': 0,
                    'hzl': 0,
                    'xbl': 0
                },
                'benbi': {
                    'zms': 0,
                    'sc': 0,
                    'sy': 0,
                    'bl': 0,
                    'js': 0,
                    'scpf': 0,
                    'scsj': 0,
                    'tcsj': 0,
                    'tccs': 0,
                    'scjpf': 0,
                    'hzl': 0,
                    'xbl': 0
                },
                'huji': {'cs': 0},
                'SF1': {'cs': 0},
                'SF2': {'cs': 0},
                'SF3': {'cs': 0}
            },
            ChartsData: {},
            ShowCharts: false,
            PointObject: '',
            msg: [],
            showMsg: false
        },
        methods: {
            ChangeDB: function (k) {
                this.ChangeDBSelect = false;
                window.location.href = '<?php echo U(ACTION_NAME);?>?db_config_index=' + k;
            },
            BuildCharts: function (a,b,c,d) {
                $('.ShowChartsClass').removeClass('ShowChartsClass');
                $(event.currentTarget).addClass('ShowChartsClass');
                var _class;
                if(d === 1){
                    _class = this.UdpData_updown0.class;
                }else if(d === 2){
                    _class = this.UdpData_updown1.class;
                }else{
                    return;
                }
                this.ChartsData = Highcharts.chart('container',{
                    chart: {
                        type: 'spline',
                        animation: Highcharts.svg,
                        marginRight: 10
                    },
                    //colors: ['#3598dc'],
                    credits: {enabled: false},
                    title: {text: a.replace(/？/g,_class)},
                    xAxis: {
                        type: 'datetime',
                        tickPixelInterval: 150
                    },
                    yAxis: {
                        title: {text: b},
                        plotLines: [{
                            value: 0,
                            width: 1,
                            color: '#808080'
                        }]
                    },
                    tooltip: {
                        formatter: function () {
                            return '<b>' + this.series.name + '</b><br/>' +
                                Highcharts.dateFormat('%Y-%m-%d %H:%M:%S', this.x) + '<br/>' +
                                Highcharts.numberFormat(this.y, 2);
                        }
                    },
                    legend: {enabled: false},
                    series: [{
                        name: a.replace(/？/g,_class),
                        data: (function () {
                            var data = [];
                            for (var i = -10;i <= 0;i ++) {
                                data.push({
                                    x: (new Date()).getTime() + i * 1000,
                                    y: 0
                                });
                            }
                            return data;
                        }())
                    }]
                });
                this.ShowCharts = true;
                this.PointObject = c;
            }
        },
        mounted: function () {
            var _this = this;
            _this.ChartsData = {};
            _this.ShowCharts = false;
            _this.PointObject = '';
            $.ajax({
                url: '<?php echo U('getSocketioDomain');?>?db_config_index=' + _this.db_config_index,
                success: function (domain) {
                    //连接服务端
                    var socket = io(domain);
                    //参考链接：https://blog.csdn.net/zsj777/article/details/83212193
                    socket.on('connect_error',function () {
                        $.ajax({
                            url: '<?php echo U('connect_error_api');?>?db_config_index=' + _this.db_config_index,
                            success: function (respon) {
                                var respon = eval('(' + respon + ')');
                                _this.msg.splice(0,0,{msg: respon.msg,time: respon.time});
                            }
                        });
                    });
                    //当php后端推送解析好后的UDP数据时
                    socket.on('AnalyUdpData' + _this.db_config_index, function (online_stat) {
                        var online_stat = eval('(' + online_stat + ')');
                        if(online_stat.ret === '<?php echo C('succ_ret');?>'){
                            if(Number(_this.DB_CONFIG[_this.db_config_index].updown)){
                                _this.UdpData_updown1 = online_stat.data;
                                if(_this.ShowCharts){
                                    if(_this.PointObject.indexOf('_') === -1){
                                        _this.ChartsData.series[0].addPoint([(new Date()).getTime(),_this.UdpData_updown1[_this.PointObject]],true,true);
                                    }else{
                                        var tempArr = _this.PointObject.split('_');
                                        _this.ChartsData.series[0].addPoint([(new Date()).getTime(),_this.UdpData_updown1[tempArr[0]][tempArr[1]]],true,true);
                                    }
                                }
                            }else{
                                _this.UdpData_updown0 = online_stat.data;
                                if(_this.ShowCharts){
                                    if(_this.PointObject.indexOf('_') === -1){
                                        _this.ChartsData.series[0].addPoint([(new Date()).getTime(),_this.UdpData_updown0[_this.PointObject]],true,true);
                                    }else{
                                        var tempArr = _this.PointObject.split('_');
                                        _this.ChartsData.series[0].addPoint([(new Date()).getTime(),_this.UdpData_updown0[tempArr[0]][tempArr[1]]],true,true);
                                    }
                                }
                            }
                        }
                    });
                }
            });
        }
    });
</script>

<div class="sg-footer">
    <a href="<?php echo U('Monitor/websocket');?>">
        <div class="icon">
            <i class="iconfont icon-shexiangtou" :style="{'color':menu1?'#3598dc':'#999'}"></i>
        </div>
        <p :style="{'color':menu1?'#3598dc':'#999'}">生产监控</p>
    </a>
    <a href="<?php echo U('Select/bl');?>">
        <div class="icon">
            <i class="iconfont icon-sousuo" :style="{'color':menu2?'#1aad19':'#999'}"></i>
        </div>
        <p :style="{'color':menu2?'#1aad19':'#999'}">备料</p>
    </a>
    <a href="<?php echo U('Select/blms');?>">
        <div class="icon">
            <i class="iconfont icon-sousuo" :style="{'color':menu3?'#1aad19':'#999'}"></i>
        </div>
        <p :style="{'color':menu3?'#1aad19':'#999'}">备料米数</p>
    </a>
    <a href="<?php echo U('Select/scdd');?>">
        <div class="icon">
            <i class="iconfont icon-sousuo" :style="{'color':menu4?'#1aad19':'#999'}"></i>
        </div>
        <p :style="{'color':menu4?'#1aad19':'#999'}">生产订单</p>
    </a>
    <a href="<?php echo U('Select/wgdd');?>">
        <div class="icon">
            <i class="iconfont icon-sousuo" :style="{'color':menu5?'#1aad19':'#999'}"></i>
        </div>
        <p :style="{'color':menu5?'#1aad19':'#999'}">完工订单</p>
    </a>
    <a href="<?php echo U('Alter/index');?>" v-if="Number('<?php echo session('SG_User.root');?>') === 0">
        <div class="icon">
            <i class="iconfont icon-xiugai" :style="{'color':menu6?'#3598dc':'#999'}"></i>
        </div>
        <p :style="{'color':menu6?'#3598dc':'#999'}">修改</p>
    </a>
    <a @click="logout()" style="cursor: pointer;">
        <div class="icon">
            <i class="iconfont icon-tuichu"></i>
        </div>
        <p style="margin-top: 5px;">退出</p>
    </a>
    <a href="javascript:location.reload();">
        <div class="icon">
            <i class="iconfont icon-shuaxin"></i>
        </div>
        <p style="margin-top: 5px;">刷新</p>
    </a>
</div>
<script>
    new Vue({
        el: '.sg-footer',
        data: {
            CurCA: '<?php echo CONTROLLER_NAME;?>/<?php echo ACTION_NAME;?>',
            menu1: false,
            menu2: false,
            menu3: false,
            menu4: false,
            menu5: false,
            menu6: false
        },
        methods: {
            logout: function () {
                $.confirm('确定要退出吗？','',
                    function () {
                        window.location.href = '<?php echo U('Logout/logout');?>';
                    }
                );
            }
        },
        mounted: function () {
            if(this.CurCA === 'Monitor/websocket'){
                this.menu1 = true;
            }else if(this.CurCA === 'Select/bl'){
                this.menu2 = true;
            }else if(this.CurCA === 'Select/blms'){
                this.menu3 = true;
            }else if(this.CurCA === 'Select/scdd'){
                this.menu4 = true;
            }else if(this.CurCA === 'Select/wgdd'){
                this.menu5 = true;
            }else if(this.CurCA === 'Alter/index'){
                this.menu6 = true;
            }else{

            }
        }
    });
</script>
</body>
</html>