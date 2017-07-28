<?php

namespace Mcch\Controller;

use Common\Controller\PublicController;
use Mcch\Model\WorkMcchFinancialModel;

/**
 * Index控制器，控制测试网站的首页信息
 * 放置公司内部的项目信息
 * 
 * @author xiaoyutab<xiaoyutab@qq.com>
 * @version v1.0.2
 * @copyright (c) 2017, xiaoyutab
 * @adtime 2017-03-16 19:17:36
 * @uptime 2017-04-17 13:12:37 @ add getDateAction oldversion 1.0.0
 * @uptime 2017-04-17 13:59:26 @ update getDateAction old version 1.0.1
 */
class IndexController extends PublicController {
    /**
     * 获取时间方法
     * 该方法获取结束时间后自动退出程序
     * 
     * @author xiaoyutab<xiaoyutab@qq.com>
     * @version v1.0.1
     * @copyright (c) 2017, xiaoyutab
     * @adtime 2017-04-17 13:10:18
     * @uptime 2017-04-17 13:58:39 @ update 修复2月31日的问题
     */
    public function getDateAction(){
        $get = I('get.');
        if($get['type'] == 1){
            $days = '-10';
            if(date('d', strtotime($get['time'])) >= 25){
                $get['month'] ++;
            }
        } else {
            $days = date('-d', strtotime($get['time']));
        }
        $times = date('Y-m'.$days, strtotime($get['time']));
        $timeint = strtotime('+'.$get['month'].' month',strtotime($times));
        $lastTimeInt = strtotime(date('Y-m-01',strtotime('+'.$get['month'] + 1 .' month',strtotime($times))));
        if($timeint >= $lastTimeInt){
            $timeint = strtotime(date('Y-m-01', $lastTimeInt) . ' -1 day');
        }
        echo date('Y-m-d',$timeint);
        exit;
    }
    
    /**
     * 生成还款计划表
     * 生成还款计划表后自动下载excel文件
     * 
     * @author xiaoyutab<xiaoyutab@qq.com>
     * @version v1.0.0
     * @copyright (c) 2017, xiaoyutab
     * @adtime 2017-03-18 13:10:18
     */
    public function pcIndexExcelAction() {
        $id = I('get.id');
        $xlsName = "还款计划表";
        $xlsCell = array(
            array('number', '期数'),
            array('start_times', '开始时间'),
            array('end_times', '截止时间'),
            array('pay_days', '计息天数'),
            array('month', '月份'),
            array('pay_times', '还款日期'),
            array('pay_interest_on_a_loan', '支付利息'),
            array('pay_myself', '支付本金'),
            array('pay_money', '应还金额')
        );
        $work = new WorkMcchFinancialModel();
        $xlsData = $this->pcIndexAction($work->getInfo($id), true);
        $this->exportExcel($xlsName, $xlsCell, $xlsData);
    }

    /**
     * 构造函数，定义网站的icon图标及网站的一些基本信息
     * 继承HOME模块下的Public控制器，用来直接渲染HTML页面
     * 
     * @author xiaoyutab<xiaoyutab@qq.com>
     * @version v1.0.0
     * @copyright (c) 2017, xiaoyutab
     * @adtime 2017-03-16 19:27:42
     */
    public function __construct() {
        parent::__construct();
        $this->wget('bootstrap')->wget('jquery')->wget('bootstrap-daterangepicker');
    }

