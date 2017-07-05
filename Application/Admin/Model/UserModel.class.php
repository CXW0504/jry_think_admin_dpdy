<?php
namespace Admin\Model;

class UserModel extends \Common\Model\AllModel{
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
}