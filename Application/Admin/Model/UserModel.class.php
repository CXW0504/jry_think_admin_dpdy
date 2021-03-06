<?php

namespace Admin\Model;
use Admin\Model\UserLogModel;

class UserModel extends \Common\Model\PdoModel {

    /**
     * 检测用户是否登录
     * 
     * @return boolean
     * @author xiaoyutab<xiaoyutab@qq.com>
     * @version v1.0.2
     * @copyright (c) 2017, xiaoyutab
     * @adtime 2017-07-06 16:22:31
     */
    public function checkUser() {
        if (empty($_SESSION)) {
            return FALSE;
        }
        return true;
        $info = session('admin.userinfo');
        $time = session('admin.usertime');
        // 超时token效验
        if ($time < ( time() - C('MAC_TIME_LOGOUT'))) {
            session('admin.usertime', time());
            return session('admin.usertoken') == md5(time());
        }
        session('admin.usertime', time());
        return !empty($info);
    }

    /**
     * 登录操作
     * 
     * @param string $name 用户输入的用户名，可以是username，可以是手机号，也可以是邮箱
     * @param string $pass 用户输入的密码信息
     * @return boolean
     * @author xiaoyutab<xiaoyutab@qq.com>
     * @version v1.0.2
     * @copyright (c) 2017, xiaoyutab
     * @adtime 2017-07-06 16:22:45
     */
    public function login($name = '', $pass = '') {
        $pir = C('DB_PREFIX');
        $sql = "SELECT `id`,`password`,`rand_code` FROM `{$pir}user` WHERE `status` = 99 AND ( `username` = ? OR `phone` = ? OR `email` = ? )";
        $data = $this->query($sql, array($name, $name, $name));
        if (empty($data)) {
            return NULL; // 查找不到对应的用户
        }
        // 验证用户输入的密码
        $id = $this->cheskPassword($data, $pass);
        // 根据返回的编号判断输入的密码是否正确
        if ($id) {
            $sql = "SELECT `{$pir}user`.`id`, `{$pir}user`.`username`, `{$pir}user`.`phone`, `{$pir}user`.`email`, `{$pir}user`.`group_id`, `{$pir}user`.`ad_time`, `{$pir}user`.`status`, `{$pir}user_info`.`sex`, `{$pir}user_info`.`birthdy`, `{$pir}user_group`.`content`, `{$pir}file`.`file_path` FROM `{$pir}user` LEFT JOIN `{$pir}user_info` ON `{$pir}user`.`id` = `{$pir}user_info`.`id` LEFT JOIN `{$pir}user_group` ON `{$pir}user_group`.`id` = `{$pir}user`.`group_id` LEFT JOIN `{$pir}file` ON `{$pir}user_info`.`avatar` = `{$pir}file`.`id` WHERE `{$pir}user`.`id` = ?";
            $data = $this->query($sql, array($id));
            $info = $data[0]; // 返回查询到的数组数据
            if($info['group_id'] == 0){
                // 如果是超级管理员将全部的权限都赋予给超级管理员
                $group_content = array();
                $menu = C('MENU_CONFIG');
                foreach($menu as $k => $v){
                    if(is_array($v['content'])){
                        foreach($v['content'] as $ks => $vs){
                            $group_content[] = $k.'/'.$ks;
                        }
                    }
                    if(is_array($v['child'])){
                        foreach($v['child'] as $ks => $vs){
                            $group_content[] = $k.'/'.$ks;
                        }
                    }
                }
            } else {
                // 否则就根据权限组给出的权限进行分析判断权限
                if(empty($info['content'])){
                    $group_content = array();
                } else {
                    $group_content = explode(',', $info['content']);
                }
            }
            $info['content'] = $group_content;
            return $info;
        }
        return FALSE;
    }

    /**
     * 密码验证操作
     *  data为执行SQL获取到的结果
     *  SQL：SELECT `id`,`password`,`rand_code` FROM `user` WHERE `username` = ? OR `phone` = ? OR `email` = ?
     * 
     * @param array $data 查询到的结果
     * @param string $pass 用户输入的密码
     * @return boolean
     * @author xiaoyutab<xiaoyutab@qq.com>
     * @version v1.0.2
     * @copyright (c) 2017, xiaoyutab
     * @adtime 2017-07-06 16:22:56
     */
    private function cheskPassword($data = array(), $pass = '') {
        if (is_array($data)) {
            foreach ($data as $v) {
                if (pass($pass, $v['rand_code']) == $v['password']) {
                    return $v['id'];
                }
            }
            return FALSE;
        }
        return false;
    }

