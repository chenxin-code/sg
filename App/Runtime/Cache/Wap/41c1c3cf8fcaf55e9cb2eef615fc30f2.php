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
<!-- 引入 mescroll 插件 -->
<script src="/sg/res/mescroll/mescroll.min.js"></script>
<link rel="stylesheet" href="/sg/res/mescroll/mescroll.min.css">

<style>
    .mescroll {
        height: auto;
        position: fixed;
        top: 40px;
        bottom: 60px;
    }
    .mescroll-totop {
        bottom: 70px;
        z-index: 1;
    }
</style>

<div id="VueBox">
    <div class="sg-header">
        <div @click="ChangeDBSelect = true" class="filter">
            <span :style="{'color':ChangeDBSelect?'#1aad19':'#3d4245'}">{{DB_CONFIG[DB].DB_FLAG}}</span>
            <i class="iconfont icon-xialajiantou"></i>
            <div class="line"></div>
        </div>
        <div @click="OpenSlider()" class="filter">
            <i class="iconfont icon-shaixuan"></i>&nbsp;筛选
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

    <div id="mescroll" class="mescroll">
        <div id="lists" v-if="Number(DB_CONFIG[DB].updown)">
            <div v-for="(v1,k1) in lists">
                <div v-for="(v2,k2) in v1" :class="'info-item ' + (k1%2?'bgc1':'bgc2')">
                    <div class="flex-box">
                        <div>序号：<span>{{v2.sn}}</span></div>
                    </div>
                    <div class="flex-box">
                        <div>订单号：<span>{{v2.order_number}}</span></div>
                    </div>
                    <div class="flex-box" v-if="Number('<?php echo session('SG_User.root');?>') !== 2">
                        <div>客户名称：<span>{{v2.company_name}}</span></div>
                    </div>
                    <div class="flex-box">
                        <div>纸质：<span v-if="Number(DB_CONFIG[DB].isnew)">{{v2.paper_code}}</span><span v-else>{{v2.paper}}</span></div>
                        <div>坑型：<span>{{v2.flute_type}}</span></div>
                    </div>
                    <div class="flex-box">
                        <div>门幅：<span>{{v2.width}}</span></div>
                        <div>修边：<span>{{v2.trimming}}</span></div>
                    </div>
                    <div class="flex-box">
                        <div>纸宽：<span>{{v2.paper_w}}</span></div>
                        <div>纸长：<span>{{v2.paper_len}}</span></div>
                    </div>
                    <div class="flex-box">
                        <div>订单数：<span>{{v2.order_qty}}</span></div>
                        <div>切刀数：<span>{{v2.cutting_qty}}</span></div>
                    </div>
                    <div class="flex-box">
                        <div>压型：<span>{{v2.pressing_type}}</span></div>
                        <div>剖1：<span v-if="Number(DB_CONFIG[DB].isnew)">{{v2.slitting}}</span><span v-else>{{v2.slitting1}}</span></div>
                    </div>
                    <div class="flex-box">
                        <div>生产数：<span>{{v2.prod_qty}}</span></div>
                        <div>坏品数：<span>{{v2.bad_qty}}</span></div>
                    </div>
                    <div class="flex-box">
                        <div>停车次数：<span>{{v2.stops}}</span></div>
                        <div>停车时间：<span>{{v2.stop_time}}</span></div>
                    </div>
                    <div class="flex-box">
                        <div>压线资料1：<span v-if="Number(DB_CONFIG[DB].isnew)">{{v2.slitting_data}}</span><span v-else>{{v2.slitting_data1}}</span></div>
                    </div>
                    <div class="flex-box">
                        <div>完工时间：<span>{{v2.finish_date}}</span></div>
                    </div>
                    <div class="oblique-sign-up" v-if="v2.tag === '1'">上刀</div>
                    <div class="oblique-sign-down" v-else-if="v2.tag === '-1'">下刀</div>
                </div>
            </div>
        </div>
        <div id="lists" v-else>
            <div v-for="(v,k) in lists" :class="'info-item ' + (k%2?'bgc1':'bgc2')">
                <div class="flex-box">
                    <div>序号：<span>{{v.sn}}</span></div>
                </div>
                <div class="flex-box">
                    <div>订单号：<span>{{v.order_number}}</span></div>
                </div>
                <div class="flex-box" v-if="Number('<?php echo session('SG_User.root');?>') !== 2">
                    <div>客户名称：<span>{{v.company_name}}</span></div>
                </div>
                <div class="flex-box">
                    <div>门幅：<span>{{v.width}}</span></div>
                    <div>纸质：<span v-if="Number(DB_CONFIG[DB].isnew)">{{v.paper_code}}</span><span v-else>{{v.paper}}</span></div>
                </div>
                <div class="flex-box">
                    <div>坑型：<span>{{v.flute_type}}</span></div>
                    <div>修边：<span>{{v.trimming}}</span></div>
                </div>
                <div class="flex-box">
                    <div>纸宽：<span>{{v.paper_w}}</span></div>
                    <div>纸长：<span>{{v.paper_len}}</span></div>
                </div>
                <div class="flex-box">
                    <div>订单数：<span>{{v.order_qty}}</span></div>
                    <div>切刀数：<span>{{v.cutting_qty}}</span></div>
                </div>
                <div class="flex-box">
                    <div>压型：<span>{{v.pressing_type}}</span></div>
                    <div>剖1：<span v-if="Number(DB_CONFIG[DB].isnew)">{{v.slitting}}</span><span v-else>{{v.slitting1}}</span></div>
                </div>
                <div class="flex-box">
                    <div>生产数：<span>{{v.good_qty}}</span></div>
                    <div>坏品数：<span>{{v.bad_qty}}</span></div>
                </div>
                <div class="flex-box">
                    <div>停车次数：<span>{{v.stops}}</span></div>
                    <div>停车时间：<span>{{v.stop_time}}</span></div>
                </div>
                <div class="flex-box">
                    <div>压线资料1：<span v-if="Number(DB_CONFIG[DB].isnew)">{{v.slitting_data}}</span><span v-else>{{v.slitting_data1}}</span></div>
                </div>
                <div class="flex-box">
                    <div>完工时间：<span>{{v.finish_date}}</span></div>
                </div>
            </div>
        </div>
    </div>
    <div class="right-aside" :class="{'push':BarVisible}">
        <div class="shadow" @click="CloseSlider(false)"></div>
        <div class="form">
            <div class="rows">
                <div class="row1">
                    <div class="title">序号</div>
                    <input type="number" v-model="form.sn" class="input" placeholder="精确查询">
                </div>
                <div class="row1">
                    <div class="title">订单号</div>
                    <input v-model="form.order_number" class="input" placeholder="模糊查询">
                </div>
                <div class="row1" v-if="Number('<?php echo session('SG_User.root');?>') !== 2">
                    <div class="title">客户名称</div>
                    <input v-model="form.company_name" class="input" placeholder="模糊查询">
                </div>
                <div class="row1">
                    <div class="title">纸质</div>
                    <input v-model="form.paper_code" class="input" placeholder="模糊查询">
                </div>
                <div class="row1">
                    <div class="title">坑型</div>
                    <input v-model="form.flute_type" class="input" placeholder="精确查询">
                </div>
                <div class="row1">
                    <div class="title">门幅</div>
                    <input type="number" v-model="form.width" class="input" placeholder="精确查询">
                </div>
                <div class="row1">
                    <div class="title">完工时间(开始)</div>
                    <div class="input" @click="$refs.BeginTime.open()">{{datetimeFormat(form.BeginTime,'yyyy-MM-dd hh:mm:00')}}</div>
                </div>
                <div class="row1">
                    <div class="title">完工时间(结束)</div>
                    <div class="input" @click="$refs.EndTime.open()">{{datetimeFormat(form.EndTime,'yyyy-MM-dd hh:mm:59')}}</div>
                </div>
                <div class="row3">
                    <label for="rememberForm" :class="{'checked':form.rememberForm === 'yes'}"></label>
                    <label for="rememberForm">记住筛选条件(本次登录有效)</label>
                    <input type="checkbox" id="rememberForm" v-model="form.rememberForm" true-value="yes" false-value="no" hidden>
                </div>
                <!--<div style="height:1500px;background-color:#1aad19;border:5px solid #ff5000;"></div>-->
            </div>
            <div class="footer">
                <div @click="ResetForm()" class="reset">重置</div>
                <div @click="CloseSlider(true)" class="confirm">确定</div>
            </div>
        </div>
    </div>
    <mt-datetime-picker type="datetime" :start-date="MinTime" :end-date="MaxTime" v-model="form.BeginTime" ref="BeginTime"></mt-datetime-picker>
    <mt-datetime-picker type="datetime" :start-date="MinTime" :end-date="MaxTime" v-model="form.EndTime" ref="EndTime"></mt-datetime-picker>
