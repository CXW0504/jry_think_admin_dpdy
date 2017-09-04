<?php
namespace Admin\Controller;
use Think\Verify;
use Admin\Model\UserModel;
use Admin\Model\UserLogModel;

/**
 * 网站登录页面信息
 * 
 * @author xiaoyutab<xiaoyutab@qq.com>
 * @version v1.0.2
 * @copyright (c) 2017, xiaoyutab
 * @adtime 2017-07-05 17:34:52
 */
class LoginController extends \Common\Controller\PublicController{
    /**
     * 构造函数类
     * 导入页面需要使用的jquery挂件和页面需要的样式表
     * 
     * @version v1.0.0
     * @author xiaoyutab<xiaoyutab@qq.com>
     * @copyright (c) 2017, xiaoyutab
     * @adtime 2017-07-05 16:41:19
     */
    public function __construct() {
        $this->wget('jquery')->wget('jquery_pintuer')->css('Css/Admin/admin');
        parent::__construct();
    }

    /**
     * 登录页面信息
     * 
     * @return void
     * @version v1.0.0
     * @author xiaoyutab<xiaoyutab@qq.com>
     * @copyright (c) 2017, xiaoyutab
     * @adtime 2017-07-05 16:41:48
     */
    public function loginAction(){
        if(I('post.')){
            $ver = new Verify();
            $ver->reset = false;
            if($ver->check(I('post.code'))){
                $user = new UserModel();
                // 查询用户的个人信息
                $info = $user->login(I('post.name'),I('post.password'));
                if($info){
                    session('admin.usertime',time());
                    session('admin.userinfo',$info);
                    session('admin.usertoken',md5(pass(I('post.password'), microtime ())));
                    // 设置用户登录日志
                    $user->set_user_login_log();
                    return $this->success('登录成功',U('Index/index'));
                }
                return $this->error('用户名/密码输入错误');
            }
            return $this->error('验证码输入错误');
            exit;
        }
    	return $this->display();
    }
    
    /**
     * 退出登录页面
     * 
     * @return void
     * @version v1.0.0
     * @author xiaoyutab<xiaoyutab@qq.com>
     * @copyright (c) 2017, xiaoyutab
     * @adtime 2017-07-06 10:26:45
     */
    public function logoutAction(){
        $user = new UserModel();
        $info = $user->get_user_info();
        $user->set_user_logout_log();
        $_SESSION = array();
        return $this->success('退出登录成功',U('Login/login'));
    }

    /**
     * 获取验证码信息
     *      备注：验证验证码时请使用(new Verify())->check('xxx')进行验证
     * 
     * @return void
     * @version v1.0.0
     * @author xiaoyutab<xiaoyutab@qq.com>
     * @copyright (c) 2017, xiaoyutab
     * @adtime 2017-07-05 18:08:48
     */
    public function get_passcodeAction(){
        $ver = new Verify();
        $ver->codeSet = '0123456789';
        $ver->bg = array(245,245,245);
        $ver->length = 4;
        $ver->useCurve = FALSE;
        $ver->useNoise = FALSE;
        $ver->fontttf = '9.ttf';
        $ver->entry();
    }
    
    /**
     * 用户修改自己的密码
     *     备注：如果修改成功会自动跳转到hello欢迎页面
     * 
     * @return void
     * @version v1.0.0
     * @author xiaoyutab<xiaoyutab@qq.com>
     * @copyright (c) 2017, xiaoyutab
     * @adtime 2017-07-12 17:06:41
     */
    public function set_passwordAction(){
        // 临时修改跳转页面地址
        C('TMPL_ACTION_ERROR','Common:ct_error');
        C('TMPL_ACTION_SUCCESS','Common:ct');
        if(!I('post.')){
            return $this->display();
        }
        $user = new UserModel();
        $info = $user->get_user_info();
        if($user->check_self_password(I('post.password'))){
            if($user->save_user_password($info['id'],I('post.new_password'))){
                return $this->success('修改成功',U('Index/hello'));
            }
            return $this->error('系统错误');
        }
        return $this->error('旧密码输入错误');
    }
}