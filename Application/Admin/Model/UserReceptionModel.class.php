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
     * 添加前台用户
     * 
     * @param string $name 用户组名称
     * @return boolean 是否添加成功
     * @author xiaoyutab<xiaoyutab@qq.com>
     * @version v1.0.0
     * @copyright (c) 2017, xiaoyutab
     * @adtime 2017-07-17 14:09:03
     */
    public function create_info($data = array()){
        if(empty($data)){
            return FALSE;
        }
        // 如果未设置username、password、type则返回失败
        if(!isset($data['username'],$data['password'],$data['type'])){
            return FALSE;
        }
        $data['status'] = 99;
        $data['ad_time'] = NOW_TIME;
        $data['rand_code'] = random($hash, 6);
        // 密码先MD5以下然后再和随机码进行一起加密
        // 如果要做APP接口的话则需要先在客户端进行MD5加密，然后服务器再进行计算保存
        $data['password'] = pass(md5($data['password']), $data['rand_code']);
        $id = $this->add($data);
        if($id){
            $log = new LogModel();
            $log->create_log('user_reception', $id);
        }
        return $id;
    }
    
    /**
     * 更新前台用户
     * 
     * @param number $id 要更新的用户组名称
     * @param array $post 获取到的POST的值
     * @return boolean
     * @author xiaoyutab<xiaoyutab@qq.com>
     * @version v1.0.0
     * @copyright (c) 2017, xiaoyutab
     * @adtime 2017-07-17 14:09:19
     */
    public function save_info($id = 0,$post = array()){
        if ($id <= 0) {
            return FALSE;
        }
        if(empty($post)){
            return FALSE;
        }
        $info = $this->get_info($id);
        $save_arr = array();
        $old_arr = array();
        if($name != $info['name']){
            $save_arr['name'] = $name;
            $old_arr['name'] = $info['name'];
        }
        foreach($info as $k => $v){
            if($k == 'password'){
                if(!empty($post['password'])){
                    // 如果是修改密码则同时修改两个字段
                    $save_arr['rand_code'] = random('', 6);
                    $old_arr['rand_code'] = $v['rand_code'];
                    $save_arr['password'] = pass(md5($post['password']), $save_arr['rand_code']);
                    $old_arr['password'] = $v['password'];
                }
            } else {
                if(isset($post[$k]) && $v != $post[$k]){
                    $save_arr[$k] = $post[$k];
                    $old_arr[$k] = $v;
                }
            }
        }
        $success = $this->where(array('status'=>array('neq',98),'id'=>$info['id']))->save($save_arr);
        if($success){
            $log = new LogModel();
            $log->update_log('user_reception', $info['id'], $save_arr, $old_arr);
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
            $log->delete_log('user_reception', $id, $info['status']);
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
    
    /**
     * 检测某字段的值是否可用
     * @param 字段名 $name
     * @param 字段值 $value
     * @return boolean
     * @author xiaoyutab<xiaoyutab@qq.com>
     * @version v1.0.0
     * @copyright (c) 2017, xiaoyutab
     * @adtime 2017-7-17 18:23:23
     */
    public function name_value($name = '',$value = ''){
        // 如果传入的值是空则可用
        if($name == '' || $value == ''){
            return TRUE;
        }
        // 如果传入的字段不在唯一里面，则可用
        if(!in_array($name, array('username','phone','email'))){
            return TRUE;
        }
        $count = $this->where(array($name=>$value))->count();
        if($count > 0){
            return FALSE;
        }
        return TRUE;
    }
}