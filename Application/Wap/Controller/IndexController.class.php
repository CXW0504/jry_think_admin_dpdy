<?php
namespace Wap\Controller;

use Think\Controller;
/**
 * 网站首页信息Wap端
 * 
 * @author xiaoyutab<xiaoyutab@qq.com>
 * @version v1.0.2
 * @copyright (c) 2017, xiaoyutab
 * @adtime 2017-09-01 16:53:10
 */
class IndexController extends CommonController{
    /**
     * 构造函数类
     * 
     * @author xiaoyutab<xiaoyutab@qq.com>
     * @copyright (c) 2017, xiaoyutab
     * @adtime 2017-09-01 16:53:21
     */
    public function __construct() {
        parent::__construct();
    }
    
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