<?php
namespace Other\Controller;
use Other\Model\dateTool;

/**
 * other项目的主要操作方法
 *  主要作用为计算利息和保费信息
 * 
 * @author xiaoyutab<xiaoyutab@qq.com>
 * @version v1.0.0
 * @copyright (c) 2017, xiaoyutab
 * @adtime 2017-04-14 18:39:20
 */
class IndexController extends \Common\Controller\PublicController{
    
    /**
     * 构造函数
     * 作用：渲染bootstrap模板和jquery事件库
     * 
     * @author xiaoyutab<xiaoyutab@qq.com>
     * @version v1.0.0
     * @copyright (c) 2017, xiaoyutab
     * @adtime 2017-04-14 18:39:20
     */
    public function __construct() {
        parent::__construct();
        $this->wget('jquery')->wget('bootstrap');
    }

    /**
     * 首页页面展示
     * @return void
     * 
     * @author xiaoyutab<xiaoyutab@qq.com>
     * @version v1.0.0
     * @copyright (c) 2017, xiaoyutab
     * @adtime 2017-04-14 18:39:06
     */
    public function indexAction(){
        $this->wget('bootstrap-daterangepicker');
        return $this->display();
    }
    
    /**
     * 计算数据并渲染页面
     * @return void
     * 
     * @author xiaoyutab<xiaoyutab@qq.com>
     * @version v1.0.0
     * @copyright (c) 2017, xiaoyutab
     * @adtime 2017-04-14 19:38:55
     */
    public function CalculationAction(){
        $money = (int)I('post.money');
        if($money <= 0){
            return $this->error('贷款金额不能为空或者负数');
        }
        M('work_other_calculation')->add(array(
            'name' => I('post.uname'),
            'money' => (int)I('post.money'),
            'starttime' => dateTool::getIntTime(I('post.starttime')),
            'loan_type' => (int)I('post.loan_type'),
            'month' => (int)I('post.month'),
            'loan_channel' => (int)I('post.loan_channel'),
            'loan_number' => (int)I('post.loan_number'),
            'loan_charge' => (int)I('post.loan_charge'),
            'loan_rate' => (float)I('post.loan_rate'),
            'status' => 99,
            'ad_time' => time(),
        ));
        $my_service = I('post.money') * I('post.month') * 0.001;
        if(I('post.loan_charge') == 1){
            // 渠道点位一次性收取
            $s_service = I('post.money') * I('post.month') * (I('loan_rate') / 100 - 0.001);
        } else {
            // 渠道点位按月份收取
            $s_service = 0;
        }
        if(I('post.loan_type') == 1){
            // 一抵 0.85%
            $my_loan = I('post.money') * 0.85 / 100;
            if(I('post.loan_charge') == 1){
                // 已一次性收取
                $s_loan = 0;
            } else {
                // 分摊到每个月
                $s_loan = I('post.money') * ( I('post.loan_rate') - 0.85) / 100;
            }
        } else {
            // 二抵 1%
            $my_loan = I('post.money') * 0.01;
            if(I('post.loan_charge') == 1){
                // 已一次性收取
                $s_loan = 0;
            } else {
                // 分摊到每个月
                $s_loan = I('post.money') * ( I('post.loan_rate') - 1) / 100;
            }
        }
        $dateList = $this->getDateList(I('post.starttime'), I('post.month'));
        foreach($dateList as $k => $v){
            $dateList[$k]['my_loan'] = $my_loan;
            $dateList[$k]['s_loan'] = $s_loan;
            $dateList[$k]['all_loan'] = $my_loan + $s_loan;
            $dateList[$k]['money'] = 0;
            $dateList[$k]['all'] = $my_loan + $s_loan;
        }
        $dateList[$k]['my_loan'] = 0;
        $dateList[$k]['s_loan'] = 0;
        $dateList[$k]['money'] = I('post.money');
        $dateList[$k]['all_loan'] = 0;
        $dateList[$k]['all'] = I('post.money');
        $this->assign(array(
            'post' => I('post.'),
            'loan_type' => array(1=>'一抵','二抵'),
            'loan_channel' => array(1=>'有渠道','没有渠道'),
            'loan_charge' => array(1=>'服务费形式一次性收取','利息形式按月支付'),
            'qianqi' => array(
                'pinggu' => I('post.loan_number') * 200,
                'baoxian' => I('post.money') * 0.03 / 100,
                'gongzheng' => I('post.money') * 0.003,
                'weituo' => 1000 * I('post.loan_number'),
                'zhengjian' => 800 * I('post.loan_number'),
                'gongzheng_all' => I('post.money') * 0.003 + 1000 * I('post.loan_number') + 800 * I('post.loan_number'),
                'diya' => I('post.loan_number') * 80,
                'my_service' => $my_service,
                's_service' => $s_service,
                'all_service' => $my_service + $s_service,
                'my_loan' => $my_loan,
                's_loan' => $s_loan,
            ),
            'list_data' => $dateList,
        ));
        return $this->display();
    }
    
    /**
     * 获取还款计划时间表
     * @param string $start 开始时间
     * @param integer $month 贷款月份
     * @return array 生成的还款计划表
     * 
     * @author xiaoyutab<xiaoyutab@qq.com>
     * @version v1.0.0
     * @copyright (c) 2017, xiaoyutab
     * @adtime 2017-04-17 19:38:30
     */
    private function getDateList($start = '2017-04-10',$month = 12){
        $return_data = array();
        $intDate = dateTool::getIntTime($start,'Y-m-d');
        $day = dateTool::date($intDate,'d');
        $day = ( intval( $day / 5 ) + 1 ) * 5;
        if($day > 30){$day = 5;}
        $ti = 1;
        for($i = 1;$i <= $month;$i ++){
            $start = dateTool::date($intDate, 'Y-m-'.$day);
            $start = strtotime('+' . $i - $ti . ' month', strtotime($start));
            if($intDate > $start){
                $start = strtotime('+1 month', $start);
                $ti = 0;
            }
            if(dateTool::date($start,'d') % 5 != 0){
                $start = dateTool::date($start,'Y-m-05');
            } else {
                $start = dateTool::date($start,'Y-m-d');
            }
            $return_data[] = array(
                'start' => $start
            );
        }
        $return_data[] = array(
            'start' => dateTool::date(strtotime('-1 day',strtotime('+ ' . $month . ' month',$intDate)),'Y-m-d'),
        );
        return $return_data;
    }
}