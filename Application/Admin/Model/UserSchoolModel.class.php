<?php

namespace Admin\Model;

use Admin\Model\LogModel;

/**
 * 获取用户学校相关操作模型
 * 
 * @author xiaoyutab<xiaoyutab@qq.com>
 * @version v1.0.0
 * @copyright (c) 2017, xiaoyutab
 * @adtime 2017-08-03 14:08:53
 */
class UserSchoolModel extends \Common\Model\AllModel {

    /**
     * 创建用户学校城市信息
     * 
     * @param string $name 学校所在省市名称
     * @return boolean
     * @author xiaoyutab<xiaoyutab@qq.com>
     * @version v1.0.0
     * @copyright (c) 2017, xiaoyutab
     * @adtime 2017-08-03 14:09:20
     */
    public function create_user_office_data($name = '') {
        if (empty($name)) {
            return FALSE;
        }
        $success = $this->add(array(
            'name' => trim($name),
            'fid' => 0,
            'ad_time' => NOW_TIME,
            'status' => 99,
            'del_time' => 0,
        ));
        if ($success) {
            $log = new LogModel();
            $log->create_log('user_school', $success);
            return TRUE;
        }
        return FALSE;
    }

    /**
     * 修改用户学校所在地区操作
     * 
     * @param number $id 修改的条目ID
     * @param string $name 学校所在省市名称
     * @return boolean
     * @author xiaoyutab<xiaoyutab@qq.com>
     * @version v1.0.0
     * @copyright (c) 2017, xiaoyutab
     * @adtime 2017-08-03 19:06:19
     */
    public function save_user_school_city($id = 0,$name = ''){
        if (empty($name)) {
            return FALSE;
        }
        if($id <= 0){
            return FALSE;
        }
        $info = $this->where(array('id'=>$id,'status'=>array('neq',98)))->find();
        if(empty($info)){
            return FALSE;
        }
        $success = $this->where(array('id'=>$id,'status'=>array('neq',98)))->save(array(
            'name' => $name
        ));
        if($success){
            $log = new LogModel();
            $log->update_log('user_school', $id, array('name'=>$name), array('name'=>$info['name']));
            return TRUE;
        }
        return FALSE;
    }
    
    /**
     * 删除用户学校地区
     * 
     * @param number $id 要删除的条目编号
     * @return boolean 是否删除成功
     * @author xiaoyutab<xiaoyutab@qq.com>
     * @version v1.0.0
     * @copyright (c) 2017, xiaoyutab
     * @adtime 2017-08-03 19:11:21
     */
    public function delete_user_school_city($id = 0){
        if($id <= 0){
            return FALSE;
        }
        $info = $this->where(array('id'=>$id,'status'=>array('neq',98)))->find();
        if(empty($info)){
            return FALSE;
        }
        if($this->setDelete($id)){
            $log = new LogModel();
            $log->delete_log('user_school', $id, $info['status']);
            return TRUE;
        }
        return FALSE;
    }

    /**
     * 创建用户学校信息
     * 
     * @param string $name 学校所名称
     * @return boolean
     * @author xiaoyutab<xiaoyutab@qq.com>
     * @version v1.0.0
     * @copyright (c) 2017, xiaoyutab
     * @adtime 2017-08-04 15:24:14
     */
    public function create_user_school_name($id = 0,$name = ''){
        if($id <= 0){
            return FALSE;
        }
        if(empty($name)){
            return FALSE;
        }
        $success = $this->add(array(
            'name' => $name,
            'fid' => $id,
            'ad_time' => NOW_TIME,
            'status' => 99,
            'del_time' => 0
        ));
        if($success){
            $log = new LogModel();
            $log->create_log('user_school', $success);
            return TRUE;
        }
        return FALSE;
    }

    /**
     * 修改用户学校信息
     * 
     * @param string $name 学校所名称
     * @return boolean
     * @author xiaoyutab<xiaoyutab@qq.com>
     * @version v1.0.0
     * @copyright (c) 2017, xiaoyutab
     * @adtime 2017-08-04 15:24:14
     */
    public function update_user_school_name($id = 0,$name = ''){
        if($id <= 0){
            return FALSE;
        }
        if(empty($name)){
            return FALSE;
        }
        $info = $this->where(array('id'=>$id,'status'=>array('neq',98)))->find();
        if(empty($info)){
            return FALSE;
        }
        $success = $this->where(array('id'=>$id,'status'=>array('neq',98)))->save(array('name' => $name));
        if($success){
            $log = new LogModel();
            $log->update_log('user_school', $id, array('name' => $name), array('name' => $info['name']));
            return TRUE;
        }
        return FALSE;
    }
}
