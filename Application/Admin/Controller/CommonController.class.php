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
     * @version v1.0.3
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
            // 如果用户登录了就进行判断当前操作有无权限访问
            C('TMPL_ACTION_ERROR','Common:ct_error');
            C('TMPL_ACTION_SUCCESS','Common:ct');
            $this->wget('jquery')->wget('jquery_pintuer')->css('Css/Admin/admin');
            $info = $user->get_user_info();
            if(in_array(CONTROLLER_NAME . '/' . ACTION_NAME, C('NO_JURISDICTION'))){
                // 如果是谁都有权限访问的模块
                return $this;
            } else if(in_array(CONTROLLER_NAME . '/' . ACTION_NAME, $info['content'])){
                // 如果用户有权限访问该模块
                return $this;
            } else {
                return $this->error('抱歉，您没有权限进行此操作',U('Index/hello'));
            }
        }
        return $this->error('登录信息失效，请重新登录', U('Login/login'));
    }
}
