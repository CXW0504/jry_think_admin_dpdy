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
class UserController extends CommonController{
    /**
     * 获取权限组列表页面
     * 
     * @return void
     * @author xiaoyutab<xiaoyutab@qq.com>
     * @copyright (c) 2017, xiaoyutab
     * @adtime 2017-07-06 16:25:14
     */
    public function group_listAction(){
        return $this->display();
    }
}