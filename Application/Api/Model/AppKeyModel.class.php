<?php
namespace Api\Model;
use Think\Model;

/**
 * App应用授权信息查询类，作用为查询应用的一些信息
 * 
 * @author xiaoyutab<xiaoyutab@qq.com>
 * @adtime 2017-3-6 15:44:10
 * @version v1.0.0
 * @copyright (c) 2017, xiaoyutab
 */
class AppKeyModel extends Model{
    /**
     * 构造函数类
     * 作用为设置当前操作表名
     * 当前操作表：app_key，与类名相同，暂不设置
     * 
     * @author xiaoyutab<xiaoyutab@qq.com>
     * @version v1.0.0
     * @copyright (c) 2017, xiaoyutab
     * @adtime 2017-3-6 15:44:30
     */
    public function __construct() {
        parent::__construct('authorize_app_key');
    }
    
    /**
     * 查询应用是否授权
     * 
     * @param intval   $id  要查询的应用的编号
     * @param string   $do  要查询的网站域名
     * @return boolean      该域名是否已授权
     * @version v1.0.0
     * @author xiaoyutab<xiaoyutab@qq.com>
     * @copyright (c) 2017, xiaoyutab
     * @adtime 2017-3-6 15:44:34
     */
    public function isAuthorized($id = 1,$do = 'localhost'){
        if($id < 1){
            return FALSE;
        }
        $data = $this->field(array('id','status','end_time','go_time'))->where(array('aid'=>$id,'doman'=>$do,'status'=>array('neq',98)))->select();
        if(empty($data)){
            $this->add(array('aid'=>$id,'doman'=>$do,'ad_time'=>time(),'status'=>1));
            return FALSE;
        }
        // 如果查询出了结果，就判断下是否是已授权
        foreach($data as $v){
            // 如果授权状态是99，即正常授权
            if($v['status'] == 99){
                // 授权已经开始
                if($v['go_time'] < time()){
                    // 在授权期内
                    if($v['end_time'] > time()){
                        return TRUE;
                    }
                }
            }
        }
        return FALSE;
    }
}