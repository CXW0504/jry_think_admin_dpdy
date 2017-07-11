<?php

namespace Admin\Controller;

/**
 * 网站订单管理方法，集合了订单的各种操作
 * 
 * @author xiaoyutab<xiaoyutab@qq.com>
 * @version v1.0.0
 * @copyright (c) 2017, xiaoyutab
 * @adtime 2017-07-10 10:25:56
 */
class LoanController extends CommonController {

    /**
     * 分配订单列表页面
     *      内部包含所有已分配且未进行处理[未上传公正/抵押时间的订单]的订单
     *      附加添加订单功能
     * 
     * @return void
     * @author xiaoyutab<xiaoyutab@qq.com>
     * @version v1.0.0
     * @copyright (c) 2017, xiaoyutab
     * @adtime 2017-07-10 10:30:37
     */
    public function distributionAction() {
        $this->wget('bootstrap')->wget('bootstrap-daterangepicker');
        return $this->display();
    }

}
