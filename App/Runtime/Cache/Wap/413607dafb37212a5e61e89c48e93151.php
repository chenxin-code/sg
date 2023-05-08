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
<div id="VueBox">
    <div class="sg-header">
        <div @click="ChangeDBSelect = true" class="filter">
            <span :style="{'color':ChangeDBSelect?'#1aad19':'#3d4245'}">{{DB_CONFIG[DB].DB_FLAG}}</span>
            <i class="iconfont icon-xialajiantou"></i>
        </div>
    </div>
    <div class="drop-select-box">
        <div @click="ChangeDBSelect = false" @touchmove.prevent class="shadow" :style="{'display':ChangeDBSelect?'block':'none'}"></div>
        <div class="select" :style="{'display':ChangeDBSelect?'block':'none'}">
            <div v-for="(v,k) in DB_CONFIG" @click="ChangeDB(k)" class="option" :class="{'selected':k === DB}">
                {{v.DB_FLAG}}
                <i class="iconfont icon-xuanzhong"></i>
            </div>
        </div>
    </div>
    <el-table :data="lists" stripe style="margin: 40px 0 60px;" :height="$(document).height() - 100">
        <el-table-column prop="sn" label="序号"></el-table-column>
        <el-table-column prop="width" label="门幅"></el-table-column>
        <el-table-column prop="paper_code" label="生产纸质" v-if="Number(DB_CONFIG[DB].isnew)"></el-table-column>
        <el-table-column prop="paper" label="生产纸质" v-else></el-table-column>
        <el-table-column prop="flute_type" label="楞别"></el-table-column>
        <el-table-column prop="total_len" label="总长"></el-table-column>
    </el-table>
</div>

<script>
    new Vue({
        el: '#VueBox',
        data: {
            DB: Number('<?php echo session('SG_User.DB');?>')?Number('<?php echo session('SG_User.DB');?>'):0,
            DB_CONFIG: eval('(' + '<?php echo ($DB_CONFIG); ?>' + ')'),
            ChangeDBSelect: false,
            lists: null
        },
        methods: {
            search: function () {
                var _this = this;
                _this.lists = null;
                $.ajax({
                    url: '<?php echo U('bl_api');?>',
                    type: 'get',
                    data: {DB: _this.DB},
                    beforeSend: function () {
                        _this.$indicator.open();
                    },
                    success: function (respon) {
                        _this.$indicator.close();
                        var respon = eval('(' + respon + ')');
                        if(respon.ret === '<?php echo C('succ_ret');?>'){
                            _this.lists = respon.data;
                        }else{
                            $.toast(respon.msg,'forbidden');
                        }
                    }
                });
            },
            ChangeDB: function (k) {
                this.ChangeDBSelect = false;
                this.DB = k;
                this.search();
            }
        },
        mounted: function () {
            this.search();
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
