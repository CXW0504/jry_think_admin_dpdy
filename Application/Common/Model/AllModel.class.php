<?php

namespace Common\Model;

use Think\Model;

/**
 * 该模型为公共继承模型
 * 封装有查询数量、获取列表、删除条目的方法
 * 使用该模型需要创建以下字段：
 *     status:98删除、99正常[tinyint类型]
 *     ad_time:添加时间[int类型]
 *     del_time:删除时间[int类型]
 *     备注：以上数据均为无符号类型
 * @adtime 2017-04-16 22:26:10
 */
class AllModel extends Model {
    /**
     * 查询数量
     * @param  integer $status 所查询的状态
     * @return number          查询到的数量
     * 
     * @author xiaoyutab<xiaoyutab@qq.com>
     * @version v1.0.0
     * @copyright (c) 2017, xiaoyutab
     * @adtime 2017-04-16 22:26:18
     */
    public function getCount($status = false) {
        if ($status === false)
            return $this->where(array('status' => array('neq', 98)))->count();
        return $this->where(array('status' => $status))->count();
    }

    /**
     * 获取列表
     * @param  integer $p0     开始条数
     * @param  integer $p1     查询条数
     * @param  boolean $status 查询状态，false为全部未删除的，数字为相应状态的文章
     * @param  string $order 排序规则，根据什么进行排序
     * @return array           查询到的列表数据
     * 
     * @author xiaoyutab<xiaoyutab@qq.com>
     * @version v1.0.2
     * @copyright (c) 2017, xiaoyutab
     * @adtime 2017-04-16 22:26:24
     */
    public function getList($p0 = 0, $p1 = 20, $status = false,$order = '`ad_time` DESC') {
        if ($status === false)
            return $this->where(array('status' => array('neq', 98)))->order($order)->limit($p0, $p1)->select();
        return $this->where(array('status' => $status))->order($order)->limit($p0, $p1)->select();
    }
    
    /**
     * 删除条目数据
     * 备注：该删除为模拟状态式删除，方便找回信息
     * 
     * @param  intval $id 要删除的文章分类编号
     * @return boolean    是否删除成功
     * 
     * @author xiaoyutab<xiaoyutab@qq.com>
     * @version v1.0.0
     * @copyright (c) 2017, xiaoyutab
     * @adtime 2017-04-16 22:26:29
     */
    public function setDelete($id = 1){
        if($id < 1){
            return false;
        }
        $success = $this->where(array('id'=>$id,'status'=>array('neq',98)))->save(array('status'=>98,'del_time'=>time()));
        return $success > 0;
    }
}