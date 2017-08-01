<?php
namespace Admin\Controller;
use Admin\Model\UserLogModel;
use Admin\Model\User_allModel;
use Admin\Model\UserInfoModel;
use Think\Page;

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
        $where = array();
        $log = new UserLogModel();
        $user = new User_allModel();
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
            $where = array(
                'uid' => array('in',$ids),
                'ip_city|ad_ip' => array('like',$wheres,'OR'),
                '_logic' => 'or',
            );
        }
        $count = $log->where($where)->cache(TRUE)->getCount();
        $page = new Page($count, 10);
    	$page->setConfig('prev','上一页');
    	$page->setConfig('next','下一页');
    	$page->setConfig('header','');
    	$page->setPageHtml('normal_page_html','<a href="%PAGE_HREF%" class="tcdNumber">%PAGE_NUMBER%</a>');
    	$page->setPageHtml('current_page_html','<span class="current">%CURRENT_PAGE_NUMBER%</span>');
    	$page->setConfig('theme','%UP_PAGE% %LINK_PAGE% %DOWN_PAGE%');
        $list = $log->where($where)->cache(TRUE)->getList($page->firstRow, $page->listRows);
        foreach($list as $k => $v){
            $list[$k]['user_info'] = $user->where(array('id'=>$v['uid']))->find();
        }
        return $this->assign(array(
            'count' => $count,
            'list' => $list,
            'page' => $page->show(),
        ))->display();
        return $this->display();
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
        $info = $log->where(array('id'=>I('get.id')))->find();
        $user = new User_allModel();
        $info['user'] = $user->where(array('id'=>$info['uid'],'status'=>array('neq',98)))->find();
        $user_info = new UserInfoModel();
        $info['user_info'] = $user_info->where(array('id'=>$info['user']['id']))->find();
        $this->assign('l_info',$info);
        return $this->display();
    }
}