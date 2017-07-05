<?php
namespace Admin\Controller;
use Think\Verify;

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
     * @author xiaoyutab<xiaoyutab@qq.com>
     * @copyright (c) 2017, xiaoyutab
     * @adtime 2017-07-05 16:41:48
     */
    public function loginAction(){
        if(I('post.')){
            $ver = new Verify();
            if($ver->check(I('post.code'))){
                return $this->success('登录成功',U('Index/index'));
            }
            return $this->error('验证码输入错误');
            exit;
        }
    	return $this->display();
    }
    
    /**
     * 获取验证码信息
     *      备注：验证验证码时请使用(new Verify())->check('xxx')进行验证
     * 
     * @return void
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
}