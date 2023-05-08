<?php
namespace Wap\Controller;
//修改
class AlterController extends BaseController{

    public function __construct(){
        parent::__construct();
        if(session('SG_User.root') !== 0){
            if(strpos(ACTION_NAME,'_api') === FALSE){
                $this->error('没有权限');
            }else{
                echo jejuu(['ret' => C('fail_ret'),'msg' => '没有权限']);
            }
            die;
        }
    }

    public function index(){
        $get = I('get.');
        if(isset($get['db_config_index'])){
            session('SG_User.DB',$get['db_config_index']);
            $db_config_index = (int)$get['db_config_index'];
        }else{
            $db_config_index = (int)session('SG_User.DB')?(int)session('SG_User.DB'):0;
        }
        $this->assign([
            'LayoutTitle' => '修改（'.$this->DB_CONFIG[$db_config_index]['DB_FLAG'].'）',
            'db_config_index' => $db_config_index,
            'DB_CONFIG' => jejuu($this->DB_CONFIG),
        ]);
        $this->display();
    }

    public function getValue_api(){
        $get = I('get.');
        if(C('DB_CONFIG.'.$get['db_config_index'])['isnew']){
            try {
                $data = M('','','DB_CONFIG.'.$get['db_config_index'])->table('SysConfiguration')
                    ->where(['ParameterType' => '特殊参数','ParameterName' => 'TMP_FINISH_TOTAL_LEN_OFFSET'])
                    ->field('ParameterValue')
                    ->find();
            } catch(\Exception $e){
                echo jejuu([
                    'ret' => C('fail_ret'),
                    'msg' => $e->getMessage(),
                ]);die;
            }
            echo jejuu([
                'ret' => C('succ_ret'),
                'Value' => $data[strtolower('ParameterValue')],
            ]);
        }else{
            $field = C('DB_CONFIG.'.$get['db_config_index'])['updown']?'prodlenm':'lenmm';
            try {
                $data = M('','','DB_CONFIG.'.$get['db_config_index'])->table('finish')->field('id,'.$field)->order('id desc')->find();
            } catch(\Exception $e){
                echo jejuu([
                    'ret' => C('fail_ret'),
                    'msg' => $e->getMessage(),
                ]);die;
            }
            echo jejuu([
                'ret' => C('succ_ret'),
                'id' => $data['id'],
                'Value' => $data[$field],
            ]);
        }
    }

    public function changeValue_api(){
        $get = I('get.');
        if(C('DB_CONFIG.'.$get['db_config_index'])['isnew']){
            try {
                $data = M('','','DB_CONFIG.'.$get['db_config_index'])->table('SysConfiguration')
                    ->where(['ParameterType' => '特殊参数','ParameterName' => 'TMP_FINISH_TOTAL_LEN_OFFSET'])
                    ->field('ParameterValue')
                    ->find();
                $r = M('','','DB_CONFIG.'.$get['db_config_index'])->table('SysConfiguration')
                    ->where(['ParameterType' => '特殊参数','ParameterName' => 'TMP_FINISH_TOTAL_LEN_OFFSET'])
                    ->save(['ParameterValue' => $get['Value']]);
            } catch(\Exception $e){
                echo jejuu([
                    'ret' => C('fail_ret'),
                    'msg' => $e->getMessage(),
                ]);die;
            }
            if($r){
                $record = session('SG_User.record');
                $record[$get['db_config_index']][] = [
                    'before' => $data[strtolower('ParameterValue')],
                    'after' => $get['Value'],
                    'time' => date('Y-m-d H:i:s',time()),
                ];
                session('SG_User.record',$record);
                echo jejuu([
                    'ret' => C('succ_ret'),
                    'msg' => '修改成功',
                ]);
            }else{
                echo jejuu([
                    'ret' => C('fail_ret'),
                    'msg' => '修改失败',
                ]);
            }
        }else{
            $field = C('DB_CONFIG.'.$get['db_config_index'])['updown']?'prodlenm':'lenmm';
            try {
                $data = M('','','DB_CONFIG.'.$get['db_config_index'])->table('finish')->where(['id' => $get['id']])->field($field)->find();
                $r = M('','','DB_CONFIG.'.$get['db_config_index'])->table('finish')->where(['id' => $get['id']])->save([$field => $get['Value']]);
            } catch(\Exception $e){
                echo jejuu([
                    'ret' => C('fail_ret'),
                    'msg' => $e->getMessage(),
                ]);die;
            }
            if(!$data){
                echo jejuu([
                    'ret' => C('fail_ret'),
                    'msg' => 'id [ '.$get['id'].' ] 不存在',
                ]);die;
            }
            if($r){
                $record = session('SG_User.record');
                $record[$get['db_config_index']][] = [
                    'id' => $get['id'],
                    'before' => $data[$field],
                    'after' => $get['Value'],
                    'time' => date('Y-m-d H:i:s',time()),
                ];
                session('SG_User.record',$record);
                echo jejuu([
                    'ret' => C('succ_ret'),
                    'msg' => '修改成功',
                ]);
            }else{
                echo jejuu([
                    'ret' => C('fail_ret'),
                    'msg' => '修改失败',
                ]);
            }
        }
    }

    public function getRecord_api(){
        $get = I('get.');
        echo jejuu(array_reverse(session('SG_User.record')[$get['db_config_index']]));
    }

    public function cleanRecord_api(){
        $get = I('get.');
        $record = session('SG_User.record');
        $record[$get['db_config_index']] = NULL;
        session('SG_User.record',$record);
    }

}
