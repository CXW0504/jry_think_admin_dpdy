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
            // 将图片修改为存储图片的路径，而不再是图片编号。
            // 因为如果存图片编号的话，一张图片会生成多张截图，
            // 而一个用户可能生成很多张截图，
            // 所以此处将图片编号修改为图片路径，
            // 头像不在数据库中的文件表中存储
            // 修改时间：2017-07-14 01:04:46
            'avatar' => trim($avatar),
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
    
    /**
     * 修改通讯录人员信息
     * 
     * @param number $id 条目编号
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
     * @adtime 2017-07-14 16:15:06
     */
    public function save_directories_user($id = 0,$name = '',$phone = '',$tel = '',$email = '',$dep_id = '',$avatar = '',$position = '',$phone_type = '',$job_no = ''){
        // 姓名不许为空
        if(empty($name)){
            return FALSE;
        }
        // 通讯录条目编号不允许为空
        if($id <= 0){
            return FALSE;
        }
        // 手机号不允许为空
        if(empty($phone)){
            return FALSE;
        }
        // 获取旧的信息
        $old_arr = $this->where(array('id'=>$id))->find();
        // 设置临时数组，用来判断哪些字段有改动
        $temp_arr = array();
        // 设置改动后的数组，用来改变数据库数据
        $new_arr = array();
        // 遍历旧的变量数组
        foreach($old_arr as $k => $v){
            if(!isset($$k) || $k == 'id'){
                // 跳过未传值的字段和id字段
                continue;
            }
            if($$k != $v){
                // 获取修改的字段
                $temp_arr[$k] = $v;
                $new_arr[$k] = $$k;
            }
        }
        $success = $this->where(array('id'=> intval($id)))->save($new_arr);
        if($success){
            $log = new LogModel();
            $log->update_log('directories_user', $id,$new_arr,$temp_arr);
        }
        return $success;
    }
    
    /**
     * 删除通讯录条目信息
     * 
     * @param number $id 通讯录条目编号
     * @return boolean 是否删除成功
     * @author xiaoyutab<xiaoyutab@qq.com>
     * @version v1.0.0
     * @copyright (c) 2017, xiaoyutab
     * @adtime 2017-07-14 16:29:29
     */
    public function delete_directories_user($id = 0){
        // 如果传入的条目编号小于0，则返回删除失败
        if($id <= 0){
            return FALSE;
        }
        $info = $this->where(array('id'=> intval($id),'status'=>array('neq',98)))
                ->save(array('status'=>98,'del_time'=>NOW_TIME));
        if($info){
            $log = new LogModel();
            $log->delete_log('directories_user', intval($id), 99);
        }
        return $info;
    }
}