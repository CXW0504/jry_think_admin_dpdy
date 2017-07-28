<?php
namespace Admin\Model;
use Admin\Model\LogModel;
/**
 * 接口参数处理操作
 * 
 * @author xiaoyutab<xiaoyutab@qq.com>
 * @version v1.0.0
 * @copyright (c) 2017, xiaoyutab
 * @adtime 2017-07-27 19:35:55
 */

class ProjectApiParameterModel extends \Common\Model\AllModel{
    /**
     * 整理数据并插入到数据库中
     * 
     * @param number $id 接口编号
     * @param array $in 输入参数
     * @param array $out 输出参数
     * @return boolean 是否操作成功
     * @author xiaoyutab<xiaoyutab@qq.com>
     * @version v1.0.0
     * @copyright (c) 2017, xiaoyutab
     * @adtime 2017-07-27 19:38:42
     */
    public function update_info($id = 0,$in = array(),$out = array()){
        if($id <= 0){
            return FALSE;
        }
        $list_in = $this->where(array('a_id' => $id,'type' => 1))->find();
        $_list_in = array();
        foreach($list_in as $v){
            $_list_in[] = $v['name'];
        }
        $list_out = $this->where(array('a_id' => $id,'type' => 2))->find();
        $_list_out = array();
        foreach($list_out as $v){
            $_list_out[] = $v['name'];
        }
        $add = array();// 添加的数据
        $del = array();// 删除的数据
        $upd = array();// 修改的数据
        $_zl_in = array();// 修改的数据
        $_zl_out = array();// 修改的数据
        foreach($in['name'] as $k => $v){
            if(in_array($v, $_list_in)){
                // 如果存在数组中
                $upd[] = array(
                    'type' => 1,
                    'name' => $v,
                    'desc' => $in['desc'][$k],
                    'max_length' => $in['max_length'][$k],
                    'c_type' => $in['c_type'][$k],
                    'is_must' => $in['is_must'][$k],
                    'msg' => $in['msg'][$k],
                );
            } else {
                $add[] = array(
                    'type' => 1,
                    'name' => $v,
                    'a_id' => $id,
                    'desc' => $in['desc'][$k],
                    'max_length' => $in['max_length'][$k],
                    'c_type' => $in['c_type'][$k],
                    'is_must' => $in['is_must'][$k],
                    'msg' => $in['msg'][$k],
                    'ad_time' => NOW_TIME,
                );
            }
            $_zl_in[] = $v;
        }
        foreach($list_in as $v){
            if(!in_array($v['name'], $_zl_in)){
                $del[] = $v['id'];
            }
        }
        ///////////////////////////////
        foreach($out['name'] as $k => $v){
            if(in_array($v, $_list_out)){
                // 如果存在数组中
                $upd[] = array(
                    'type' => 2,
                    'name' => $v,
                    'desc' => $out['desc'][$k],
                    'max_length' => $out['max_length'][$k],
                    'c_type' => $out['c_type'][$k],
                    'is_must' => $out['is_must'][$k],
                    'msg' => $out['msg'][$k],
                );
            } else {
                $add[] = array(
                    'type' => 2,
                    'name' => $v,
                    'a_id' => $id,
                    'desc' => $out['desc'][$k],
                    'max_length' => $out['max_length'][$k],
                    'c_type' => $out['c_type'][$k],
                    'is_must' => $out['is_must'][$k],
                    'msg' => $out['msg'][$k],
                    'ad_time' => NOW_TIME,
                );
            }
            $_zl_out[] = $v;
        }
        foreach($list_out as $v){
            if(!in_array($v['name'], $_zl_out)){
                $del[] = $v['id'];
            }
        }
        $this->add_suc($add);
        $this->update_suc($id,$upd);
        $this->del_suc($id,$del);
        return TRUE;
    }
    
    /**
     * 批量添加接口参数
     * @param array $adds 添加的数组操作
     * @return boolean
     * @author xiaoyutab<xiaoyutab@qq.com>
     * @version v1.0.0
     * @copyright (c) 2017, xiaoyutab
     * @adtime 2017-07-27 19:37:47
     */
    public function add_suc($adds = array()){
        $log = new LogModel();
        if(is_array($adds)){
            foreach($adds as $v){
                $success = $this->add($v);
                if($success){
                    $log->create_log('project_api_parameter', $success);
                }
            }
            return TRUE;
        }
        return FALSE;
    }
    
