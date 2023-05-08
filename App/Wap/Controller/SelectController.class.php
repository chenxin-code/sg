<?php
namespace Wap\Controller;
class SelectController extends BaseController{

    /********** 页面 **********/

    //备料
    public function bl(){
        $this->assign([
            'LayoutTitle' => '备料',
            'DB_CONFIG' => jejuu($this->DB_CONFIG),
        ]);
        $this->display();
    }

    //备料米数
    public function blms(){
        $this->assign([
            'LayoutTitle' => '备料米数',
            'DB_CONFIG' => jejuu($this->DB_CONFIG),
        ]);
        $this->display();
    }

    //生产订单
    public function scdd(){
        $mca = md5(strtolower(MODULE_NAME.CONTROLLER_NAME.ACTION_NAME.'_api'));
        $rememberForm = session('SG_User.rememberForm_'.$mca)?session('SG_User.rememberForm_'.$mca):[];
        $this->assign([
            'LayoutTitle' => '生产订单',
            'DB_CONFIG' => jejuu($this->DB_CONFIG),
            'rememberForm' => jejuu($rememberForm),
        ]);
        $this->display();
    }

    //完工订单
    public function wgdd(){
        $mca = md5(strtolower(MODULE_NAME.CONTROLLER_NAME.ACTION_NAME.'_api'));
        $rememberForm = session('SG_User.rememberForm_'.$mca)?session('SG_User.rememberForm_'.$mca):[];
        $this->assign([
            'LayoutTitle' => '完工订单',
            'DB_CONFIG' => jejuu($this->DB_CONFIG),
            'rememberForm' => jejuu($rememberForm),
        ]);
        $this->display();
    }

    /********** 接口 **********/

    //备料
    public function bl_api(){
        $get = I('get.');
        session('SG_User.DB',$get['DB']);
        if(C('DB_CONFIG.'.$get['DB'])['isnew']){
            $tablename = 'MyOrder';
        }else{
            if(C('DB_CONFIG.'.$get['DB'])['updown']){
                $tablename = 'MyOrder';
            }else{
                $tablename = 'bc';
            }
        }
        try {
            //（关于set nocount on）链接：http://www.thinkphp.cn/topic/46368.html
            $data = M('','','DB_CONFIG.'.$get['DB'])->query('exec P_GetPrePaperTotal '.$tablename.',\'\'');
        } catch(\Exception $e){
            echo jejuu([
                'ret' => C('fail_ret'),
                'msg' => $e->getMessage(),
            ]);die;
        }
        //dump($data);die;
        echo jejuu([
            'ret' => C('succ_ret'),
            'data' => $data,
        ]);
    }