    /**
     * 设置用户登录日志
     * 
     * @return array
     * @author xiaoyutab<xiaoyutab@qq.com>
     * @version v1.0.3
     * @copyright (c) 2017, xiaoyutab
     * @adtime 2017-07-06 16:23:13
     */
    public function set_user_login_log() {
        $log = new UserLogModel();
        $info = $this->get_user_info();
        return $log->set_log(1,'user',$info['id']);
    }

    /**
     * 设置用户退出日志
     * 
     * @return array
     * @author xiaoyutab<xiaoyutab@qq.com>
     * @version v1.0.3
     * @copyright (c) 2017, xiaoyutab
     * @adtime 2017-07-06 16:23:13
     */
    public function set_user_logout_log() {
        $log = new UserLogModel();
        $info = $this->get_user_info();
        return $log->set_log(4,'user',$info['id']);
    }

    /**
     * 设置用户注册日志
     * 
     * @param number $uid 注册的用户编号
     * @return array
     * @author xiaoyutab<xiaoyutab@qq.com>
     * @version v1.0.2
     * @copyright (c) 2017, xiaoyutab
     * @adtime 2017-07-09 14:53:24
     */
    public function set_user_insert_log($uid = 1) {
        $log = new UserLogModel();
        return $log->set_log(2,'user',$uid);
    }

    /**
     * 获取用户详情信息
     * 
     * @return array
     * @author xiaoyutab<xiaoyutab@qq.com>
     * @version v1.0.2
     * @copyright (c) 2017, xiaoyutab
     * @adtime 2017-07-06 16:23:21
     */
    public function get_user_info() {
        $info = session('admin.userinfo');
        if (isset($info) && is_array($info))
            return $info;
        return array();
    }
    
    /**
     * 获取用户总条数
     * 
     * @param string $key_words 查询的关键词
     * @param number $group_id 用户组编号
     * @return int
     * @author xiaoyutab<xiaoyutab@qq.com>
     * @version v1.0.3
     * @copyright (c) 2017, xiaoyutab
     * @adtime 2017-07-09 13:44:50
     */
    public function get_count($key_words = '',$group_id = -1){
        $pir = C('DB_PREFIX');
        $where = " WHERE `{$pir}user`.`status` <> 98";
        $data = array();
        // 关键词判断
        if(!empty($key_words)){
            $where .= " AND (`{$pir}user`.`username` LIKE ? OR `{$pir}user`.`phone` LIKE ? )";
            $data[] = '%'.$key_words.'%';
            $data[] = '%'.$key_words.'%';
        }
        // 权限组判断
        if($group_id >= 0){
            $where .= " AND `{$pir}user`.`group_id` = ?";
            $data[] = $group_id;
        }
        $sql = "SELECT COUNT(*) AS `count` FROM `{$pir}user` LEFT JOIN `{$pir}user_info` ON `{$pir}user`.`id` = `{$pir}user_info`.`id` LEFT JOIN `{$pir}user_group` ON `{$pir}user`.`group_id` = `{$pir}user_group`.`id`";
        $data = $this->query($sql.$where,$data);
        if(empty($data[0]['count'])){
            return 0;
        }
        return $data[0]['count'];
    }
    
    /**
     * 获取用户列表操作
     * 备注：按照添加时间进行倒叙排列
     * 
     * @param string $key_words 查询的关键词
     * @param number $group_id 用户组编号
     * @param number $p0 查询条数的开始条数
     * @param number $p1 查询条数的结束条数
     * @return array 查询到的数据
     * @author xiaoyutab<xiaoyutab@qq.com>
     * @version v1.0.3
     * @copyright (c) 2017, xiaoyutab
     * @adtime 2017-07-09 13:46:20
     */
    public function get_list($key_words = '',$group_id = -1,$p0 = 0,$p1 = 20){
        $pir = C('DB_PREFIX');
        $where = " WHERE `{$pir}user`.`status` =99 ";
        $data = array();
        // 关键词判断
        if(!empty($key_words)){
            $where .= " AND (`{$pir}user`.`username` LIKE ? OR `{$pir}user`.`phone` LIKE ? )";
            $data[] = '%'.$key_words.'%';
            $data[] = '%'.$key_words.'%';
        }
        // 权限组判断
        if($group_id >= 0){
            $where .= " AND `{$pir}user`.`group_id` = ?";
            $data[] = $group_id;
        }
        $sql = "SELECT `{$pir}user`.`username`,`{$pir}user`.`group_id`,`{$pir}user`.`id`, `{$pir}user`.`phone`, `{$pir}user_info`.`birthdy`, `{$pir}user`.`ad_time`, `{$pir}user_group`.`name` FROM `{$pir}user` LEFT JOIN `{$pir}user_info` ON `{$pir}user`.`id` = `{$pir}user_info`.`id` LEFT JOIN `{$pir}user_group` ON `{$pir}user`.`group_id` = `{$pir}user_group`.`id`";
        $limit = " ORDER BY `{$pir}user`.`ad_time` DESC LIMIT ". intval($p0).','. intval($p1);
        $data = $this->query($sql.$where.$limit,$data);
        if(empty($data)){
            return array();
        }
        return $data;
    }
    
