<?php

namespace Api\Model;
use Org\User\IpCity;
use Org\User\System;
use Api\Model\LogInfoModel;

class UserReceptionLogModel extends \Common\Model\AllModel{
    
    /**
     * 设置用户日志
     * 
     * @param intval $uid 用户编号
     * @param intval $type 用户操作类型1登录2退出
     * @return Boolean
     * @author xiaoyutab<xiaoyutab@qq.com>
     * @version v1.0.0
     * @copyright (c) 2017, xiaoyutab
     * @adtime 2017-09-05 00:22:24
     */
    public function set_log($uid = 1,$type = 1){
        return $this->_create_date(array(
            'user_type' => intval($type),
            'uid' => intval($uid),
        ));
    }

    /**
     * 执行添加操作，需要传入一个数组，其中必须要传入以下几点：
     *      user_type：操作类型：1登录2退出
     *      uid：谁操作的
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
        // 如果必须要传入的这两个字段没有传入则返回false
        if(!isset($arr['user_type'],$arr['uid'])){
            return FALSE;
        }
        $log_info = new LogInfoModel();
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
        $arr['system'] = $log_info->get_id($system);
        $arr['ad_time'] = NOW_TIME;
        return $this->add($arr);
    }
}