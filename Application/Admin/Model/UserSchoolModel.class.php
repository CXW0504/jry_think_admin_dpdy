<?php

namespace Admin\Model;

use Admin\Model\LogModel;

/**
 * 获取用户学校相关操作模型
 * 
 * @author xiaoyutab<xiaoyutab@qq.com>
 * @version v1.0.0
 * @copyright (c) 2017, xiaoyutab
 * @adtime 2017-08-03 14:08:53
 */
class UserSchoolModel extends \Common\Model\AllModel {

    /**
     * 创建用户学校城市信息
     * 
     * @param string $name 学校所在省市名称
     * @return boolean
     * @author xiaoyutab<xiaoyutab@qq.com>
     * @version v1.0.0
     * @copyright (c) 2017, xiaoyutab
     * @adtime 2017-08-03 14:09:20
     */
    public function create_user_office_data($name = '') {
        if (empty($name)) {
            return FALSE;
        }
        $success = $this->add(array(
            'name' => trim($name),
            'fid' => 0,
            'ad_time' => NOW_TIME,
            'status' => 99,
            'del_time' => 0,
        ));
        if ($success) {
            $log = new LogModel();
            $log->create_log('user_school', $success);
            return TRUE;
        }
        return FALSE;
    }

}
