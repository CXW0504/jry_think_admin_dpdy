<?php
namespace Admin\Controller;
use Admin\Model\UserLogModel;
use Admin\Model\User_allModel;
use Admin\Model\UserInfoModel;
use Think\Page;
use Admin\Model\LogInfoModel;

/**
 * 网站日志查看控制器
 * 
 * @author xiaoyutab<xiaoyutab@qq.com>
 * @version v1.0.0
 * @copyright (c) 2017, xiaoyutab
 * @adtime 2017-08-01 18:00:31
 */
class LogController extends CommonController{
    /**
     * 后台登录日志查看页面
     * 
     * @return void
     * @author xiaoyutab<xiaoyutab@qq.com>
     * @version v1.0.0
     * @copyright (c) 2017, xiaoyutab
     * @adtime 2017-08-01 18:02:40
     */
    public function loginAction(){
        $where = array('user_type' => 1);// 设置只检索登录日志
        $log = new UserLogModel();
        $user = new User_allModel();
        $log_info = new LogInfoModel();
        if(I('get.keywords')){
            $ids = array();
            $wheres = array();
            foreach(explode(' ', I('get.keywords')) as $v){
                $wheres[] = '%'.$v.'%';
            }
            $select = $user->where(array(
                'username|phone|email' => array('like',$wheres,'OR'),
            ))->select();
            foreach($select as $v){
                $ids[] = $v['id'];
            }
            // 如果没查询出来该用户，讲用户设置为0，防止出现SQL错误
            if(empty($ids)){
                $ids = array(0);
            }
            $log_info_sel = $log_info->get_ids($wheres);
            foreach($log_info_sel as $v){
                $log_info_sel_id[] = $v['id'];
            }
            // 如果没查询出来该用户，讲用户设置为0，防止出现SQL错误
            if(empty($log_info_sel_id)){
                $log_info_sel_id = array(0);
            }
            $where[] = array(
                'uid' => array('in',$ids),
                'ip_city|ad_ip' => array('in',$log_info_sel_id),
                '_logic' => 'or',
            );
        }
        $count = $log->where($where)->getCount();
        $page = new Page($count, 10);
    	$page->setConfig('prev','上一页');
    	$page->setConfig('next','下一页');
    	$page->setConfig('header','');
    	$page->setPageHtml('normal_page_html','<a href="%PAGE_HREF%" class="tcdNumber">%PAGE_NUMBER%</a>');
    	$page->setPageHtml('current_page_html','<span class="current">%CURRENT_PAGE_NUMBER%</span>');
    	$page->setConfig('theme','%UP_PAGE% %LINK_PAGE% %DOWN_PAGE%');
        $list = $log->where($where)->getList($page->firstRow, $page->listRows);
        foreach($list as $k => $v){
            $list[$k]['user_info'] = $user->where(array('id'=>$v['uid']))->find();
            $list[$k]['ad_ip'] = $log_info->get_info($v['ad_ip']);
            $list[$k]['ip_city'] = $log_info->get_info($v['ip_city']);
        }
        return $this->assign(array(
            'count' => $count,
            'list' => $list,
            'page' => $page->show(),
            'title' => '登录日志',
        ))->display();
    }
    
    /**
     * 查看登录日志详情信息
     * 
     * @return void
     * @author xiaoyutab<xiaoyutab@qq.com>
     * @version v1.0.0
     * @copyright (c) 2017, xiaoyutab
     * @adtime 2017-08-01 18:48:11
     */
    public function view_log_info_loginAction(){
        $log = new UserLogModel();
        $log_info = new LogInfoModel();
        $info = $log->where(array('id'=>I('get.id')))->find();
        $user = new User_allModel();
        $info['user'] = $user->where(array('id'=>$info['uid'],'status'=>array('neq',98)))->find();
        $user_info = new UserInfoModel();
        $info['user_info'] = $user_info->where(array('id'=>$info['user']['id']))->find();
        $info['system'] = $log_info->get_info($info['system'], 'value');
        $info['ad_ip'] = $log_info->get_info($info['ad_ip'], 'value');
        $info['user_agent'] = $log_info->get_info($info['user_agent'], 'value');
        $info['ip_city'] = $log_info->get_info($info['ip_city'], 'value');
        $info['browser'] = $log_info->get_info($info['browser'], 'value');
        $this->assign('l_info',$info);
        return $this->display();
    }
    
    public function log_outAction(){
        $where = array('user_type' => 4);// 设置只检索登录日志
        $log = new UserLogModel();
        $user = new User_allModel();
        $log_info = new LogInfoModel();
        if(I('get.keywords')){
            $ids = array();
            $wheres = array();
            foreach(explode(' ', I('get.keywords')) as $v){
                $wheres[] = '%'.$v.'%';
            }
            $select = $user->where(array(
                'username|phone|email' => array('like',$wheres,'OR'),
            ))->select();
            foreach($select as $v){
                $ids[] = $v['id'];
            }
            // 如果没查询出来该用户，讲用户设置为0，防止出现SQL错误
            if(empty($ids)){
                $ids = array(0);
            }
            $log_info_sel = $log_info->get_ids($wheres);
            foreach($log_info_sel as $v){
                $log_info_sel_id[] = $v['id'];
            }
            // 如果没查询出来该用户，讲用户设置为0，防止出现SQL错误
            if(empty($log_info_sel_id)){
                $log_info_sel_id = array(0);
            }
            $where[] = array(
                'uid' => array('in',$ids),
                'ip_city|ad_ip' => array('in',$log_info_sel_id),
                '_logic' => 'or',
            );
        }
        $count = $log->where($where)->getCount();
        $page = new Page($count, 10);
    	$page->setConfig('prev','上一页');
    	$page->setConfig('next','下一页');
    	$page->setConfig('header','');
    	$page->setPageHtml('normal_page_html','<a href="%PAGE_HREF%" class="tcdNumber">%PAGE_NUMBER%</a>');
    	$page->setPageHtml('current_page_html','<span class="current">%CURRENT_PAGE_NUMBER%</span>');
    	$page->setConfig('theme','%UP_PAGE% %LINK_PAGE% %DOWN_PAGE%');
        $list = $log->where($where)->getList($page->firstRow, $page->listRows);
        foreach($list as $k => $v){
            $list[$k]['user_info'] = $user->where(array('id'=>$v['uid']))->find();
            $list[$k]['ad_ip'] = $log_info->get_info($v['ad_ip']);
            $list[$k]['ip_city'] = $log_info->get_info($v['ip_city']);
        }
        return $this->assign(array(
            'count' => $count,
            'list' => $list,
            'page' => $page->show(),
            'title' => '退出日志',
        ))->display('login');
    }
}