<?php
namespace Mcch\Model;
use Think\Model;

/**
 * 借款财务处理类
 * 
 * @author xiaoyutab<xiaoyutab@qq.com>
 * @adtime 2017-03-20 18:36:49
 * @version v1.0.0
 * @copyright (c) 2017, xiaoyutab
 */
class WorkMcchFinancialModel extends Model{
    /**
     * 构造函数类
     * 作用为设置当前操作表名
     * 当前操作表：pic_type
     * 
     * @author xiaoyutab<xiaoyutab@qq.com>
     * @version v1.0.0
     * @copyright (c) 2017, xiaoyutab
     * @adtime 2017-03-20 18:37:49
     */
    public function __construct() {
        parent::__construct('work_mcch_financial');
    }

    /**
     * 添加借款信息操作
     * @param integer $money     借款金额，单位：元，整数
     * @param integer $st_time   开始时间，时间戳格式，整数
     * @param integer $type_loan 抵押类型，1一抵，2二抵
     * @param integer $type      借款方，1五矿，2中信
     * @param integer $month     借款时间，单位：月，最大不超过250月
     * @param integer $end_time  结束时间，时间戳格式，整数
     * @return integer 			 添加的信息编号
     */
    public function addFinancial($money = 10000,$st_time = 100,$type_loan = 1,$type = 1,$month = 2,$end_time = 101){
    	return $this->add(array(
    		'money' => intval($money),
    		'st_time' => intval($st_time),
    		'type' => intval($type),
    		'type_loan' => intval($type_loan),
    		'month' => intval($month),
    		'end_time' => intval($end_time),
    		'status' => 99,
    		'ad_time' => time(),
		));
    }

    /**
     * 获取借款详细信息操作
     * @param  integer $id 借款编号
     * @return array       查询到的信息
     */
    public function getInfo($id = 1){
    	return $this->where(array('id'=>$id))->find();
    }

}