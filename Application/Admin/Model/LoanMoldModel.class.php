<?php

namespace Admin\Model;

use Admin\Model\LogModel;

/**
 * 抵押顺位模型[下拉选项操作]
 * 
 * @author xiaoyutab<xiaoyutab@qq.com>
 * @version v1.0.0
 * @copyright (c) 2017, xiaoyutab
 * @adtime 2017-07-14 16:52:57
 */
class LoanMoldModel extends \Common\Model\AllModel {

    /**
     * 删除抵押顺位信息
     * 
     * @param number $id 要删除的抵押顺位信息编号
     * @return boolean
     * @author xiaoyutab<xiaoyutab@qq.com>
     * @version v1.0.1
     * @copyright (c) 2017, xiaoyutab
     * @adtime 2017-07-14 17:02:54
     */
    public function delete_loan_mold($id = 0) {
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
            $log->delete_log('loan_mold', $id, $info['status']);
        }
        return $success;
    }
    
    /**
     * 获取抵押顺位详情信息
     * 
     * @param number $id 要获取的抵押顺位详情编号
     * @return boolean 查询到的信息
     * @author xiaoyutab<xiaoyutab@qq.com>
     * @version v1.0.0
     * @copyright (c) 2017, xiaoyutab
     * @adtime 2017-07-14 17:07:04
     */
    public function get_info($id = 0){
        if ($id <= 0) {
            return FALSE;
        }
        $info = $this->where(array('id' => $id))->find();
        return $info;
    }
    
    /**
     * 更新抵押顺位名称操作
     * 
     * @param number $id 要更新的抵押顺位名称
     * @param string $name 修改成什么样的值
     * @return boolean
     * @author xiaoyutab<xiaoyutab@qq.com>
     * @version v1.0.0
     * @copyright (c) 2017, xiaoyutab
     * @adtime 2017-07-14 17:31:31
     */
    public function save_loan_mold($id = 0,$name = ''){
        if ($id <= 0) {
            return FALSE;
        }
        $info = $this->get_info($id);
        $success = $this->where(array('status'=>array('neq',98),'id'=>$info['id']))->save(array('name'=>$name));
        if($success){
            $log = new LogModel();
            $log->update_log('loan_mold', $info['id'], array('name'=>$name), array('name'=>$info['name']));
        }
        return $success;
    }
    
    /**
     * 添加抵押顺位操作
     * 
     * @param string $name 抵押顺位名称
     * @return boolean 是否添加成功
     * @author xiaoyutab<xiaoyutab@qq.com>
     * @version v1.0.0
     * @copyright (c) 2017, xiaoyutab
     * @adtime 2017-07-14 17:38:33
     */
    public function create_loan_mold($name = ''){
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
            $log->create_log('loan_mold', $id);
        }
        return $id;
    }

}
