<?php
namespace Admin\Controller;


/**
 * 网站首页信息
 * 
 * @author xiaoyutab<xiaoyutab@qq.com>
 * @version v1.0.2
 * @copyright (c) 2017, xiaoyutab
 * @adtime 2017-07-05 16:41:11
 */
class IndexController extends CommonController{
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
     * 网站首页信息
     * 
     * @return void
     * @author xiaoyutab<xiaoyutab@qq.com>
     * @copyright (c) 2017, xiaoyutab
     * @adtime 2017-07-05 16:41:48
     */
    public function indexAction(){
    	return $this->display();
    }
    
    /**
     * 网站欢迎页面
     * 
     * @return void
     * @author xiaoyutab<xiaoyutab@qq.com>
     * @copyright (c) 2017, xiaoyutab
     * @adtime 2017-07-05 18:58:30
     */
    public function helloAction(){
        return $this->display();
    }
}