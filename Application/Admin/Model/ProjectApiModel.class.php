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
}