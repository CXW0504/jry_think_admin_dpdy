<?php

namespace Admin\Model;

use Common\Model\AllModel;
use Admin\Model\LogModel;

/**
 * 标签管理模型
 * 
 * @author xiaoyutab<xiaoyutab@qq.com>
 * @version v1.0.0
 * @copyright (c) 2017, xiaoyutab
 * @adtime 2017-08-10 17:25:19
 */
class TagModel extends AllModel {

    /**
     * 添加文章标签信息
     * 
     * @param string $name 添加时间
     * @param intval $open_type 显示类型
     * @param intval $type 标签类型
     * @return boolean
     * @author xiaoyutab<xiaoyutab@qq.com>
     * @version v1.0.0
     * @copyright (c) 2017, xiaoyutab
     * @adtime 2017-08-10 17:26:29
     */
    public function create_info($name = '', $open_type = 1, $type = 1) {
        if (empty($name)) {
            return FALSE;
        }
        $success = $this->add(array(
            'name' => trim($name),
            'type' => intval($type),
            'open_type' => intval($open_type),
            'ad_time' => NOW_TIME,
            'status' => 99,
            'del_time' => 0,
        ));
        if ($success) {
            $log = new LogModel();
            $log->create_log('tag', $success);
            return TRUE;
        }
        return FALSE;
    }
    
    /**
     * 删除标签
     * 
     * @param Number $id 要删除的条目编号
     * @return boolean 是否删除成功
     * @author xiaoyutab<xiaoyutab@qq.com>
     * @version v1.0.0
     * @copyright (c) 2017, xiaoyutab
     * @adtime 2017-08-10 18:15:18
     */
    public function delete_info($id = 1){
        if($id <= 0){
            return FALSE;
        }
        $info = $this->where(array('id'=>$id,'status'=>array('neq',98)))->find();
        if(empty($info)){
            return FALSE;
        }
        if($this->setDelete($id)){
            $log = new LogModel();
            $log->delete_log('tag', $id, $info['status']);
            return TRUE;
        }
        return FALSE;
    }
    
    /**
     * 修改接口参数信息
     * 
     * @param array $post 用户传入的修改后的参数
     * @param array $info 用户接口参数原来的值
     * @return boolean是否修改成功
     * @author xiaoyutab<xiaoyutab@qq.com>
     * @version v1.0.0
     * @copyright (c) 2017, xiaoyutab
     * @adtime 2017-08-10 18:23:08
     */
    public function save_info($post = array(),$info = array()){
        if(empty($post)){
            return FALSE;
        }
        if(empty($post['name'])){
            return FALSE;
        }
        $old_arr = array();
        $new_arr = array();
        foreach($post as $k => $v){
            if($info[$k] != $v){
                $old_arr[$k] = $info[$k];
                $new_arr[$k] = $v;
            }
        }
        if(empty($new_arr)){
            return FALSE;
        }
        if($this->where(array('id'=>$info['id']))->save($new_arr)){
            $log = new LogModel();
            $log->update_log('tag', $info['id'], $new_arr, $old_arr);
            return TRUE;
        }
        return FALSE;
    }
}
