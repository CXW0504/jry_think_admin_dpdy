<?php
namespace Api\Controller;
use Api\Model\AppModel;
use Api\Model\AppKeyModel;

/**
 * 网站系统相关接口
 * 
 * @author xiaoyutab<xiaoyutab@qq.com>
 * @version v1.0.0
 * @copyright (c) 2017, xiaoyutab
 * @adtime 2017-3-6 16:59:21
 */
class SystemController extends ApiController {

    /**
     * 授权接口,待更新。现功能为任意参数均已授权
     * 
     * return.token 可以直接比较的授权码
     * @author xiaoyutab<xiaoyutab@qq.com>
     * @version v1.0.0
     * @copyright (c) 2017, xiaoyutab
     * @adtime 2017-3-6 16:58:16
     */
    public function doaminAction() {
        return $this->returnCode(array(
            'token' => md5($domain.$other),
        ));
    }

}
