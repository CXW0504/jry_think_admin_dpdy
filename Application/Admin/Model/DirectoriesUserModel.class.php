<?php
namespace Admin\Model;
use Admin\Model\LogModel;

/**
 * 通讯录操作类，作用为获取通讯录相关信息
 * 
 * @author xiaoyutab<xiaoyutab@qq.com>
 * @version v1.0.0
 * @copyright (c) 2017, xiaoyutab
 * @adtime 2017-07-12 18:40:38
 */
class DirectoriesUserModel extends \Common\Model\AllModel {
    /**
     * 获取部门下的人员数量
     * @param number $id 部门编号
     * @return number
     * @author xiaoyutab<xiaoyutab@qq.com>
     * @version v1.0.0
     * @copyright (c) 2017, xiaoyutab
     * @adtime 2017-07-12 18:40:34
     */
    public function get_directories_count_people($id = 0){
        $count = $this->where(array('dep_id' => intval($id),'status' => array('neq',98)))->count();
        return $count;
    }
}