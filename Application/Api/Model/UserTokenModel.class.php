<?php
namespace Api\Model;

/**
 * 用户登录token操作
 * 
 * @author xiaoyutab<xiaoyutab@qq.com>
 * @version v1.0.0
 * @copyright (c) 2017, xiaoyutab
 * @adtime 2017-09-04 22:15:15
 */
class UserTokenModel extends \Common\Model\PdoModel{

    /**
     * 设置用户的登录token标识
     * 
     * @param intval $uid 用户编号
     * @param string $token 用户token值
     * @param intval $type 用户设备类型
     * @return number
     * @author xiaoyutab<xiaoyutab@qq.com>
     * @version v1.1.0
     * @copyright (c) 2017, xiaoyutab
     * @adtime 2017-07-06 16:23:03
     */
    public function set_user_token($uid = 1, $token = '', $type = 1) {
        $pir = C('DB_PREFIX');
        if($this->login_fix($uid, $type)){
            $sql = "INSERT INTO `{$pir}user_token`(`uid`,`token`,`user_type`,`ad_time`) VALUE(?,?,?,?)";
            $this->query($sql,array($uid,$token,$type,NOW_TIME));
            return TRUE;
        }
        return FALSE;
    }
    
    /**
     * 登录前置操作,判断用户是否可以登录进行设置token
     *      如果可以登录,会自动判断是否到达了峰值。如果到达峰值会自动进行清理最老的一条记录
     * 
     * @param intval $uid 用户编号
     * @param intval $type 用户设备类型
     * @return Boolean
     * @author xiaoyutab<xiaoyutab@qq.com>
     * @version v1.0.0
     * @copyright (c) 2017, xiaoyutab
     * @adtime 2017-09-04 23:31:23
     */
    private function login_fix($uid = 1, $type = 1){
        $pir = C('DB_PREFIX');
        $temp_1 = C('LOGIN_TYPE');// 是否为单设备状态
        if($temp_1){
            // 如果是单设备登录
            $temp_2 = C('LOGIN_TYPE_AUTO_CLEAR'); // 获取是否自动清除历史
            $sql = "SELECT `id` FROM `{$pir}user_token` WHERE `uid` = ?";
            $data = $this->query($sql, array($uid));
            if(empty($data[0]['id'])){
                return TRUE;
            }
            if($temp_2){
                // 如果自动清除登录状态
                $sql = "DELETE FROM `dpdy_user_token` WHERE `uid` = ?";
                $this->query($sql, array($uid));// 因为是单设备状态,所以可以清除掉所有的该用户登录记录
                return TRUE;
            } else {
                // 如果是锁定登录状态
                return FALSE;
            }
        } else {
            // 如果是多设备登录
            $temp_2 = C('LOGIN_TYPE_AUTO_CLEAR'); // 获取是否自动清除历史
            $sql = "SELECT `id` FROM `{$pir}user_token` WHERE `uid` = ? AND `user_type` = ? ORDER BY `id` ASC";
            $data = $this->query($sql, array($uid,$type));
            if(count($data) >= C('LOGIN_TYPE_NUMBER')){
                // 如果登录的个数限制到达了峰值
                if($temp_2){
                    // 如果自动清除登录状态
                    $sql = "DELETE FROM `dpdy_user_token` WHERE `id` = ?";
                    // 清除掉一个空位,方便登录操作
                    for($i = 0 ; $i < count($data) - C('LOGIN_TYPE_NUMBER') + 1; $i ++){
                        $this->query($sql, array($data[$i]['id']));
                    }
                    return TRUE;
                } else {
                    // 如果是锁定登录状态
                    return FALSE;
                }
            } else {
                return TRUE;// 如果没有到达数量限制就直接返回true
            }
            return FALSE;
        }
    }

    /**
     * 检测用户的登录token标识
     * 
     * @param intval $uid 用户编号
     * @param string $token 用户token值
     * @param intval $type 用户设备类型
     * @return boolean
     * @author xiaoyutab<xiaoyutab@qq.com>
     * @version v1.0.0
     * @copyright (c) 2017, xiaoyutab
     * @adtime 2017-09-04 21:55:09
     */
    public function check_user_token($uid = 1, $token = '', $type = 1) {
        $pir = C('DB_PREFIX');
        $sql = "SELECT `id`,`token` FROM `{$pir}user_token` WHERE `uid` = ? AND `user_type` = ?";
        $data = $this->query($sql, array($uid, $type));
        if (empty($data[0])) {
            return FALSE;
        }
        return boolval($data[0]['token'] == $token);
    }

    /**
     * 清除用户登录标识
     * 
     * @param intval $uid 用户编号
     * @param string $token 用户token值
     * @return boolean
     * @author xiaoyutab<xiaoyutab@qq.com>
     * @version v1.1.0
     * @copyright (c) 2017, xiaoyutab
     * @adtime 2017-07-06 16:23:03
     */
    public function del_user_token($uid = 1, $token = '') {
        $temp1 = C('LOGIN_TYPE');
        $temp2 = C('LOGIN_TYPE_AUTO_CLEAR');
        if($temp1 && !$temp2){
            return TRUE;// 如果是单设备禁止清除状态则直接返回成功
        }
        $sql = "DELETE FROM `dpdy_user_token` WHERE `uid` = ? AND `token` = ?";
        $this->query($sql, array($uid, $token));
        return TRUE;
    }
    
    /**
     * 获取一个32位长度的token标示
     * 
     * @return string
     * @author xiaoyutab<xiaoyutab@qq.com>
     * @version v1.0.0
     * @copyright (c) 2017, xiaoyutab
     * @adtime 2017-09-04 22:52:10
     */
    public function get_range_token(){
        return md5(microtime());
    }
}