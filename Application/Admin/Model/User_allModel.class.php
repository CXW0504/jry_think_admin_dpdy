<?php

namespace Admin\Model;

/**
 * 后台用户查询/操作模型
 * 
 * @author xiaoyutab<xiaoyutab@qq.com>
 * @version v1.0.0
 * @copyright (c) 2017, xiaoyutab
 * @adtime 2017-08-01 18:27:05
 */
class User_allModel extends \Common\Model\AllModel {

    /**
     * 设置该模型操作的表名
     *      因为UserModel使用的PDO，无法使用ThinkPHP自带的搜索
     *      故此处新建一个_all的模型来处理日志记录信息
     * 
     * @return void
     * @author xiaoyutab<xiaoyutab@qq.com>
     * @version v1.0.0
     * @copyright (c) 2017, xiaoyutab
     * @adtime 2017-08-01 18:26:34
     */
    public function __construct() {
        parent::__construct('user');
    }

}
