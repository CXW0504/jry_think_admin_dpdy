<?php

namespace Home\Model;

/**
 * 错误代码相关操作
 * 
 * @author xiaoyutab<xiaoyutab@qq.com>
 * @version v1.0.0
 * @copyright (c) 2017, xiaoyutab
 * @adtime 2017-08-08 21:03:41
 */
class ProjectErrorCodeModel extends \Common\Model\AllModel {

    /**
     * 获取错误代码及相关注释说明
     * 
     * @param number $id 需要获取错误代码的项目编号
     * @return array|boolean 查询到的值。如果查询不到则返回false
     * @author xiaoyutab<xiaoyutab@qq.com>
     * @version v1.0.0
     * @copyright (c) 2017, xiaoyutab
     * @adtime 2017-08-08 21:06:15
     */
    public function get_error_code_list($id = 0) {
        if ($id <= 0) {
            return FALSE;
        }
        $list = $this->where(array('pid' => $id, 'status' => array('neq', 98)))->select();
        if (empty($list)) {
            return FALSE;
        }
        $return_data = array();
        foreach ($list as $v) {
            $return_data[$v['name']] = $v['desc'];
        }
        return $return_data;
    }

}
