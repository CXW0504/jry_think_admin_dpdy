<?php

namespace Home\Model;

/**
 * 接口文档的项目管理模型
 * 
 * @author xiaoyutab<xiaoyutab@qq.com>
 * @version v1.0.0
 * @copyright (c) 2017, xiaoyutab
 * @adtime 2017-07-30 15:52:13
 */
class ProjectModel extends \Common\Model\AllModel {

    /**
     * 獲取接口項目的詳細信息
     * 
     * @param number $id 項目編號
     * @return boolean 是否获取成功
     * @author xiaoyutab<xiaoyutab@qq.com>
     * @version v1.0.0
     * @copyright (c) 2017, xiaoyutab
     * @adtime 2017-07-30 15:53:47
     */
    public function get_project_info($id = 0) {
        if ($id <= 0) {
            return FALSE;
        }
        $info = $this->where(array('id'=>$id,'status' => array('neq',98)))->find();
        if(empty($info)){
            return FALSE;
        }
        return $info;
    }
}
