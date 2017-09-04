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
        $this->css(array(
            'Css/Wap/common','Css/Wap/login',
        ));
        parent::__construct();
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
        dump(I('post.'));
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