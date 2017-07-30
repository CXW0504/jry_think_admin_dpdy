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
    
    public function get_error_config($id = 0){
        return array(
            '0000' => '成功',
            '2056' => '系统错误',
            '3000' => '手机号已存在',
            '2057' => '用户未登录',
            '2101' => '邀请码错误',
            '2102' => "邀请码错误请重新输入\n或选择随机分配",
            '1000' => '参数不能为空',
            '1001' => '必填参数不能为空',
            '1002' => '图片传输错误',
            '1003' => '数据写入错误',
            '1004' => '流水号传输错误',
            '1005' => '重复操作',
            '1006' => '禁止操作',
        );
    }

}
