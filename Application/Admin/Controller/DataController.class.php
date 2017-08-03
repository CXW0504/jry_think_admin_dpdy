<?php

namespace Admin\Controller;

use Think\Page;
use Admin\Model\WorkMcchFinancialModel;

/**
 * 网站数据管理操作控制器
 * 
 * @author xiaoyutab<xiaoyutab@qq.com>
 * @version v1.0.0
 * @copyright (c) 2017, xiaoyutab
 * @adtime 2017-07-29 11:55:23
 */
class DataController extends CommonController {

    /**
     * 获取mcch的财务计算列表数据
     * 
     * @return void
     * @author xiaoyutab<xiaoyutab@qq.com>
     * @version v1.0.0
     * @copyright (c) 2017, xiaoyutab
     * @adtime 2017-07-29 12:37:06
     */
    public function mcch_data_listAction() {
        $this->wget('bootstrap')->wget('bootstrap-daterangepicker');
        if (I('get.times_end')) {
            $times = explode(' ~ ', I('get.times_end'));
            $where = array(
                'ad_time' => array(
                    array('gt', strtotime($times[0] . ' 00:00:01')), 
                    array('lt', strtotime($times[1] . ' 23:59:59'))
                )
            );
        } else {
            $where = array();
        }
        $work = new WorkMcchFinancialModel();
        $count = $work->where($where)->getCount();
        $page = new Page($count, 10);
        $page->setConfig('prev', '上一页');
        $page->setConfig('next', '下一页');
        $page->setConfig('header', '');
        $page->setPageHtml('top_html', '<div class="pagination" style="margin:0;"><ul>%PAGE_CONTENT_HTML%</ul></div>');
        $page->setPageHtml('normal_page_html', '<a href="%PAGE_HREF%" class="tcdNumber">%PAGE_NUMBER%</a>');
        $page->setPageHtml('current_page_html', '<span class="current">%CURRENT_PAGE_NUMBER%</span>');
        $page->setConfig('theme', '%UP_PAGE% %LINK_PAGE% %DOWN_PAGE%');
        $list = $work->where($where)->getList($page->firstRow, $page->listRows);
        return $this->assign(array(
                    'count' => $count,
                    'g_list' => $list,
                    'page' => $page->show(),
                ))->display();
    }
    
    /**
     * 删除数据操作
     * 
     * @return void
     * @author xiaoyutab<xiaoyutab@qq.com>
     * @version v1.0.0
     * @copyright (c) 2017, xiaoyutab
     * @adtime 2017-07-29 12:44:26
     */
    public function mcch_data_list_deleteAction(){
        $work = new WorkMcchFinancialModel();
        if($work->delete_financial(I('get.id'))){
            return $this->success('删除成功',U('mcch_data_list'));
        }
        return $this->error('删除失败');
    }
    
    /**
     * 导出MCCH数据列表
     * 
     * @return void
     * @author xiaoyutab<xiaoyutab@qq.com>
     * @version v1.0.0
     * @copyright (c) 2017, xiaoyutab
     * @adtime 2017-07-29 13:01:51
     */
    public function mcch_data_list_downloadAction(){
        $id = I('get.id');
        $work = new WorkMcchFinancialModel();
        $xlsData = $work->arrangement_download($id);
        if(!$xlsData){
            return $this->error('系统错误');
        }
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
        $this->exportExcel($xlsName, $xlsCell, $xlsData);
    }
}