    /**
     * 批量修改操作
     * 
     * @param number $id 接口编号
     * @param array $data 批量操作的数据
     * @return boolean 是否成功
     * @author xiaoyutab<xiaoyutab@qq.com>
     * @version v1.0.0
     * @copyright (c) 2017, xiaoyutab
     * @adtime 2017-07-27 19:36:47
     */
    public function update_suc($id = 0,$data = array()){
        $log = new LogModel();
        if(is_array($adds)){
            $log = new LogModel();
            foreach($adds as $v){
                $info = $this->where(array('a_id'=>$id,'type'=>$v['type'],'name'=>$v['name']))->find();
                $old_arr = array();
                $new_arr = array();
                foreach($v as $ks => $vs){
                    if($info[$ks] != $vs){
                        $old_arr[$ks] = $info[$ks];
                        $new_arr[$ks] = $vs;
                    }
                }
                $success = $this->where(array('id'=>$info['id'],'status'=>array('neq',98)))->save($new_arr);
                if($success){
                    $log->update_log('project_api_parameter', $info['id'], $new_arr, $old_arr);
                }
            }
            return TRUE;
        }
        return FALSE;
    }
    
    /**
     * 批量删除操作
     * 
     * @param number $id 接口编号
     * @param array $data 要删除的编号数组
     * @return boolean
     * @author xiaoyutab<xiaoyutab@qq.com>
     * @version v1.0.0
     * @copyright (c) 2017, xiaoyutab
     * @adtime 2017-07-27 19:36:09
     */
    public function del_suc($id = 0,$data = array()){
        $log = new LogModel();
        if(is_array($data)){
            $log = new LogModel();
            foreach($data as $v){
                $success = $this->setDelete($v);
                if($success){
                    $log->delete_log('project_api_parameter', $v);
                }
            }
            return TRUE;
        }
        return FALSE;
    }
    
    /**
     * 添加接口中的参数
     * 
     * @param number $a_id
     * @param number $type
     * @param string $name
     * @param string $desc
     * @param number $max_length
     * @param number $c_type
     * @param number $is_must
     * @param string $msg
     * @return boolean 是否添加成功
     * @author xiaoyutab<xiaoyutab@qq.com>
     * @version v1.0.0
     * @copyright (c) 2017, xiaoyutab
     * @adtime 2017-07-28 17:18:21
     */
    public function create_info($a_id = 0,$type = 1,$name = '',$desc = '',$max_length = '',$c_type = '',$is_must = '',$msg = ''){
        // 接口编号不能小于0
        if($a_id <= 0){
            return FALSE;
        }
        // 接口类型只能是1和2
        if($type > 2 || $type < 1){
            return FALSE;
        }
        $success = $this->add(array(
            'a_id' => intval($a_id),
            'type' => intval($type),
            'name' => trim($name),
            'desc' => trim($desc),
            'max_length' => intval($max_length),
            'c_type' => intval($c_type),
            'is_must' => intval($is_must),
            'msg' => trim($msg),
            'ad_time' => NOW_TIME,
        ));
        if($success){
            $log = new LogModel();
            $log->create_log('project_api_parameter', $success);
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
     * @adtime 2017-07-28 17:47:48
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
            $log->update_log('project_api_parameter', $info['id'], $new_arr, $old_arr);
            return TRUE;
        }
        return FALSE;
    }
    
    /**
     * 批量删除操作
     * 
     * @param number $id 接口编号
     * @return boolean
     * @author xiaoyutab<xiaoyutab@qq.com>
     * @version v1.0.0
     * @copyright (c) 2017, xiaoyutab
     * @adtime 2017-07-27 19:36:09
     */
    public function del_info($id = 0){
        $success = $this->setDelete($id);
        if($success){
            $log = new LogModel();
            $log->delete_log('project_api_parameter', $id);
            return TRUE;
        }
        return FALSE;
    }
}