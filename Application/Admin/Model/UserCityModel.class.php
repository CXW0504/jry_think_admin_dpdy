<?php
namespace Admin\Model;
use Admin\Model\LogModel;
/**
 * 用户城市管理模型
 *      管理所有的用户可选择城市列表
 * 
 * @author xiaoyutab<xiaoyutab@qq.com>
 * @version v1.0.0
 * @copyright (c) 2017, xiaoyutab
 * @adtime 2017-08-22 15:48:02
 */
class UserCityModel extends \Common\Model\AllModel{
    /**
     * 插入一条数据
     * 
     * @param array $data 插入的数据
     * @return boolean 是否添加成功
     * @author xiaoyutab<xiaoyutab@qq.com>
     * @version v1.0.0
     * @copyright (c) 2017, xiaoyutab
     * @adtime 2017-08-22 15:50:18
     */
    public function create_user_city($data = array()){
        if(!isset($data['id'])){
            return FALSE;
        }
        if($this->add($data)){
            $log = new LogModel();
            $log->create_log('user_city', $data['id']);
            return TRUE;
        }
        return FALSE;
    }
    
    /**
     * 修改条目信息
     * 
     * @param number $id 修改的数据编号
     * @param array $data 用户提交过来的值
     * @return boolean 是否修改成功
     * @author xiaoyutab<xiaoyutab@qq.com>
     * @version v1.0.0
     * @copyright (c) 2017, xiaoyutab
     * @adtime 2017-08-23 15:26:45
     */
    public function save_user_city($id = 0,$data = array()){
        if($id <= 0){
            return FALSE;
        }
        if(!isset($data['id'])){
            return FALSE;
        }
        $info = $this->where(array('id'=>$id,'commend' => array('neq',2)))->find();
        if($this->where(array('id'=>$id,'commend' => array('neq',2)))->save($data)){
            $log = new LogModel();
            $log->update_log('user_city',$id,$data,$info);
            return TRUE;
        }
        return FALSE;
    }
    
    /**
     * 删除数据信息[真实删除]
     * 
     * @param number $id 要删除的条目编号
     * @return boolean 是否删除成功
     * @author xiaoyutab<xiaoyutab@qq.com>
     * @version v1.0.0
     * @copyright (c) 2017, xiaoyutab
     * @adtime 2017-08-23 15:37:33
     */
    public function delete_user_city($id = 0){
        if($id <= 0){
            return FALSE;
        }
        $info = $this->where(array('id'=>$id))->find();
        if($this->where(array('id'=>$id))->delete()){
            $log = new LogModel();
            $log->delete_log_actual('user_city',$info);
            return TRUE;
        }
        return FALSE;
    }
}