</div>

<script>
    new Vue({
        el: '#VueBox',
        data: {
            DB_CONFIG: eval('(' + '<?php echo ($DB_CONFIG); ?>' + ')'),
            DB: Number('<?php echo session('SG_User.DB');?>')?Number('<?php echo session('SG_User.DB');?>'):0,
            //侧边栏的表单
            form: {
                //tag: '',
                sn: '',
                order_number: '',
                company_name: '',
                paper_code: '',
                flute_type: '',
                width: '',
                BeginTime: '<?php echo date('Y-m-d 00:00:00');?>',
                EndTime: '<?php echo date('Y-m-d 23:59:59');?>',
                rememberForm: 'no'
            },
            MinTime: new Date('<?php echo date('Y/m/d 00:00:00',strtotime('-1 year'));?>'),
            MaxTime: new Date('<?php echo date('Y/m/d 23:59:59',strtotime('now'));?>'),
            //打开侧边栏前的form对象
            BeforeOpenSliderForm: {},
            BarVisible: false,
            tempTop: 0,
            ChangeDBSelect: false,
            mescroll: null,
            lists: []
        },
        methods: {
            upCallback: function(page) {
                var _this = this;
                _this.search(page.num, page.size, function (respon) {
                    //如果是第一页需手动制空列表 (代替clearId和clearEmptyId的配置)
                    if(page.num === 1){
                        _this.lists = [];
                    }
                    _this.lists = _this.lists.concat(respon.data);
                    //_this.mescroll.endSuccess(respon.data.length);
                    _this.mescroll.endBySize(respon.data.length, respon.count);
                }, function() {
                    _this.mescroll.endErr();
                });
            },
            search: function (CurPage,PageSize,successCallback,errorCallback) {
                var _this = this;
                _this.form.BeginTime = datetimeFormat(_this.form.BeginTime,'yyyy/MM/dd hh:mm:00');
                _this.form.EndTime = datetimeFormat(_this.form.EndTime,'yyyy/MM/dd hh:mm:59');
                $.ajax({
                    url: '<?php echo U('wgdd_api');?>',
                    type: 'get',
                    data: {
                        CurPage: CurPage,
                        PageSize: PageSize,
                        DB: _this.DB,
                        form: _this.form
                    },
                    success: function (respon) {
                        var respon = eval('(' + respon + ')');
                        if(respon.ret === '<?php echo C('succ_ret');?>'){
                            successCallback && successCallback(respon);
                        }else{
                            _this.lists = [];
                            $.toast(respon.msg,'forbidden');
                            errorCallback();
                        }
                    },
                    error: errorCallback
                });
            },
            ResetLists: function () {
                this.lists = [];
                this.mescroll.resetUpScroll();
                this.mescroll.hideTopBtn();
            },
            ResetForm: function () {
                this.form = Object.assign({},this.BeforeOpenSliderForm);
            },
            OpenSlider: function () {
                this.BarVisible = true;
                this.tempTop = document.scrollingElement.scrollTop;
                document.body.classList.add('body-lock');
                document.body.style.top = -this.tempTop + 'px';
                this.BeforeOpenSliderForm = Object.assign({},this.form);
            },
            CloseSlider: function (IsClickConfirm) {
                this.BarVisible = false;
                document.body.classList.remove('body-lock');
                document.scrollingElement.scrollTop = this.tempTop;
                if(IsClickConfirm
                    //&& !checkObjectIsEqual(this.form,this.BeforeOpenSliderForm)
                ){
                    this.ResetLists();
                }else{
                    this.ResetForm();
                }
            },
            ChangeDB: function (k) {
                this.ChangeDBSelect = false;
                this.DB = k;
                this.ResetLists();
            }
        },
        mounted: function () {
            var _this = this;
            _this.form = Object.assign({},_this.form,eval('(' + '<?php echo ($rememberForm); ?>' + ')'));
            _this.mescroll = new MeScroll('mescroll',{
                up: {
                    callback: _this.upCallback, //上拉回调
                    isBounce: false, //此处禁止ios回弹,如果您的项目是在iOS的微信,QQ,Safari等浏览器访问的,建议配置此项
                    noMoreSize: 0, //如果列表已无数据,可设置列表的总数量要大于0条才显示无更多数据;避免列表数据过少(比如只有一条数据),显示无更多数据会不好看
                    page: {size: 3},
                    toTop: {src: '/sg/res/totop.png'},
                    htmlNodata: '<p class="upwarp-nodata">-- 没有更多订单了 --</p>',
                    //vue的案例请勿配置clearId和clearEmptyId,否则列表的数据模板会被清空
                    //clearEmptyId: 'lists', //1.下拉刷新时会自动先清空此列表,再加入数据; 2.无任何数据时会在此列表自动提示空
                    empty: {
                        warpId: 'lists',
                        icon: '/sg/res/empty.jpg',
                        tip: '没有找到相关订单'
                    }
                }
            });
            //初始化vue后,显示vue模板布局
            //document.getElementById('lists').style.display = 'block';
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
