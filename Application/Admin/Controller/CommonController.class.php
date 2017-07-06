<?php
namespace Admin\Controller;
use Admin\Model\UserModel;

/**
 * 后台公共模块
 * 包含有后台的授权信息、验证权限信息等
 * 
 * @author xiaoyutab<xiaoyutab@qq.com>
 * @version v1.0.2
 * @copyright (c) 2017, xiaoyutab
 * @adtime 2017-07-05 12:41:11
 */
class CommonController extends \Common\Controller\PublicController {
    /**
     * 公用模板，方便权限控制或者登录验证
     * @return $this
     * 
     * @author xiaoyutab<xiaoyutab@qq.com>
     * @version v1.0.2
     * @copyright (c) 2017, xiaoyutab
     * @adtime 2017-07-05 12:50:11
     */
    public function __construct() {
        parent::__construct();
        if (empty($_SESSION)) {
            return $this->error('登录信息失效，请重新登录', U('Login/login'));
        }
        $user = new UserModel();
        if ($user->checkUser()) {
            $this->wget('jquery')->wget('jquery_pintuer')->css('Css/Admin/admin');
            return $this;
        }
        return $this->error('登录信息失效，请重新登录', U('Login/login'));
    }
}
