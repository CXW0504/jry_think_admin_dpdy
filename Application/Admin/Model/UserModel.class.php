<?php
namespace Admin\Model;

class UserModel extends \Common\Model\PdoModel{
    /**
     * 检测用户是否登录
     * @return boolean
     */
    public function checkUser(){
        if (empty($_SESSION)) {
            return FALSE;
        }
        return true;
        $info = session('admin.userinfo');
        $time = session('admin.usertime');
        // 超时token效验
        if($time < ( time() - C('MAC_TIME_LOGOUT'))){
                session('admin.usertime',time());
                return session('admin.usertoken') == md5(123456);
        }
        session('admin.usertime',time());
        return !empty($info);
    }
    
    /**
     * 登录操作
     * @param string $name 用户输入的用户名，可以是username，可以是手机号，也可以是邮箱
     * @param string $pass 用户输入的密码信息
     * @return boolean
     */
    public function login($name = '',$pass = ''){
        $pir = C('DB_PREFIX');
        $sql = "SELECT `id`,`password`,`rand_code` FROM `{$pir}user` WHERE `username` = ? OR `phone` = ? OR `email` = ?";
        $data = $this->query($sql,array($name,$name,$name));
        if(empty($data)){
            return NULL;// 查找不到对应的用户
        }
        // 验证用户输入的密码
        $id = $this->cheskPassword($data,$pass);
        // 根据返回的编号判断输入的密码是否正确
        if($id){
            $sql = "SELECT `{$pir}user`.`id`, `{$pir}user`.`username`, `{$pir}user`.`phone`, `{$pir}user`.`email`, `{$pir}user`.`group_id`, `{$pir}user`.`ad_time`, `{$pir}user`.`status`, `{$pir}user_info`.`sex`, `{$pir}user_info`.`birthdy`, `{$pir}file`.`file_path` FROM `{$pir}user` LEFT JOIN `{$pir}user_info` ON `{$pir}user`.`id` = `{$pir}user_info`.`id` LEFT JOIN `{$pir}file` ON `{$pir}user_info`.`avatar` = `{$pir}file`.`id` WHERE `{$pir}user`.`id` = ?";
            $data = $this->query($sql,array($id));
            return $data[0];// 返回查询到的数组数据
        }
        return FALSE;
    }
    
    /**
     * 密码验证操作
     *  data为执行SQL获取到的结果
     *  SQL：SELECT `id`,`password`,`rand_code` FROM `user` WHERE `username` = ? OR `phone` = ? OR `email` = ?
     * @param array $data 查询到的结果
     * @param string $pass 用户输入的密码
     * @return boolean
     */
    private function cheskPassword($data = array(),$pass = ''){
        if(is_array($data)){
            foreach($data as $v){
                if(pass($pass, $v['rand_code']) == $v['password']){
                    return $v['id'];
                }
            }
        }
        return false;
    }
    
    /**
     * 设置用户的登录token标识
     * @param type $uid 用户编号
     * @param type $token 用户token值
     * @param type $type 用户设备类型
     * @return type
     */
    public function set_user_token($uid = 1,$token = '',$type = 1){
        $pir = C('DB_PREFIX');
        $sql = "SELECT `id` FROM `{$pir}user_token` WHERE `uid` = ? AND `user_type` = ?";
        $data = $this->query($sql,array($uid,$type));
        if(empty($data[0])){
            $sql = "INSERT INTO `{$pir}user_token`(`uid`,`token`,`user_type`,`ad_time`) VALUE(?,?,?,UNIX_TIMESTAMP())";
            return $this->query($sql,array($uid,$token,$type));
        }
        $sql = "UPDATE `{$pir}user_token` SET `token` = ?,`ad_time` = UNIX_TIMESTAMP() WHERE `id` = ? ";
        return $this->query($sql,array($token,$data[0]['id']));
    }
    
    /**
     * 设置用户登录日志
     */
    public function set_user_login_log(){
        $info = $this->get_user_info();
        $user_agent = $_SERVER['HTTP_USER_AGENT'];
        $ip = get_client_ip();
        $temp_ip = new \Org\User\IpCity();
        $city = $temp_ip->getCity($ip);
        $sys = new \Org\User\System();
        $brow = $sys->get_browser($user_agent,',');
        $system = $sys->get_system($user_agent);
        $city || $city = '本地测试';
        $sql = "INSERT INTO `dpdy_user_log` (`user_type`,`uid`,`ad_time`,`ad_ip`,`browser`,`system`,`user_agent`,`ip_city`) VALUE(1,?,UNIX_TIMESTAMP(),?,?,?,?,?)";
        return $this->query($sql, array($info['id'],$ip,$brow,$system,$user_agent,$city));
    }
    
    /**
     * 获取用户详情信息
     * @return type
     */
    public function get_user_info(){
        $info = session('admin.userinfo');
        if(isset($info) && is_array($info))
            return $info;
        return array();
    }
}