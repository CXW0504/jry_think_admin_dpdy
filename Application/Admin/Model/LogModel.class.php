<?php

namespace Admin\Model;
use Org\User\IpCity;
use Org\User\System;
use Admin\Model\UserModel;
use Admin\Model\LogInfoModel;

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
     * @version v1.0.1
     * @copyright (c) 2017, xiaoyutab
     * @adtime 2017-07-13 10:47:48
     */
    public function update_log($user_name = '',$id = 0,$save = array(),$old = array()){
        $save = $this->_englist_import($save);
        $old = $this->_englist_import($old);
        foreach($old as $k => $v){
            if($v == $save[$k]){
                // 如果重复就删除掉相同的值
                unset($old[$k]);
                unset($save[$k]);
            } else if(!isset($save[$k])){
            	// 如果修改的值里面没有old里面的字段就直接删除掉old里面的值
            	unset($old[$k]);
            }
        }
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
     * 字符串替换操作，将原来的,替换成|
     * @param array $arr
     * @return array
     * @author xiaoyutab<xiaoyutab@qq.com>
     * @version v1.0.0
     * @copyright (c) 2017, xiaoyutab
     * @adtime 2017-7-18 16:16:05
     */
    private function _englist_import($arr = array()){
        foreach($arr as $k => $v){
            $arr[$k] = str_replace(',', '|', $v);
        }
        return $arr;
    }
    
    /**
     * 删除数据库日志
     *      真实删除，非逻辑删除
     * 
     * @param string $tab_name 删除数据的表名
     * @param array $data 删除的数据信息
     * @return boolean 是否删除成功
     *      真实删除记录值中，new_val为空，old_key为0，user_type为4
     * @author xiaoyutab<xiaoyutab@qq.com>
     * @version v1.0.0
     * @copyright (c) 2017, xiaoyutab
     * @adtime 2017-08-23 15:41:05
     */
    public function delete_log_actual($tab_name = '',$data = array()){
        if(empty($tab_name)){
            return FALSE;
        }
        $arr = array(
            'user_type' => 4,
            'user_name' => $tab_name,
            'old_key' => 0,
            'tab_key' => implode(',', array_keys($data)),
            'old_val' => implode(',', $data),
            'new_val' => implode(',', array()),
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
        $log_info = new LogInfoModel();
        $info = $user->get_user_info();
        $arr['uid'] = $info['id'];
        // 获取用户的HTTP_USER_AGENT标识
        $user_agent = $_SERVER['HTTP_USER_AGENT'];
        $arr['user_agent'] = $log_info->get_id($user_agent);
        // 获取用户的IP地址及城市
        $ip = get_client_ip();
        $temp_ip = new \Org\User\IpCity();
        $city = $temp_ip->getCity($ip);
        $city || $city = '本地测试';
        $arr['ad_ip'] = $log_info->get_id($ip);
        $arr['ip_city'] = $log_info->get_id($city);
        // 获取用户的系统及浏览器版本信息
        $sys = new \Org\User\System();
        $brow = $sys->get_browser($user_agent, ',');
        $system = $sys->get_system($user_agent);
        $arr['browser'] = $log_info->get_id($brow);
        $arr['user_name'] = $log_info->get_id($arr['user_name']);
        $arr['system'] = $log_info->get_id($system);
        $arr['ad_time'] = NOW_TIME;
        return $this->add($arr);
    }
}