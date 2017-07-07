<?php
namespace Admin\Model;
use Org\User\IpCity;
use Org\User\System;
use Admin\Model\UserModel;

class UserLogModel extends \Common\Model\AllModel{
    /**
     * 添加普通错误信息
     * 
     * @param string $user_name 操作的表名
     * @param number $tab_key 修改的表的主键编号
     * @param string $old_val 旧的内容
     * @param string $new_val 新的内容
     * @return number 是否记录成功
     * @author xiaoyutab<xiaoyutab@qq.com>
     * @version v1.0.2
     * @copyright (c) 2017, xiaoyutab
     * @adtime 2017-07-06 18:44:59
     */
    public function update_log($user_name = 'user_group',$tab_key = 1,$old_val = '',$new_val = ''){
        $user = new UserModel();
        $info = $user->get_user_info();
        $user_agent = $_SERVER['HTTP_USER_AGENT'];
        $ip = get_client_ip();
        $temp_ip = new \Org\User\IpCity();
        $city = $temp_ip->getCity($ip);
        $sys = new \Org\User\System();
        $brow = $sys->get_browser($user_agent, ',');
        $system = $sys->get_system($user_agent);
        $city || $city = '本地测试';
        return $this->add(array(
            'user_type' => 98,
            'user_name' => $user_name,
            'old_key' => $tab_key,
            'old_val' => $old_val,
            'new_val' => $new_val,
            'uid' => $info['id'],
            'ad_time' => NOW_TIME,
            'ad_ip' => $ip,
            'browser' => $brow,
            'system' => $system,
            'user_agent' => $user_agent,
            'ip_city' => $city,
        ));
    }
    
    /**
     * 删除普通条目的错误日志
     * 
     * @param string $user_name 操作的表名
     * @param number $tab_key 修改的表的主键编号
     * @param string $old_val 旧的内容
     * @param string $new_val 新的内容
     * @return number 是否记录成功
     * @author xiaoyutab<xiaoyutab@qq.com>
     * @version v1.0.2
     * @copyright (c) 2017, xiaoyutab
     * @adtime 2017-07-06 19:36:44
     */
    public function delete_log($user_name = 'user_group',$tab_key = 1,$old_val = '99',$new_val = '98'){
        $user = new UserModel();
        $info = $user->get_user_info();
        $user_agent = $_SERVER['HTTP_USER_AGENT'];
        $ip = get_client_ip();
        $temp_ip = new \Org\User\IpCity();
        $city = $temp_ip->getCity($ip);
        $sys = new \Org\User\System();
        $brow = $sys->get_browser($user_agent, ',');
        $system = $sys->get_system($user_agent);
        $city || $city = '本地测试';
        return $this->add(array(
            'user_type' => 99,
            'user_name' => $user_name,
            'old_val' => $old_val,
            'old_key' => $tab_key,
            'new_val' => $new_val,
            'uid' => $info['id'],
            'ad_time' => NOW_TIME,
            'ad_ip' => $ip,
            'browser' => $brow,
            'system' => $system,
            'user_agent' => $user_agent,
            'ip_city' => $city,
        ));
    }
}