<?php
namespace Admin\Model;
use Admin\Model\LogModel;
/**
 * 接口主体信息添加-修改-删除操作
 * 
 * @author xiaoyutab<xiaoyutab@qq.com>
 * @version v1.0.0
 * @copyright (c) 2017, xiaoyutab
 * @adtime 2017-07-27 18:54:35
 */
class ProjectApiModel extends \Common\Model\AllModel{
    
    /**
     * 添加接口主体信息
     * 
     * @param number $pid 所属项目编号，不能小于0
     * @param string $name 接口名称，不能为空
     * @param string $href 接口相对路径地址
     * @param string $desc 接口描述
     * @param number $in_type 请求数据类型，1GET2POST
     * @param array $out_type 返回数据类型，1JSON，2XML，3JSONP
     * @return boolean|number 插入失败|成功插入的数据编号
     * @author xiaoyutab<xiaoyutab@qq.com>
     * @version v1.0.0
     * @copyright (c) 2017, xiaoyutab
     * @adtime 2017-07-27 18:56:33
     */
    public function create_info($pid = 0,$name = '',$href = '',$desc = '',$in_type = 1,$out_type = array('1')){
        if($pid <= 0){
            return FALSE;
        }
        if(empty($name)){
            return FALSE;
        }
        $id = $this->add(array(
            'p_id' => intval($pid),
            'a_name' => trim($name),
            'href' => trim($href),
            'desc' => trim($desc),
            'in_type' => intval($in_type),
            'out_type' => implode(',', $out_type),
            'ad_time' => NOW_TIME,
        ));
        if($id){
            $log = new LogModel();
            $log->create_log('project_api', $id);
            return $id;
        }
        return FALSE;
    }
    
    /**
     * 删除接口操作
     * 
     * @param Number $id 要删除的条目编号
     * @return boolean 是否删除成功
     * @author xiaoyutab<xiaoyutab@qq.com>
     * @version v1.0.0
     * @copyright (c) 2017, xiaoyutab
     * @adtime 2017-07-27 19:50:06
     */
    public function delete_info($id = 0){
        if($id <= 0){
            return FALSE;
        }
        $info = $this->where(array('id'=>$id,'status'=>array('neq',98)))->find();
        if(empty($info)){
            return FALSE;
        }
        if($this->setDelete($id)){
            $log = new LogModel();
            $log->delete_log('project_api', $id, $info['status']);
            return TRUE;
        }
        return FALSE;
    }
    
    /**
     * 修改接口基本信息
     * 
     * @param number $id
     * @param array $post
     * @param array $old
     * @return boolean 是否更新成功
     * @author xiaoyutab<xiaoyutab@qq.com>
     * @version v1.0.0
     * @copyright (c) 2017, xiaoyutab
     * @adtime 2017-07-27 20:46:20
     */
    public function update_info($id = 1,$post = array(),$old_info = array()){
        if($id <= 0){
            return FALSE;
        }
        if(!isset($post['version'])){
            return FALSE;
        }
        $old = array();
        $new = array();
        foreach($post as $k => $v){
            if($k == 'out_type'){
                $v = implode(',', $v);
            }
            if($k != 'version'){
                if($v != $old_info[$k]){
                    $old[$k] = $old_info[$k];
                    $new[$k] = $v;
                }
            }
        }
        if($post['version'] == 'version_smail'){
            // 更新小版本
            $old['version_smail'] = $old_info['version_smail'];
            $new['version_smail'] = $old_info['version_smail'] + 1;
        } else if($post['version'] == 'version_middle'){
            // 更新中版本
            $old['version_smail'] = $old_info['version_smail'];
            $old['version_middle'] = $old_info['version_middle'];
            $new['version_middle'] = $old_info['version_middle'] + 1;
            $new['version_smail'] = 0;
        } else {
            // 更新大版本
            $old['version_smail'] = $old_info['version_smail'];
            $old['version_big'] = $old_info['version_big'];
            $old['version_middle'] = $old_info['version_middle'];
            $new['version_big'] = $old_info['version_big'] + 1;
            $new['version_middle'] = 0;
            $new['version_smail'] = 0;
        }
        if($this->where(array('id'=>$id,'status'=>array('neq',98)))->save($new)){
            $log = new LogModel();
            $log->update_log('project_api', $id, $new, $old);
            return TRUE;
        }
        return FALSE;
    }
}