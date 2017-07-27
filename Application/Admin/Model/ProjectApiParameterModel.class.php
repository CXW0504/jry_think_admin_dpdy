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
}