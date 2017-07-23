<?php
namespace Admin\Model;
use Common\Model\AllModel;
use Admin\Model\LogModel;

/**
 * 考勤管理模型
 * 
 * @author xiaoyutab<xiaoyutab@qq.com>
 * @version v1.0.0
 * @copyright (c) 2017, xiaoyutab
 * @adtime 2017-07-23 18:16:37
 */
class TimeRuleModel extends AllModel{
    /**
     * 获取考勤情况信息
     * 
     * @param number $id 要获取的用户组编号
     * @return boolean 查询到的信息
     * @author xiaoyutab<xiaoyutab@qq.com>
     * @version v1.0.0
     * @copyright (c) 2017, xiaoyutab
     * @adtime 2017-07-23 18:39:12
     */
    public function get_info($id = 0){
        if ($id <= 0) {
            return FALSE;
        }
        $info = $this->where(array('id' => $id))->find();
        if(empty($info)){
        	return FALSE;
        }
        $info['morning_time'] = date('H:i',strtotime($info['morning_time']));
        $info['afternoon_time'] = date('H:i',strtotime($info['afternoon_time']));
        return $info;
    }

    /**
     * 添加考勤情况信息
     * 
     * @param array $post 传入的POST数据列表
     * @return boolean 是否添加成功
     * @author xiaoyutab<xiaoyutab@qq.com>
     * @version v1.0.0
     * @copyright (c) 2017, xiaoyutab
     * @adtime 2017-07-23 18:16:41
     */
	public function create_time_card($post = array()){
		// 规则名称为必填选项
        if(!isset($post['name'])){
            return FALSE;
        }
        // 考勤坐标点为必填选项
        if(!isset($post['coordinate'])){
            return FALSE;
        }
        // 有效距离默认为200米
        if(empty($post['distance'])){
            $post['distance'] = '200';
        }
        $post['ad_time'] = NOW_TIME;
        $ids = $this->add($post);
        if($ids){
            $log = new LogModel();
            $log->create_log('time_rule', $ids);
        }
        return $ids;
	}
    
    /**
     * 删除考勤情况信息
     * 
     * @param number $id 要删除的考勤情况信息编号
     * @return boolean 是否删除成功
     * @author xiaoyutab<xiaoyutab@qq.com>
     * @version v1.0.2
     * @copyright (c) 2017, xiaoyutab
     * @adtime 2017-07-23 18:40:17
     */
	public function delete_timecard($id = 0){
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
            $log->delete_log('time_rule', $id, $info['status']);
        }
        return $success;
	}

    /**
     * 修改考勤情况信息
     * 
     * @param number $id 要修改的考勤情况信息编号
     * @param array $post 更新后的考勤配置表
     * @return boolean 是否修改成功
     * @author xiaoyutab<xiaoyutab@qq.com>
     * @version v1.0.2
     * @copyright (c) 2017, xiaoyutab
     * @adtime 2017-07-23 18:52:22
     */
	public function save_timecard($id = 0,$post = array()){
        if ($id <= 0) {
            return FALSE;
        }
        if(empty($post)){
            return FALSE;
        }
        $info = $this->get_info($id);
        $save_arr = array();
        $old_arr = array();
        foreach($info as $k => $v){
	        if(isset($post[$k]) && $post[$k] != $v){
	            $save_arr[$k] = $post[$k];
	            $old_arr[$k] = $v;
	        }
        }
        $success = $this->where(array('status'=>array('neq',98),'id'=>$info['id']))->save($save_arr);
        if($success){
            $log = new LogModel();
            $log->update_log('time_rule', $info['id'], $save_arr, $old_arr);
        }
        return $success;
	}
}