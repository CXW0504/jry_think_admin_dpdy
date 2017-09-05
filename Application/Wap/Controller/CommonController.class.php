<?php
namespace Wap\Controller;

/**
 * 网站首页登录权限控制器
 * 
 * @author xiaoyutab<xiaoyutab@qq.com>
 * @version v1.0.2
 * @copyright (c) 2017, xiaoyutab
 * @adtime 2017-09-01 16:55:05
 */
class CommonController extends \Common\Controller\PublicController{
    /**
     * 构造函数类
     * 
     * @author xiaoyutab<xiaoyutab@qq.com>
     * @version v1.0.1
     * @copyright (c) 2017, xiaoyutab
     * @adtime 2017-09-01 16:53:21
     */
    public function __construct() {
        parent::__construct();
        $this->wget('auicss');
        if(cookie('u_info') && !cookie('user_info')){
            // 如果设置了自动登录且目前未登录
            $this->auto_login();// 进行自动登录
        }
        $info = session('user_info');// 获取用户信息
        $info_time = session('user_time'); // 获取上次检测时间
        if(NOW_TIME > $info_time + C('TOKEN_CHECK_TIME')){
            // 如果设置的检测时间到了以后，就判断一下目前token是否有效
            $info = get_api_data('User/check_user_login',array(
                'uid' => $info['uid'],
                'token' => $info['token'],
                'type' => 1,
            ),'post');
            if($info['code'] != 'R0000'){
                return $this->error('登录信息失效',U('Login/login'));
            } else {
                session('user_time',NOW_TIME); // 重新设置检测时间
            }
        }
    }
    
    /**
     * 自动登录
     * 
     * @return void
     * @author xiaoyutab<xiaoyutab@qq.com>
     * @version v1.0.0
     * @copyright (c) 2017, xiaoyutab
     * @adtime 2017-09-05 17:21:03
     */
    private function auto_login(){
        // 自动登录
        $info = cookie('u_info');
        $info = get_api_data('User/check_user_login',array(
            'uid' => $info['uid'],
            'token' => $info['token'],
            'type' => 1,
        ),'post');
        if($info['code'] != 'R0000'){
            return $this->error('登录信息失效',U('Login/login'));
        } else {
            session('user_time',NOW_TIME); // 重新设置检测时间
            $info = get_api_data('User/get_user_info',array(
                    'uid' => $info['uid'],
                    'token' => $info['token'],
                    'type' => 1,
                ),'post');
            session('user_info',$info['data']);// 有效时间到浏览器关闭
        }
    }
}