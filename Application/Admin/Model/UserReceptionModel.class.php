<?php
namespace Admin\Model;
use Admin\Model\LogModel;
/**
 * 前台用户组列表
 * 
 * @author xiaoyutab<xiaoyutab@qq.com>
 * @version v1.0.0
 * @copyright (c) 2017, xiaoyutab
 * @adtime 2017-07-17 14:08:42
 */
class UserReceptionModel extends \Common\Model\AllModel{
    
    /**
     * 获取用户组详情
     * 
     * @param number $id 要获取的用户组编号
     * @return boolean 查询到的信息
     * @author xiaoyutab<xiaoyutab@qq.com>
     * @version v1.0.0
     * @copyright (c) 2017, xiaoyutab
     * @adtime 2017-07-17 14:08:52
     */
    public function get_info($id = 0){
        if ($id <= 0) {
            return FALSE;
        }
        $info = $this->where(array('id' => $id))->find();
        return $info;
    }
    
    /**
     * 添加前台用户组
     * 
     * @param string $name 用户组名称
     * @return boolean 是否添加成功
     * @author xiaoyutab<xiaoyutab@qq.com>
     * @version v1.0.0
     * @copyright (c) 2017, xiaoyutab
     * @adtime 2017-07-17 14:09:03
     */
    public function create_info($name = ''){
        if(empty($name)){
            return FALSE;
        }
        $id = $this->add(array(
            'name' => $name,
            'status' => 99,
            'ad_time' => NOW_TIME,
        ));
        if($id){
            $log = new LogModel();
            $log->create_log('user_reception_group', $id);
        }
        return $id;
    }
    
    /**
     * 更新前台用户组
     * 
     * @param number $id 要更新的用户组名称
     * @param string $name 修改成什么样的值
     * @return boolean
     * @author xiaoyutab<xiaoyutab@qq.com>
     * @version v1.0.0
     * @copyright (c) 2017, xiaoyutab
     * @adtime 2017-07-17 14:09:19
     */
    public function save_info($id = 0,$name = ''){
        if ($id <= 0) {
            return FALSE;
        }
        $info = $this->get_info($id);
        $success = $this->where(array('status'=>array('neq',98),'id'=>$info['id']))->save(array('name'=>$name));
        if($success){
            $log = new LogModel();
            $log->update_log('user_reception_group', $info['id'], array('name'=>$name), array('name'=>$info['name']));
        }
        return $success;
    }

    /**
     * 删除前台用户组
     * 
     * @param number $id 要删除的用户组编号
     * @return boolean
     * @author xiaoyutab<xiaoyutab@qq.com>
     * @version v1.0.0
     * @copyright (c) 2017, xiaoyutab
     * @adtime 2017-07-14 18:39:34
     */
    public function delete_info($id = 0) {
        if ($id <= 0) {
            return FALSE;
        }
        $info = $this->get_info($id);
        $success = $this->where(array('id' => $id, 'status' => array('neq', 98)))->save(array(
            'status' => 98,
            'del_time' => NOW_TIME,
        ));
        if ($success) {
            $log = new LogModel();
            $log->delete_log('user_reception_group', $id, $info['status']);
        }
        return $success;
    }
    
    /**
     * 获取部门下的人员数量
     * @param intval $id 要获取的部门编号
     * @return string 获取到的值
     * @author xiaoyutab<xiaoyutab@qq.com>
     * @version v1.0.0
     * @copyright (c) 2017, xiaoyutab
     * @adtime 2017-7-17 14:41:07
     */
    public function get_group_count($id = 0){
        if($id <= 0){
            return 0;
        }
        $count = $this->where(array('group_id'=> intval($id)))->getCount();
        return intval($count);
    }
}