    /**
     * PC端的财务计算页面
     * @return void
     * 
     * @author xiaoyutab<xiaoyutab@qq.com>
     * @version v1.0.0
     * @copyright (c) 2017, xiaoyutab
     * @adtime 2017-03-18 13:10:18
     */
    public function pcIndexAction($post = false, $type_return = false) {
        // 如果没有传入post值就直接渲染填表页面
        if (!$type_return) {
            if (!I('post.'))
                return $this->display('index');
        }
        if (!$post) {
            $post = I('post.');
            // 如果没有传入初始值就设置为默认值
            if (empty($post['date'])) {
                $post['date'] = date('Y-m-d');
            } else {
                $post['date'] = date('Y-m-d', strtotime($post['date']));
            }
            // 设置默认结束时间
            if (empty($post['end_date'])) {
                if ($post['type'] == 1) {
                    $post['end_date'] = date('Y-m-10', strtotime('+2 month'));
                } else {
                    $post['end_date'] = date('Y-m-d', strtotime('+2 month'));
                }
            } else {
                $post['end_date'] = date('Y-m-d', strtotime($post['end_date']));
            }
            // 插入数据库数据
            $work = new WorkMcchFinancialModel();
            $content_id = $work->addFinancial($post['money'], strtotime($post['date']), $post['type_loan'], $post['type'], $post['month'], strtotime($post['end_date']));
        } else {
            $post['date'] = date('Y-m-d', $post['st_time']);
            $post['end_date'] = date('Y-m-d', $post['end_time']);
        }
        // 计算服务费
        if ($post['type_loan'] == 1) {
            // xiaoyutab于2017-04-12 15:06:56修改
            // 一抵服务费利率修改
            // 修改原因：服务费利率修改，由原来的一抵1%修改为2月3%,3月2%，半年和一年1%
            // 服务费计算方式
            switch ($post['month']) {
                case 2:
                    $service_money = round($post['money'] * 0.03 / 12 * $post['month'] + 80, 2);
                    break;
                case 3:
                    $service_money = round($post['money'] * 0.02 / 12 * $post['month'] + 80, 2);
                    break;
                default:
                    $service_money = round($post['money'] * 0.01 / 12 * $post['month'] + 80, 2);
                    break;
            }
        } else {
            // xiaoyutab于2017-04-12 15:06:56修改
            // 二抵服务费利率修改
            // 修改原因：服务费利率修改，由原来的一抵3%修改为2月9%,3月6%，半年和一年3%
            // 服务费计算方式
            switch ($post['month']) {
                case 2:
                    $service_money = round($post['money'] * 0.09 / 12 * $post['month'] + 80, 2);
                    break;
                case 3:
                    $service_money = round($post['money'] * 0.06 / 12 * $post['month'] + 80, 2);
                    break;
                default:
                    $service_money = round($post['money'] * 0.03 / 12 * $post['month'] + 80, 2);
                    break;
            }
        }
        // 还款日期生成器
        $service_date = $this->gotime($post['date'], $post['end_date'], 'month', 10);
        $days = $post['type'] == 1 ? 365 : 360;
        // 计算每个月应还利息
        $all_loan_money = 0; // 总还款利息
        foreach ($service_date as $k => $v) {
            if ($post['type_loan'] == 1) {
                $service_date[$k]['pay_interest_on_a_loan'] = $this->payInterestOnALoan($post['money'], $post['month'], $days, $v['pay_days']);
                $all_loan_money = $all_loan_money + $service_date[$k]['pay_interest_on_a_loan'];
            } else {
                $service_date[$k]['pay_interest_on_a_loan'] = $this->payInterestOnALoan($post['money'], $post['month'], $days, $v['pay_days']);
                $all_loan_money = $all_loan_money + $service_date[$k]['pay_interest_on_a_loan'];
            }
            $service_date[$k]['pay_myself'] = 0;
        }
        // 最后一期还款本金金额
        $service_date[$k]['pay_myself'] = $post['money'];
        // 计算保费
        $keno_day = $this->dayTimeDifferent($post['date'], $post['end_date']);
        if ($keno_day > 365) {
            $keno_day = 365;
        }
        $premium = round(( $post['money'] + $all_loan_money ) * 0.008 / 365 * $keno_day, 2);
        if ($type_return) {
            $data = array();
            foreach ($service_date as $k => $v) {
                $data[] = array(
                    'number' => $k + 1,
                    'start_times' => $v['start_times'],
                    'end_times' => $v['end_times'],
                    'pay_days' => $v['pay_days'] . ' 天',
                    'month' => date('m', strtotime('-1 month', strtotime($v['pay_times']))) . ' 月份',
                    'pay_times' => $v['pay_times'],
                    'pay_interest_on_a_loan' => $v['pay_interest_on_a_loan'] . ' 元',
                    'pay_myself' => $v['pay_myself'] . ' 元',
                    'pay_money' => number_format($v['pay_interest_on_a_loan'] + $v['pay_myself'], 2) . ' 元',
                );
            }
            return $data;
        }
        $this->assign(array(
            'service_money' => $service_money, // 服务费
            'form' => $post, // 提交过来的表单
            'type_loan' => array(1 => '一抵', '二抵'),
            'id' => $content_id,
            'type' => array(1 => '五矿', '中信'),
            'premium' => $premium,
            'all_loan_days' => $this->dayTimeDifferent($post['date'], $post['end_date']) - 1, // 用款天数
            'all_loan_money' => $all_loan_money, // 利息总计
            'service_date' => $service_date, // 还款计划表
            'lilv' => array(2 => '8.2', '8.2', 6 => '7.7', 12 => '7.2'),
        ));
        $this->display('excel_loan_premium_pc');
    }

