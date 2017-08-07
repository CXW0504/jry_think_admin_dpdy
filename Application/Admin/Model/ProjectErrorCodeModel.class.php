<?php
namespace Admin\Model;
use Common\Model\AllModel;
use Admin\Model\LogModel;

/**
 * 项目的错误代码列表页面
 * 
 * @author xiaoyutab<xiaoyutab@qq.com>
 * @version v1.0.0
 * @copyright (c) 2017, xiaoyutab
 * @adtime 2017-08-07 17:09:41
 */
class ProjectErrorCodeModel extends AllModel{
    /**
     * 添加项目的错误代码
     * 
     * @param array $pid 要添加的错误代码属于哪个项目
     * @param array $data 要添加的数据
     * @return boolean 是否添加成功
     * @author xiaoyutab<xiaoyutab@qq.com>
     * @version v1.0.0
     * @copyright (c) 2017, xiaoyutab
     * @adtime 2017-08-07 17:25:20
     */
    public function create_info($pid = 0,$data = array()){
        if (empty($data)) {
            return FALSE;
        }
        if(empty($data['name'])){
            // 如果项目名称为空则返回失败
            return FALSE;
        }
        $data['ad_time'] = NOW_TIME;
        $data['status'] = 99;
        $data['pid'] = intval($pid);
        $success = $this->add($data);
        if($success){
            $log = new LogModel();
            $log->create_log('project_error_code', $success);
        }
        return (bool)$success;
    }
    
    /**
     * 修改错误代码
     * 
     * @param array $post 用户传入的修改后的参数
     * @param array $info 用户接口参数原来的值
     * @return boolean是否修改成功
     * @author xiaoyutab<xiaoyutab@qq.com>
     * @version v1.0.0
     * @copyright (c) 2017, xiaoyutab
     * @adtime 2017-08-07 17:45:35
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
            $log->update_log('project_error_code', $info['id'], $new_arr, $old_arr);
            return TRUE;
        }
        return FALSE;
    }
    
    /**
     * 删除错误代码
     * 
     * @param Number $id 要删除的条目编号
     * @return boolean 是否删除成功
     * @author xiaoyutab<xiaoyutab@qq.com>
     * @version v1.0.0
     * @copyright (c) 2017, xiaoyutab
     * @adtime 2017-07-26 23:37:28
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
            $log->delete_log('project_error_code', $id, $info['status']);
            return TRUE;
        }
        return FALSE;
    }
}