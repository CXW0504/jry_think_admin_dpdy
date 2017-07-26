<?php

namespace Admin\Model;
use Admin\Model\LogModel;

/**
 * 接口文档的项目管理模型
 * 
 * @author xiaoyutab<xiaoyutab@qq.com>
 * @version v1.0.0
 * @copyright (c) 2017, xiaoyutab
 * @adtime 2017-07-26 18:53:18
 */
class ProjectModel extends \Common\Model\AllModel {

    /**
     * 添加项目数据
     * 
     * @param array $data 要添加的数据
     * @return boolean 是否添加成功
     * @author xiaoyutab<xiaoyutab@qq.com>
     * @version v1.0.0
     * @copyright (c) 2017, xiaoyutab
     * @adtime 2017-07-26 18:54:01
     */
    public function create_info($data = array()) {
        if (empty($data)) {
            return FALSE;
        }
        if(empty($data['name'])){
            // 如果项目名称为空则返回失败
            return FALSE;
        }
        $data['ad_time'] = NOW_TIME;
        $success = $this->add($data);
        if($success){
            $log = new LogModel();
            $log->create_log('project', $success);
        }
        return (bool)$success;
    }

}