    /**
     * 财务填写页面的控制器
     * @return void 
     * 
     * @author xiaoyutab<xiaoyutab@qq.com>
     * @version v1.0.0
     * @copyright (c) 2017, xiaoyutab
     * @adtime 2017-03-16 19:27:50
     */
    public function indexAction() {
        // 如果没有传入post值就直接渲染填表页面
        if (!I('post.'))
            return $this->display();
        $post = I('post.');
        // 如果没有传入初始值就设置为默认值
        if (empty($post['date'])) {
            $post['date'] = date('Y-m-d');
        } else {
            $post['date'] = date('Y-m-d', strtotime($post['date']));
        }
        // 设置默认结束时间
        if (empty($post['end_date'])) {
            if ($post['type'] == 1) {
                $post['end_date'] = date('Y-m-10', strtotime('+2 month'));
            } else {
                $post['end_date'] = date('Y-m-d', strtotime('+2 month'));
            }
        } else {
            $post['end_date'] = date('Y-m-d', strtotime($post['end_date']));
        }
        // 计算服务费
        if ($post['type_loan'] == 1) {
            // xiaoyutab于2017-04-12 15:06:56修改
            // 一抵服务费利率修改
            // 修改原因：服务费利率修改，由原来的一抵1%修改为2月3%,3月2%，半年和一年1%
            // 服务费计算方式
            switch ($post['month']) {
                case 2:
                    $service_money = round($post['money'] * 0.03 / 12 * $post['month'] + 80, 2);
                    break;
                case 3:
                    $service_money = round($post['money'] * 0.02 / 12 * $post['month'] + 80, 2);
                    break;
                default:
                    $service_money = round($post['money'] * 0.01 / 12 * $post['month'] + 80, 2);
                    break;
            }
        } else {
            // xiaoyutab于2017-04-12 15:06:56修改
            // 二抵服务费利率修改
            // 修改原因：服务费利率修改，由原来的一抵3%修改为2月9%,3月6%，半年和一年3%
            // 服务费计算方式
            switch ($post['month']) {
                case 2:
                    $service_money = round($post['money'] * 0.09 / 12 * $post['month'] + 80, 2);
                    break;
                case 3:
                    $service_money = round($post['money'] * 0.06 / 12 * $post['month'] + 80, 2);
                    break;
                default:
                    $service_money = round($post['money'] * 0.03 / 12 * $post['month'] + 80, 2);
                    break;
            }
        }
        // 还款日期生成器
        $service_date = $this->gotime($post['date'], $post['end_date'], 'month', 10);
        $days = $post['type'] == 1 ? 365 : 360;
        // 计算每个月应还利息
        $all_loan_money = 0; // 总还款利息
        foreach ($service_date as $k => $v) {
            if ($post['type_loan'] == 1) {
                $service_date[$k]['pay_interest_on_a_loan'] = $this->payInterestOnALoan($post['money'], $post['month'], $days, $v['pay_days']);
                $all_loan_money = $all_loan_money + $service_date[$k]['pay_interest_on_a_loan'];
            } else {
                $service_date[$k]['pay_interest_on_a_loan'] = $this->payInterestOnALoan($post['money'], $post['month'], $days, $v['pay_days']);
                $all_loan_money = $all_loan_money + $service_date[$k]['pay_interest_on_a_loan'];
            }
        }
        // 最后一期还款金额
        $service_date[$k]['pay_interest_on_a_loan'] = $service_date[$k]['pay_interest_on_a_loan'] + $post['money'];
        // 计算保费
        $keno_day = $this->dayTimeDifferent($post['date'], $post['end_date']);
        if ($keno_day > 365) {
            $keno_day = 365;
        }
        $premium = round(( $post['money'] + $all_loan_money ) * 0.008 / 365 * $keno_day, 2);
        $this->assign(array(
            'service_money' => $service_money, // 服务费
            'form' => $post, // 提交过来的表单
            'type_loan' => array(1 => '一抵', '二抵'),
            'type' => array(1 => '五矿', '中信'),
            'premium' => $premium,
            'all_loan_days' => $this->dayTimeDifferent($post['date'], $post['end_date']) - 1, // 用款天数
            'all_loan_money' => $all_loan_money, // 利息总计
            'service_date' => $service_date, // 还款计划表
        ));
        $this->display('excel_loan_premium');
    }

