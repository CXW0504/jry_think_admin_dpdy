<?php

namespace Admin\Controller;

use Admin\Model\UserModel;

class CommonController extends \Common\Controller\PublicController {
    /**
     * 公用模板，方便权限控制或者登录验证
     * @return $this
     */
    public function __construct() {
        parent::__construct();
        if (empty($_SESSION)) {
            return $this->error('登录信息失效，请重新登录', U('Login/login'));
        }
        $user = new UserModel();
        if ($user->checkUser()) {
            return $this;
        }
        return $this->error('登录信息失效，请重新登录', U('Login/login'));
    }
}
