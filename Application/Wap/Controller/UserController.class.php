<?php
namespace Wap\Controller;

/**
 * 用户中心模块
 * 
 * @author xiaoyutab<xiaoyutab@qq.com>
 * @version v1.0.2
 * @copyright (c) 2017, xiaoyutab
 * @adtime 2017-09-01 16:53:10
 */
class UserController extends CommonController{
    
    /**
     * 网站首页信息
     * 
     * @return void
     * @author xiaoyutab<xiaoyutab@qq.com>
     * @copyright (c) 2017, xiaoyutab
     * @adtime 2017-09-01 16:53:24
     */
    public function indexAction(){
    	return $this->display();
    }
}