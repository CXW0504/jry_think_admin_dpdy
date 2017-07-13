<?php

namespace Admin\Model;
use Org\User\IpCity;
use Org\User\System;
use Admin\Model\UserModel;

class LogModel extends \Common\Model\AllModel{
    /**
     * 创建添加数据的日志
     * 
     * @param string $user_name 添加数据的表名
     * @param number $old_key 添加的数据主键
     * @return number 是否添加成功
     * @author xiaoyutab<xiaoyutab@qq.com>
     * @version v1.0.0
     * @copyright (c) 2017, xiaoyutab
     * @adtime 2017-07-12 18:18:10
     */
    public function create_log($user_name = '',$old_key = 1){
        $arr = array(
            'user_type' => 2,
            'user_name' => $user_name,
            'old_key' => intval($old_key),
            'tab_key' => '',
            'old_val' => '',
            'new_val' => '',
        );
        return $this->_create_date($arr);
    }
    
    /**
     * 修改数据库日志
     * 
     * @param string $user_name 变更字段的表名
     * @param number $id 变更字段的表的主键
     * @param array $save 变更字段的新的值【关联数组】
     * @param array $old 变更字段前的旧的值【关联数组】
     * @return number 是否添加成功
     * @author xiaoyutab<xiaoyutab@qq.com>
     * @version v1.0.0
     * @copyright (c) 2017, xiaoyutab
     * @adtime 2017-07-13 10:47:48
     */
    public function update_log($user_name = '',$id = 0,$save = array(),$old = array()){
        $arr = array(
            'user_type' => 3,
            'user_name' => $user_name,
            'old_key' => intval($id),
            'tab_key' => implode(',', array_keys($old)),
            'old_val' => implode(',', $old),
            'new_val' => implode(',', $save),
        );
        return $this->_create_date($arr);
    }
    
    /**
     * 删除数据库日志
     *      逻辑删除，非真实删除
     * 
     * @param string $user_name 变更字段的表名
     * @param number $id 变更字段的表的主键
     * @param number $old 变更前的状态
     * @return number 是否添加成功
     * @author xiaoyutab<xiaoyutab@qq.com>
     * @version v1.0.0
     * @copyright (c) 2017, xiaoyutab
     * @adtime 2017-07-13 10:47:48
     */
    public function delete_log($user_name = '',$id = 0,$old = 99){
        $arr = array(
            'user_type' => 4,
            'user_name' => $user_name,
            'old_key' => intval($id),
            'tab_key' => 'status,del_time',
            'old_val' => $old.',',
            'new_val' => '98,'.NOW_TIME,
        );
        return $this->_create_date($arr);
    }

    /**
     * 执行添加操作，需要传入一个数组，其中必须要传入以下几点：
     *      user_type：操作类型：1查看2添加3修改4删除[5工单保存6审批操作7申请操作]
     *      user_name：操作的表名，不带前缀
     *      old_key：修改的表的主键编号
     *      tab_key：修改表中的哪几个字段，英文半角逗号区分
     *      old_val：表中的旧的值，英文半角逗号分割
     *      new_val：更新成的值，英文半角逗号分割
     * 
     * @return number 是否记录成功
     * @author xiaoyutab<xiaoyutab@qq.com>
     * @version v1.0.0
     * @copyright (c) 2017, xiaoyutab
     * @adtime 2017-07-12 18:03:57
     */
    private function _create_date($arr = array()){
        // 如果传入的不是数组就直接返回false
        if(!is_array($arr)){
            return FALSE;
        }
        // 如果必须要传入的这六个字段没有传入则返回false
        if(!isset($arr['user_type'],$arr['user_name'],$arr['old_key'],$arr['tab_key'],$arr['old_val'],$arr['new_val'])){
            return FALSE;
        }
        // 获取用户id
        $user = new UserModel();
        $info = $user->get_user_info();
        $arr['uid'] = $info['id'];
        // 获取用户的HTTP_USER_AGENT标识
        $user_agent = $_SERVER['HTTP_USER_AGENT'];
        $arr['user_agent'] = $user_agent;
        // 获取用户的IP地址及城市
        $ip = get_client_ip();
        $temp_ip = new \Org\User\IpCity();
        $city = $temp_ip->getCity($ip);
        $city || $city = '本地测试';
        $arr['ad_ip'] = $ip;
        $arr['ip_city'] = $city;
        // 获取用户的系统及浏览器版本信息
        $sys = new \Org\User\System();
        $brow = $sys->get_browser($user_agent, ',');
        $system = $sys->get_system($user_agent);
        $arr['browser'] = $brow;
        $arr['system'] = $system;
        $arr['ad_time'] = NOW_TIME;
        return $this->add($arr);
    }
}