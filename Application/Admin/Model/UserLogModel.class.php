<?php
namespace Admin\Model;
use Org\User\IpCity;
use Org\User\System;
use Admin\Model\UserModel;
use Admin\Model\LogInfoModel;

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
        $log_info = new LogInfoModel();
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
            'ad_ip' => $log_info->get_id($ip),
            'browser' => $log_info->get_id($brow),
            'system' => $log_info->get_id($system),
            'user_agent' => $log_info->get_id($user_agent),
            'ip_city' => $log_info->get_id($city),
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
        $log_info = new LogInfoModel();
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
            'ad_ip' => $log_info->get_id($ip),
            'browser' => $log_info->get_id($brow),
            'system' => $log_info->get_id($system),
            'user_agent' => $log_info->get_id($user_agent),
            'ip_city' => $log_info->get_id($city),
        ));
    }
    
    /**
     * 删除普通条目的错误日志
     * 
     * @param number $type 操作类型：1用户登录，2用户注册，3用户找回密码，4退出登录，5修改密码，97其他添加操作，98其他更新操作，99其他删除操作
     * @param string $user_name 操作的表名
     * @param number $tab_key 修改的表的主键编号
     * @param string $old_val 旧的内容
     * @param string $new_val 新的内容
     * @return number 是否记录成功
     * @author xiaoyutab<xiaoyutab@qq.com>
     * @version v1.0.2
     * @copyright (c) 2017, xiaoyutab
     * @adtime 2017-08-17 20:33:35
     */
    public function set_log($type = 1,$user_name = 'user_group',$tab_key = 1,$old_val = '',$new_val = ''){
        $user = new UserModel();
        $info = $user->get_user_info();
        $user_agent = $_SERVER['HTTP_USER_AGENT'];
        $ip = get_client_ip();
        $log_info = new LogInfoModel();
        $temp_ip = new \Org\User\IpCity();
        $city = $temp_ip->getCity($ip);
        $sys = new \Org\User\System();
        $brow = $sys->get_browser($user_agent, ',');
        $system = $sys->get_system($user_agent);
        $city || $city = '本地测试';
        return $this->add(array(
            'user_type' => $type,
            'user_name' => $user_name,
            'old_val' => $old_val,
            'old_key' => $tab_key,
            'new_val' => $new_val,
            'uid' => $info['id'],
            'ad_time' => NOW_TIME,
            'ad_ip' => $log_info->get_id($ip),
            'browser' => $log_info->get_id($brow),
            'system' => $log_info->get_id($system),
            'user_agent' => $log_info->get_id($user_agent),
            'ip_city' => $log_info->get_id($city),
        ));
    }
}