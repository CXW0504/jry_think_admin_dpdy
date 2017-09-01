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
     * @author xiaoyutab<xiaoyutab@qq.com>
     * @copyright (c) 2017, xiaoyutab
     * @adtime 2017-09-01 16:56:24
     */
    public function __construct() {
        parent::__construct();
    }
    
    public function loginAction(){
        if(!I('post.')){
            return $this->display();
        }
    }
}