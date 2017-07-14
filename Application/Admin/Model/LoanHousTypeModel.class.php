<?php
namespace Admin\Model;
use Admin\Model\LogModel;
/**
 * 房产状态管理
 * 
 * @author xiaoyutab<xiaoyutab@qq.com>
 * @version v1.0.0
 * @copyright (c) 2017, xiaoyutab
 * @adtime 2017-07-14 18:21:59
 */
class LoanHousTypeModel extends \Common\Model\AllModel{
    
    /**
     * 获取房产状态详情
     * 
     * @param number $id 要获取的抵押顺位详情编号
     * @return boolean 查询到的信息
     * @author xiaoyutab<xiaoyutab@qq.com>
     * @version v1.0.0
     * @copyright (c) 2017, xiaoyutab
     * @adtime 2017-07-14 18:28:12
     */
    public function get_info($id = 0){
        if ($id <= 0) {
            return FALSE;
        }
        $info = $this->where(array('id' => $id))->find();
        return $info;
    }
    
    /**
     * 添加房产状态
     * 
     * @param string $name 抵押顺位名称
     * @return boolean 是否添加成功
     * @author xiaoyutab<xiaoyutab@qq.com>
     * @version v1.0.0
     * @copyright (c) 2017, xiaoyutab
     * @adtime 2017-07-14 18:28:17
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
            $log->create_log('loan_hous_type', $id);
        }
        return $id;
    }
    
    /**
     * 更新房产状态
     * 
     * @param number $id 要更新的抵押顺位名称
     * @param string $name 修改成什么样的值
     * @return boolean
     * @author xiaoyutab<xiaoyutab@qq.com>
     * @version v1.0.0
     * @copyright (c) 2017, xiaoyutab
     * @adtime 2017-07-14 18:34:41
     */
    public function save_info($id = 0,$name = ''){
        if ($id <= 0) {
            return FALSE;
        }
        $info = $this->get_info($id);
        $success = $this->where(array('status'=>array('neq',98),'id'=>$info['id']))->save(array('name'=>$name));
        if($success){
            $log = new LogModel();
            $log->update_log('loan_hous_type', $info['id'], array('name'=>$name), array('name'=>$info['name']));
        }
        return $success;
    }

    /**
     * 删除房产状态
     * 
     * @param number $id 要删除的抵押顺位信息编号
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
            $log->delete_log('loan_hous_type', $id, $info['status']);
        }
        return $success;
    }
}