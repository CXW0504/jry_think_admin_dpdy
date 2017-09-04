<?php
namespace Api\Controller;

/**
 * 建立一个空控制器,如果用户随意访问就自动返回错误代码
 * 
 * @author xiaoyutab<xiaoyutab@qq.com>
 * @version v1.0.0
 * @copyright (c) 2017, xiaoyutab
 * @adtime 2017-09-04 22:03:03
 */
class EmptyController extends ApiController{

    /**
     * 空操作的默认访问位置
     * 
     * @return json
     * @author xiaoyutab<xiaoyutab@qq.com>
     * @version v1.0.0
     * @copyright (c) 2017, xiaoyutab
     * @adtime 2017-09-04 22:03:39
     */
    public function indexAction(){
        return $this->returnCode('S0001');
    }
}