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
    
    /**
     * 添加通讯录人员信息
     * 
     * @param string $name 人员姓名
     * @param string $phone 人员手机
     * @param string $tel 人员电话
     * @param string $email 人员邮箱
     * @param number $dep_id 所属部门编号
     * @param number $avatar 用户头像编号
     * @param string $position 用户职位名称
     * @param number $phone_type 手机号展示类型
     * @param string $job_no 用户工号
     * @return boolean 是否添加成功
     * @author xiaoyutab<xiaoyutab@qq.com>
     * @version v1.0.0
     * @copyright (c) 2017, xiaoyutab
     * @adtime 2017-07-13 21:07:13
     */
    public function create_directories_user($name = '',$phone = '',$tel = '',$email = '',$dep_id = '',$avatar = '',$position = '',$phone_type = '',$job_no = ''){
        // 姓名不许为空
        if(empty($name)){
            return FALSE;
        }
        // 手机号不允许为空
        if(empty($phone)){
            return FALSE;
        }
        $id = $this->add(array(
            'name' => trim($name),
            'phone' => trim($phone),
            'tel' => trim($tel),
            'email' => trim($email),
            'dep_id' => intval($dep_id),
            'avatar' => intval($avatar),
            'position' => trim($position),
            'phone_type' => intval($phone_type),
            'job_no' => trim($job_no),
            'ad_time' => NOW_TIME,
            'status' => 99
        ));
        if($id){
            $log = new LogModel();
            $log->create_log('directories_user', $id);
        }
        return $id;
    }
}