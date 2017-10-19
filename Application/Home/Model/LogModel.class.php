<?php

namespace Home\Model;
use Org\User\IpCity;
use Org\User\System;
use Admin\Model\LogInfoModel;

class LogModel extends \Common\Model\AllModel{
    /**
     * 添加前端用户的查看日志
     * 
     * @param string $tab_name 表名
     * @param number $oid 查看的表数据编号
     * @param number $uid 前端用户编号
     * @return boolean
     */
    public function look_data($tab_name = '',$oid = 0,$uid = 0){
        if(empty($tab_name) || $oid <= 0){
            return FALSE;
        }
        $arr = array(
            'user_type' => 99,
            'user_name' => trim($tab_name),
            'old_key' => intval($oid),
            'tab_key' => '',
            'old_val' => '',
            'new_val' => '',
            'uid' => $uid,
        );
        return $this->_create_date($arr);
    }

    /**
     * 执行添加操作，需要传入一个数组，其中必须要传入以下几点：
     *      user_type：操作类型：1查看2添加3修改4删除[5工单保存6审批操作7申请操作]
     *                  99前端用户查看98前端用户添加97前端用户修改96前端用户删除
     *      user_name：操作的表名，不带前缀
     *      old_key：修改的表的主键编号
     *      tab_key：修改表中的哪几个字段，英文半角逗号区分
     *      old_val：表中的旧的值，英文半角逗号分割
     *      new_val：更新成的值，英文半角逗号分割
     *      uid:前端用户编号
     * 
     * @return number 是否记录成功
     * @author xiaoyutab<xiaoyutab@qq.com>
     * @version v1.0.0
     * @copyright (c) 2017, xiaoyutab
     * @adtime 2017-10-12 01:29:51
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
        $arr['uid'] || $arr['uid'] = 0;// 配置默认用户编号为空
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