    /**
     * 创建用户操作
     * @param string $username 用户登录用户名
     * @param number $phone 用户登录手机号
     * @param date $birthdy 用户生日
     * @param number $group_id 用户权限组编号
     * @param string $password 用户登录密码
     * @param number $sex 用户性别
     * @return boolean|string 登录成功|失败的代码信息
     * @author xiaoyutab<xiaoyutab@qq.com>
     * @version v1.0.2
     * @copyright (c) 2017, xiaoyutab
     * @adtime 2017-07-09 15:01:07
     */
    public function create_user($username = '',$phone = '',$birthdy = '',$group_id = '',$password = '',$sex = ''){
        $pir = C('DB_PREFIX');
        $insert_sql = "INSERT INTO `{$pir}user` SET ";
        $insert_data = array();
        if(empty($username)){
            return '用户名不允许为空';
        }
        $sql = "SELECT `id` FROM `{$pir}user` WHERE `username` = ?";
        $data = $this->query($sql,array($username));
        if(!empty($data[0])){
            return '用户名已存在';
        }
        $insert_sql .= "`username` = ?";
        $insert_data[] = $username;
        // 验证手机号
        if(!empty($phone)){
            $sql = "SELECT `id` FROM `{$pir}user` WHERE `phone` = ?";
            $data = $this->query($sql,array($phone));
            if(!empty($data[0])){
                return '手机号已存在';
            }
            $insert_sql .= ",`phone` = ?";
            $insert_data[] = $phone;
        }
        // 设置随机码和密码
        $rand_code = random('',6);
        $password = pass($password, $rand_code);
        $insert_sql .= ",`password` = ?,`rand_code` = ?,`group_id` = ?,`ad_time` = ?,`status` = 99";
        $insert_data[] = $password;
        $insert_data[] = $rand_code;
        $insert_data[] = $group_id;
        $insert_data[] = NOW_TIME;
        $this->query($insert_sql,$insert_data);
        $sql = "SELECT `id` FROM `{$pir}user` WHERE `username` = ?";
        $data = $this->query($sql,array($username));
        if($data[0]['id'] != 0){
            $sql = "INSERT INTO `{$pir}user_info` SET `id` = ?, `sex` = ?, `birthdy` = ?";
            $this->query($sql,array($data[0]['id'],$sex,$birthdy));
            $this->set_user_insert_log($data[0]['id']);
        }
        return TRUE;
    }
    
    /**
     * 更新用户登录密码
     * @param string $id 用户编号
     * @param string $pass 用户新的密码标识
     * @return boolean 是否更新成功
     * @return array
     * @author xiaoyutab<xiaoyutab@qq.com>
     * @version v1.0.1
     * @copyright (c) 2017, xiaoyutab
     * @adtime 2017-07-09 15:26:32
     */
    public function save_user_password($id = 1,$pass = ''){
        if(empty($id)){return FALSE;}
        if(empty($pass)){return FALSE;}
        // 设置随机码和密码
        $rand_code = random('',6);
        $password = pass($pass, $rand_code);
        $sql = "UPDATE `dpdy_user` SET `password` = ?,`rand_code` = ? WHERE `id` = ?";
        $this->query($sql,array($password,$rand_code,$id));
        $this->set_user_password_log($id);
        return TRUE;
    }

    /**
     * 设置用户修改密码日志
     * 
     * @param number $uid 注册的用户编号
     * @return array
     * @author xiaoyutab<xiaoyutab@qq.com>
     * @version v1.0.2
     * @copyright (c) 2017, xiaoyutab
     * @adtime 2017-07-09 14:53:24
     */
    public function set_user_password_log($uid = 1) {
        $log = new UserLogModel();
        $info = $this->get_user_info();
        return $log->set_log(5,'user',$uid);
    }
    
    /**
     * 检测用户输入的密码是否正确
     * 
     * @param string $password 用户输入的密码
     * @return boolean 输入的密码是否正确
     * @author xiaoyutab<xiaoyutab@qq.com>
     * @version v1.0.0
     * @copyright (c) 2017, xiaoyutab
     * @adtime 2017-07-12 17:03:17
     */
    public function check_self_password($password = ''){
        $pir = C('DB_PREFIX');
        $sql = "SELECT `id`,`password`,`rand_code` FROM `{$pir}user` WHERE `status` = 99 AND `id` = ?";
        $info = $this->get_user_info();
        $data = $this->query($sql, array($info['id']));
        if($this->cheskPassword($data, $password)){
            return TRUE;
        }
        return FALSE;
    }
}
