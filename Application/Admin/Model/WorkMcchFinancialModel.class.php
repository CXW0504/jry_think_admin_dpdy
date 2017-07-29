<?php

namespace Admin\Model;

use Common\Model\AllModel;
use Admin\Model\LogModel;

/**
 * 个人开发的公司米仓财行财务计算器工具
 * 财务管理软件
 * 计算的历史数据管理
 * 
 * @author xiaoyutab<xiaoyutab@qq.com>
 * @version v1.0.0
 * @copyright (c) 2017, xiaoyutab
 * @adtime 2017-07-29 12:44:26
 */
class WorkMcchFinancialModel extends AllModel {

    /**
     * 删除work_mcch_financial表数据操作
     * 
     * @param number $id 要删除的条目编号
     * @return boolean
     * @author xiaoyutab<xiaoyutab@qq.com>
     * @version v1.0.0
     * @copyright (c) 2017, xiaoyutab
     * @adtime 2017-07-29 12:47:03
     */
    public function delete_financial($id = 0) {
        if ($id <= 0) {
            return FALSE;
        }
        if ($this->setDelete($id)) {
            $log = new LogModel();
            $log->delete_log('work_mcch_financial', $id);
            return TRUE;
        }
        return FALSE;
    }
    
    /**
     * 整理数据取得还款计划表
     * 
     * @param number $id 要取得的编号
     * @return array 整理后的数组数据
     * @author xiaoyutab<xiaoyutab@qq.com>
     * @version v1.0.0
     * @copyright (c) 2017, xiaoyutab
     * @adtime 2017-07-29 12:58:23
     */
    public function arrangement_download($id = 0){
        if ($id <= 0) {
            return FALSE;
        }
        $info = $this->where(array('id'=>$id,'status'=>array('neq',98)))->find();
        $mcch_index = new \Mcch\Controller\IndexController();
        return $mcch_index->pcIndexAction($info, TRUE);
    }

}