    /**
     * 还款月份生成器
     * @param  string $start_time 开始时间，格式：Y-m-d
     * @param  string $endtime    结束时间，格式：Y-m-d
     * @param  string $times      时间步长单位，默认月month，可选项：日day，月month，年year
     * @param  string $day        还款日，默认借款日
     * @return array              生成的还款计划表数组
     * 
     * @author xiaoyutab<xiaoyutab@qq.com>
     * @version v1.0.0
     * @copyright (c) 2017, xiaoyutab
     * @adtime 2017-03-18 13:10:18
     */
    private function gotime($start_time, $endtime, $times = 'month', $day = false) {
        // 初始化还款日
        if (!$day) {
            $day = date('d', strtotime($start_time));
        }
        $return_array = array();
        for ($i = 1; $i <= 36; $i ++) {
            $next_time = date('Y-m-' . $day, strtotime('+1' . $times, strtotime($start_time)));
            if($this->dayTimeDifferent($start_time, date('Y-m-d', strtotime('-1 day', strtotime($next_time)))) > 32){
                $next_time = date('Y-m-d', strtotime('-1 '.$times, strtotime($next_time)));
            }
            if (strtotime($next_time) <= strtotime($endtime)) {
                $return_array[] = array(
                    'start_times' => $start_time,
                    'end_times' => date('Y-m-d', strtotime('-1 day', strtotime($next_time))),
                    'pay_times' => $next_time,
                    'pay_days' => $this->dayTimeDifferent($start_time, date('Y-m-d', strtotime('-1 day', strtotime($next_time)))),
                );
                $start_time = $next_time;
            } else {
                if (strtotime($start_time) < strtotime($endtime)) {
                    $return_array[] = array(
                        'start_times' => $start_time,
                        'end_times' => date('Y-m-d', strtotime('-1 day', strtotime($endtime))),
                        'pay_times' => $endtime,
                        'pay_days' => $this->dayTimeDifferent($start_time, date('Y-m-d', strtotime('-1 day', strtotime($endtime)))),
                    );
                }
                return $return_array;
            }
        }
        return $return_array;
    }

    /**
     * 计算时间相差天数
     * @param  string $start 开始时间，格式：Y-m-d
     * @param  string $end   结束时间，格式：Y-m-d
     * @return intval        相差的天数，有可能为负数值
     * 
     * @author xiaoyutab<xiaoyutab@qq.com>
     * @version v1.0.0
     * @copyright (c) 2017, xiaoyutab
     * @adtime 2017-03-16 13:10:18
     */
    private function dayTimeDifferent($start, $end) {
        return floor((strtotime($end) - strtotime($start)) / 86400) + 1;
    }

    /**
     * 计算每月利息
     * @param  integer $money 借款总金额
     * @param  integer $month 借款月数，档位分为2、3、6、12
     * @param  integer $days  每年计算天数
     * @param  integer $day   计息天数
     * @return float          计算出来的金额，保留两位小数
     * 
     * @author xiaoyutab<xiaoyutab@qq.com>
     * @version v1.0.0
     * @copyright (c) 2017, xiaoyutab
     * @adtime 2017-03-16 13:10:18
     */
    public function payInterestOnALoan($money = 10000, $month = 2, $days = 365, $day = 30) {
        switch ($month) {
            case '2':
            case '3':
                $month = 0.082;
                break;
            case '6':
                $month = 0.077;
                break;
            case '12':
                $month = 0.072;
                break;
            default:
                return 'Unbeknown';
                break;
        }
        return round($money * $month / $days * $day, 2);
    }

}
