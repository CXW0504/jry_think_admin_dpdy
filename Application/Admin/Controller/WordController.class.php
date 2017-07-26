<?php

namespace Admin\Controller;

use Think\Page;
use Admin\Model\TimeRuleModel;

/**
 * 考勤相关操作
 * 
 * @author xiaoyutab<xiaoyutab@qq.com>
 * @version v1.0.0
 * @copyright (c) 2017, xiaoyutab
 * @adtime 2017-07-22 16:19:02
 */
class WordController extends CommonController {

    /**
     * 考勤规则列表
     * 
     * @return void 
     * @author xiaoyutab<xiaoyutab@qq.com>
     * @version v1.0.0
     * @copyright (c) 2017, xiaoyutab
     * @adtime 2017-07-22 16:20:21
     */
    public function settingAction() {
        $time_rule = new TimeRuleModel();
        $where = array(
            'name|addr' => array('like', '%' . I('get.keywords') . '%')
        );
        $count = $time_rule->where($where)->getCount();
        $page = new Page($count, 10);
        $page->setConfig('prev', '上一页');
        $page->setConfig('next', '下一页');
        $page->setConfig('header', '');
        $page->setPageHtml('normal_page_html', '<a href="%PAGE_HREF%" class="tcdNumber">%PAGE_NUMBER%</a>');
        $page->setPageHtml('current_page_html', '<span class="current">%CURRENT_PAGE_NUMBER%</span>');
        $page->setConfig('theme', '%UP_PAGE% %LINK_PAGE% %DOWN_PAGE%');
        $list = $time_rule->where($where)->getList($page->firstRow, $page->listRows);
        foreach ($list as $k => $v) {
            switch ($v['type']) {
                case '1':
                    $list[$k]['type_name'] = '抵押专员';
                    break;
                case '2':
                    $list[$k]['type_name'] = '调评专员';
                    break;
                default:
                    $list[$k]['type_name'] = '全体前端用户';
                    break;
            }
        }
        return $this->assign(array(
                    'count' => $count,
                    't_list' => $list,
                    'page' => $page->show(),
                ))->display();
    }

    /**
     * 添加考勤规则信息
     * 
     * @return void 
     * @author xiaoyutab<xiaoyutab@qq.com>
     * @version v1.0.0
     * @copyright (c) 2017, xiaoyutab
     * @adtime 2017-07-23 17:06:45
     */
    public function add_timecardAction() {
        $time_rule = new TimeRuleModel();
        if (!I('post.')) {
            return $this->display();
        }
        if ($time_rule->create_time_card(I('post.'))) {
            return $this->success('添加成功', U('setting'));
        }
        return $this->error('添加失败');
    }

    /**
     * 删除考勤规则信息
     * 
     * @return void
     * @author xiaoyutab<xiaoyutab@qq.com>
     * @version v1.0.0
     * @copyright (c) 2017, xiaoyutab
     * @adtime 2017-07-13 11:12:18
     */
    public function del_timecardAction() {
        $time_rule = new TimeRuleModel();
        if ($time_rule->delete_timecard(I('get.id'))) {
            return $this->success('删除成功');
        }
        return $this->error('删除失败');
    }

    /**
     * 考勤规则信息
     * 
     * @return void
     * @version v1.0.0
     * @author xiaoyutab<xiaoyutab@qq.com>
     * @copyright (c) 2017, xiaoyutab
     * @adtime 2017-07-23 18:43:04
     */
    public function save_timecardAction() {
        $time_rule = new TimeRuleModel();
        $info = $time_rule->get_info(I('get.id'));
        if (empty($info)) {
            return $this->error('查无此数据');
        }
        if (!I('post.')) {
            $this->assign('g_info', $info);
            return $this->display('add_timecard');
        }
        if ($time_rule->save_timecard(I('get.id'), I('post.'))) {
            return $this->success('修改成功', U('setting'));
        }
        return $this->error('修改失败');
    }

    /**
     * 导出考勤规则列表
     * 
     * @return void 
     * @author xiaoyutab<xiaoyutab@qq.com>
     * @version v1.0.0
     * @copyright (c) 2017, xiaoyutab
     * @adtime 2017-07-23 18:55:00
     */
    public function out_timecardAction() {
        $time_rule = new TimeRuleModel();
        $where = array(
            'name|addr' => array('like', '%' . I('get.keywords') . '%')
        );
        $count = $time_rule->where($where)->getCount();
        $list = $time_rule->where($where)->getList(0, $count);
        foreach ($list as $k => $v) {
            switch ($v['type']) {
                case '1':
                    $list[$k]['type_name'] = '抵押专员';
                    break;
                case '2':
                    $list[$k]['type_name'] = '调评专员';
                    break;
                default:
                    $list[$k]['type_name'] = '全体前端用户';
                    break;
            }
            $list[$k]['ad_time'] = date('Y-m-d H:i:s', $v['ad_time']);
            $list[$k]['distance'] = $v['distance'] . '米';
            $list[$k]['morning_time'] = date('H:i', strtotime($v['morning_time']));
            $list[$k]['afternoon_time'] = date('H:i', strtotime($v['afternoon_time']));
        }
        $title = array(
            array('id', '编号', 8),
            array('name', '规则名称', 30),
            array('addr', '考勤地址', 60),
            array('coordinate', '考勤规则经纬度', 20),
            array('distance', '考勤有效距离'),
            array('morning_time', '上班时间'),
            array('afternoon_time', '下班时间'),
            array('type_name', '针对用户类型', 14),
            array('ad_time', '创建时间', 17),
        );
        // 导出表格并指引到下载项
        return $this->exportExcel('考勤规则列表', $title, $list, '考勤规则列表');
    }

    /**
     * 经纬度帮助信息
     * 
     * @return void 
     * @author xiaoyutab<xiaoyutab@qq.com>
     * @version v1.0.0
     * @copyright (c) 2017, xiaoyutab
     * @adtime 2017-07-23 19:53:12
     */
    public function help_timecard_coordinateAction() {
        return $this->display();
    }

}
