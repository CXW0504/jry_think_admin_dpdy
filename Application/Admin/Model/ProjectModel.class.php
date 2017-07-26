<?php

namespace Admin\Model;
use Admin\Model\LogModel;

/**
 * 接口文档的项目管理模型
 * 
 * @author xiaoyutab<xiaoyutab@qq.com>
 * @version v1.0.0
 * @copyright (c) 2017, xiaoyutab
 * @adtime 2017-07-26 18:53:18
 */
class ProjectModel extends \Common\Model\AllModel {

    /**
     * 添加项目数据
     * 
     * @param array $data 要添加的数据
     * @return boolean 是否添加成功
     * @author xiaoyutab<xiaoyutab@qq.com>
     * @version v1.0.0
     * @copyright (c) 2017, xiaoyutab
     * @adtime 2017-07-26 18:54:01
     */
    public function create_info($data = array()) {
        if (empty($data)) {
            return FALSE;
        }
        if(empty($data['name'])){
            // 如果项目名称为空则返回失败
            return FALSE;
        }
        $data['ad_time'] = NOW_TIME;
        $success = $this->add($data);
        if($success){
            $log = new LogModel();
            $log->create_log('project', $success);
        }
        return (bool)$success;
    }
    
    /**
     * 修改项目信息
     * 
     * @param Number $id 要修改的编号
     * @param array $info 直接从数据库中查询出来的值
     * @param array $post 用户从表单中提交过来的值
     * @return boolean 是否修改成功
     * @author xiaoyutab<xiaoyutab@qq.com>
     * @version v1.0.0
     * @copyright (c) 2017, xiaoyutab
     * @adtime 2017-07-26 23:37:28
     */
    public function update_info($id = 1,$info = array(),$post = array()){
        if($id <= 0){
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
        $success = $this->where(array('id'=>$id,'status'=>array('neq',98)))->save($new_arr);
        if($success){
            $log = new LogModel();
            $log->update_log('project', $id, $new_arr, $old_arr);
        }
        return (bool)$success;
    }
    
    /**
     * 删除项目操作
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
            $log->delete_log('project', $id, $info['status']);
            return TRUE;
        }
        return FALSE;
    }

}