    //备料米数
    public function blms_api(){
        $get = I('get.');
        session('SG_User.DB',$get['DB']);
        try {
            $_data = M('view_myorder','','DB_CONFIG.'.$get['DB'])
                ->where([(C('DB_CONFIG.'.$get['DB'])['isnew']?'paper_code':'paper') => ['EXP','IS NOT NULL']])
                ->field('sn,'.(C('DB_CONFIG.'.$get['DB'])['isnew']?'paper_code':'paper as paper_code').',width as paper_width,(paper_len * cutting_qty / 1000.0) as paper_length')
                ->order('sn')
                ->select();
            $Flute = M('','','DB_CONFIG.'.$get['DB'])->query('exec P_GetFlute');
        } catch(\Exception $e){
            echo jejuu([
                'ret' => C('fail_ret'),
                'msg' => $e->getMessage(),
            ]);die;
        }
        //dump($_data);dump($Flute);die;
        $CengInfo = ['糊机备纸','SF1芯纸','SF1面纸','SF2芯纸','SF2面纸','SF3芯纸','SF3面纸'];
        if(C('DB_CONFIG.'.$get['DB'])['updown']){
            //二维转三维
            $data = [];
            foreach ($_data as $k => $v) {
                $data[$v['sn']][] = $v;
            }
            //重构数组
            foreach ($data as $k => $v) {
                $data[$k] = [
                    'paper_code' => $v[0]['paper_code'],
                    'paper_width' => $v[0]['paper_width'],
                    'paper_length' => max($v[0]['paper_length'],$v[1]['paper_length']),
                ];
            }
            $data = array_values($data);
        }else{
            $data = $_data;
            //重构数组
            foreach ($data as $k => $v) {
                unset($data[$k]['sn']);
                unset($data[$k]['row_number']);
                $repeat = count($CengInfo) - strlen($data[$k]['paper_code']);
                if($repeat > 0){
                    $data[$k]['paper_code'] .= str_repeat(str_repeat(C('DB_CONFIG.'.$get['DB'])['paperCodeSpaceChar'],C('DB_CONFIG.'.$get['DB'])['paperCodeNumber']),$repeat);
                }
            }
        }
        //dump($data);die;
        $new_data = [];
        $array_count = [];
        foreach ($CengInfo as $k1 => $v1) {
            $new_data_son = [];
            $PaperCodeFlag = '';
            $PaperWidthFlag = '';
            foreach ($data as $k2 => $v2) {
                $PaperCode = substr($data[$k2]['paper_code'],$k1 * C('DB_CONFIG.'.$get['DB'])['paperCodeNumber'],C('DB_CONFIG.'.$get['DB'])['paperCodeNumber']);
                if(!$PaperCode || $PaperCode === str_repeat(C('DB_CONFIG.'.$get['DB'])['paperCodeSpaceChar'],C('DB_CONFIG.'.$get['DB'])['paperCodeNumber'])){continue;}
                if($PaperCode === $PaperCodeFlag && $data[$k2]['paper_width'] === $PaperWidthFlag){
                    $new_data_son[count($new_data_son) - 1]['PaperLength'] += (float)$data[$k2]['paper_length'];
                }else{
                    $PaperCodeFlag = $PaperCode;
                    $PaperWidthFlag = $data[$k2]['paper_width'];
                    $new_data_son[] = ['PaperCode' => $PaperCode,'PaperWidth' => $data[$k2]['paper_width'],'PaperLength' => (float)$data[$k2]['paper_length']];
                }
            }
            $new_data[$CengInfo[$k1]] = $new_data_son;
            $array_count[] = count($new_data_son);
        }
        //dump($new_data);die;
        foreach ($new_data as $k1 => $v1) {
            if($k1 === 'SF1芯纸'){
                $flute_rate = $Flute[0]['fluterate1'];
            }elseif($k1 === 'SF2芯纸'){
                $flute_rate = $Flute[0]['fluterate2'];
            }elseif($k1 === 'SF3芯纸'){
                $flute_rate = $Flute[0]['fluterate3'];
            }else{
                $flute_rate = 1;
            }
            foreach ($v1 as $k2 => $v2) {
                $new_data[$k1][$k2]['PaperLength'] = round($v2['PaperLength'] * $flute_rate,0);
            }
        }
        //dump($new_data);die;
        //el-table组件数据重构
        $el_data = [];
        $MaxLength = max($array_count);
        for ($i = 0;$i < $MaxLength;$i++) {
            $el_data_son = [];
            for ($j = 0;$j < count($CengInfo);$j++) {
                if($new_data[$CengInfo[$j]][$i]){
                    $el_data_son[$CengInfo[$j]] = $new_data[$CengInfo[$j]][$i]['PaperCode'].'*'.$new_data[$CengInfo[$j]][$i]['PaperWidth'].'='.$new_data[$CengInfo[$j]][$i]['PaperLength'];
                }
            }
            $el_data[$i] = $el_data_son;
        }
        //dump($el_data);die;
        echo jejuu([
            'ret' => C('succ_ret'),
            'CengInfo' => $CengInfo,
            'data' => $el_data,
            //'MaxLength' => $MaxLength,
        ]);
    }

    //生产订单
    public function scdd_api(){
        $get = I('get.');
        session('SG_User.DB',$get['DB']);
        $mca = md5(strtolower(MODULE_NAME.CONTROLLER_NAME.ACTION_NAME));
        if($get['form']['rememberForm'] === 'yes'){
            session('SG_User.rememberForm_'.$mca,$get['form']);
        }elseif($get['form']['rememberForm'] === 'no'){
            session('SG_User.rememberForm_'.$mca,null);
        }
        $where = [];
        if($get['form']['sn']){
            $where['sn'] = ['eq',$get['form']['sn']];
        }
//        if($get['form']['tag'] === '1'){
//            $where['tag'] = ['eq',1];
//        }else if($get['form']['tag'] === '-1'){
//            $where['tag'] = ['eq',-1];
//        }
        if($get['form']['order_number']){
            $where['order_number'] = ['like','%'.$get['form']['order_number'].'%'];
        }
        if($get['form']['company_name']){
            $where['company_name'] = ['like','%'.$get['form']['company_name'].'%'];
        }
        if(C('DB_CONFIG.'.$get['DB'])['isnew']){
            $field = 'paper_code';
        }else{
            $field = 'paper';
        }
        if($get['form']['paper_code']){
            $where[$field] = ['like','%'.$get['form']['paper_code'].'%'];
        }
        if($get['form']['flute_type']){
            $where['flute_type'] = ['eq',$get['form']['flute_type']];
        }
        if($get['form']['width']){
            $where['width'] = ['eq',$get['form']['width']];
        }
        try {
            $count = M('view_prefinish','','DB_CONFIG.'.$get['DB'])->where($where)->count();
            $model = M('view_prefinish','','DB_CONFIG.'.$get['DB'])->where($where)->order('sn,tag desc');
            if($get['CurPage'] && $get['PageSize']){
                $n = C('DB_CONFIG.'.$get['DB'])['updown']?2:1;
                $MaxPage = ceil($count/($get['PageSize'] * $n));
                if($MaxPage < 1){$MaxPage = 1;}
                $_data = $model->page($get['CurPage'],$get['PageSize'] * $n)->select();
            }else{
                $MaxPage = '当前不是分页取数据该值无意义';
                $_data = $model->select();
            }
        } catch(\Exception $e){
            echo jejuu([
                'ret' => C('fail_ret'),
                'msg' => $e->getMessage(),
            ]);die;
        }
        //echo $MaxPage;die;
        //echo M('view_prefinish','','DB_CONFIG.'.$get['DB'])->getLastSql();die;
        //dump($_data);die;
        foreach ($_data as $k => $v) {
            if($v['pre_finishtime']){
                $_data[$k]['pre_finishtime'] = date('Y-m-d H:i:s',strtotime($v['pre_finishtime']));
            }
        }
        //dump($_data);die;
        //如果为上下刀，则二维转三维
        if(C('DB_CONFIG.'.$get['DB'])['updown']){
            //参考链接：http://blog.csdn.net/xyzchenxiaolin/article/details/51700485
            $data = [];
            foreach ($_data as $k => $v) {
                $data[$v['sn']][] = $v;
            }
            $data = array_values($data);//阻止JS报错(_this.lists.concat is not a function)
        }else{
            $data = $_data;
        }
        //dump($data);die;
        echo jejuu([
            'ret' => C('succ_ret'),
            'count' => $count,
            'MaxPage' => $MaxPage,
            'data' => $data,
        ]);
    }

