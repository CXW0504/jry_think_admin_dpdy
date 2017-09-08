<?php
namespace Wap\Controller;

/**
 * 网站首页登录操作
 * 
 * @author xiaoyutab<xiaoyutab@qq.com>
 * @version v1.0.2
 * @copyright (c) 2017, xiaoyutab
 * @adtime 2017-09-01 16:56:21
 */
class LoginController extends \Common\Controller\PublicController{
    /**
     * 构造函数类
     * 
     * @return void
     * @author xiaoyutab<xiaoyutab@qq.com>
     * @version v1.0.0
     * @copyright (c) 2017, xiaoyutab
     * @adtime 2017-09-01 16:56:24
     */
    public function __construct() {
        parent::__construct();
        // 登录页面样式
        $this->css('Css/Wap/login');
        C('LAYOUT_NAME','Common/layout_login');
    }
    
    /**
     * 注册操作
     * 
     * @return void
     * @author xiaoyutab<xiaoyutab@qq.com>
     * @version v1.0.0
     * @copyright (c) 2017, xiaoyutab
     * @adtime 2017-09-01 23:25:39
     */
    public function regAction(){
        if(!I('post.')){
            return $this->display();
        }
    }
    
    /**
     * 登录操作
     * 
     * @return void
     * @author xiaoyutab<xiaoyutab@qq.com>
     * @version v1.0.0
     * @copyright (c) 2017, xiaoyutab
     * @adtime 2017-09-01 23:07:11
     */
    public function loginAction(){
        if(!I('post.')){
            return $this->display();
        }
        $info = get_api_data('User/login',array(
            'username' => I('post.username'),
            'password' => md5(I('post.password')),
            'type' => 1,
        ),'post');
        if($info['code'] != 'R0000'){
            $this->error($info['msg']);
        }
        if(I('post.auto_login')){
            // 如果选中了自动登录
            cookie('u_info',array(
                'uid' =>  $info['data']['uid'],// 用户编号
                'token' => $info['data']['token'],// 设置用户登录token
                'username' => $info['data']['username'],// 设置登录用户名
            ),1536000);// 一年有效时间
        }
        $_SESSION['user_info'] = $info['data'];// 有效时间到浏览器关闭
        $_SESSION['user_time'] = NOW_TIME;// 本次检测token时间
        return $this->success('登录成功',U('User/info'));
    }

    /**
     * 登录欢迎页面
     * 
     * @return void
     * @author xiaoyutab<xiaoyutab@qq.com>
     * @version v1.0.0
     * @copyright (c) 2017, xiaoyutab
     * @adtime 2017-09-01 23:07:07
     */
    public function indexAction(){
        return $this->display();
    }
}