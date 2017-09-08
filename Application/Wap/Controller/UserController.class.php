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
class UserController extends CommonController {

    /**
     * 用户个人中心模块
     * 
     * @return void
     * @author xiaoyutab<xiaoyutab@qq.com>
     * @version v1.0.0
     * @copyright (c) 2017, xiaoyutab
     * @adtime 2017-09-05 17:33:16
     */
    public function infoAction() {
        dump($_SESSION);
        return $this->display();
    }

}