    //完工订单
    public function wgdd_api(){
        $get = I('get.');
        session('SG_User.DB',$get['DB']);
        $mca = md5(strtolower(MODULE_NAME.CONTROLLER_NAME.ACTION_NAME));
        if($get['form']['rememberForm'] === 'yes'){
            session('SG_User.rememberForm_'.$mca,$get['form']);
        }elseif($get['form']['rememberForm'] === 'no'){
            session('SG_User.rememberForm_'.$mca,null);
        }
        $where = [];
        if($get['form']['sn']){
            $where['sn'] = ['eq',$get['form']['sn']];
        }
//        if($get['form']['tag'] === '1'){
//            $where['tag'] = ['eq',1];
//        }else if($get['form']['tag'] === '-1'){
//            $where['tag'] = ['eq',-1];
//        }
        if($get['form']['order_number']){
            $where['order_number'] = ['like','%'.$get['form']['order_number'].'%'];
        }
        if($get['form']['company_name']){
            $where['company_name'] = ['like','%'.$get['form']['company_name'].'%'];
        }
        if(C('DB_CONFIG.'.$get['DB'])['isnew']){
            $field = 'paper_code';
        }else{
            $field = 'paper';
        }
        if($get['form']['paper_code']){
            $where[$field] = ['like','%'.$get['form']['paper_code'].'%'];
        }
        if($get['form']['flute_type']){
            $where['flute_type'] = ['eq',$get['form']['flute_type']];
        }
        if($get['form']['width']){
            $where['width'] = ['eq',$get['form']['width']];
        }
        if($get['form']['BeginTime'] && $get['form']['EndTime']){
            $where['finish_date'] = ['between',[$get['form']['BeginTime'],$get['form']['EndTime']]];
        }elseif($get['form']['BeginTime']){
            $where['finish_date'] = ['egt',$get['form']['BeginTime']];
        }elseif($get['form']['EndTime']){
            $where['finish_date'] = ['elt',$get['form']['EndTime']];
        }
        try {
            $count = M('view_finish','','DB_CONFIG.'.$get['DB'])->where($where)->count();
            $model = M('view_finish','','DB_CONFIG.'.$get['DB'])->where($where)->order('id desc,tag desc');
            if($get['CurPage'] && $get['PageSize']){
                $n = C('DB_CONFIG.'.$get['DB'])['updown']?2:1;
                $MaxPage = ceil($count/($get['PageSize'] * $n));
                if($MaxPage < 1){$MaxPage = 1;}
                $_data = $model->page($get['CurPage'],$get['PageSize'] * $n)->select();
            }else{
                $MaxPage = '当前不是分页取数据该值无意义';
                $_data = $model->select();
            }
        } catch(\Exception $e){
            echo jejuu([
                'ret' => C('fail_ret'),
                'msg' => $e->getMessage(),
            ]);die;
        }
        //echo $MaxPage;die;
        //dump($_data);die;
        foreach ($_data as $k => $v) {
            if($v['start_time']){
                $_data[$k]['start_time'] = date('Y-m-d H:i:s',strtotime($v['start_time']));
            }
            if($v['finish_date']){
                $_data[$k]['finish_date'] = date('Y-m-d H:i:s',strtotime($v['finish_date']));
            }
        }
        //dump($_data);die;
        //如果为上下刀，则二维转三维
        if(C('DB_CONFIG.'.$get['DB'])['updown']){
            //参考链接：http://blog.csdn.net/xyzchenxiaolin/article/details/51700485
            $data = [];
            foreach ($_data as $k => $v) {
                $data[$v['id']][] = $v;
            }
            $data = array_values($data);//阻止JS报错(_this.lists.concat is not a function)
        }else{
            $data = $_data;
        }
        //dump($data);die;
        echo jejuu([
            'ret' => C('succ_ret'),
            'count' => $count,
            'MaxPage' => $MaxPage,
            'data' => $data,
        ]);
    }

}
