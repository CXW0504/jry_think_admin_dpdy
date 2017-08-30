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
    
    /**
     * 退出日志列表管理
     * 
     * @return void
     * @author xiaoyutab<xiaoyutab@qq.com>
     * @version v1.0.0
     * @copyright (c) 2017, xiaoyutab
     * @adtime 2017-08-29 22:22:06
     */
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

    /**
     * 日志分类管理-顶级分类
     * 
     * @return void 
     * @author xiaoyutab<xiaoyutab@qq.com>
     * @version v1.0.0
     * @copyright (c) 2017, xiaoyutab
     * @adtime 2017-08-29 22:26:49
     */
    public function log_infoAction(){
        $log_info = new LogInfoModel();
        $where = array(
            'type' => 0, // 当前操作只查询顶级分类
        );
        if(I('get.keywords')){
            $wheres = array();
            foreach(explode(' ', I('get.keywords')) as $v){
                $wheres[] = '%'.$v.'%';
            }
            $where[] = array(
                'name|value' => array('like',$wheres,'or'),
            );
        }
        $count = $log_info->where($where)->getCount();
        $page = new Page($count, 10);
        $page->setConfig('prev','上一页');
        $page->setConfig('next','下一页');
        $page->setConfig('header','');
        $page->setPageHtml('normal_page_html','<a href="%PAGE_HREF%" class="tcdNumber">%PAGE_NUMBER%</a>');
        $page->setPageHtml('current_page_html','<span class="current">%CURRENT_PAGE_NUMBER%</span>');
        $page->setConfig('theme','%UP_PAGE% %LINK_PAGE% %DOWN_PAGE%');
        $list = $log_info->where($where)->getList($page->firstRow, $page->listRows);
        foreach($list as $k => $v){
            $list[$k]['child_count'] = $log_info->where(array('type' => $v['id']))->getCount();
        }
        return $this->assign(array(
            'count' => $count,
            'list' => $list,
            'page' => $page->show(),
        ))->display();
    }


    /**
     * 添加日志分类
     * 
     * @return void
     * @author xiaoyutab<xiaoyutab@qq.com>
     * @version v1.0.0
     * @copyright (c) 2017, xiaoyutab
     * @adtime 2017-08-29 22:45:21
     */
    public function log_info_addAction(){
        if(!I('post.')){
            return $this->display();
        }
        $log_info = new LogInfoModel();
        if($log_info->get_id(I('post.name'),0)){
            return $this->success('添加成功',U('log_info'));
        }
        return $this->error('添加失败');
    }

    /**
     * 日志分类管理-顶级分类
     * 
     * @return void 
     * @author xiaoyutab<xiaoyutab@qq.com>
     * @version v1.0.0
     * @copyright (c) 2017, xiaoyutab
     * @adtime 2017-08-29 22:26:49
     */
    public function log_info_childAction(){
        $log_info = new LogInfoModel();
        $where = array(
            'type' => intval(I('get.id')), // 当前操作只查询顶级分类
        );
        if(I('get.keywords')){
            $wheres = array();
            foreach(explode(' ', I('get.keywords')) as $v){
                $wheres[] = '%'.$v.'%';
            }
            $where[] = array(
                'name|value' => array('like',$wheres,'or'),
            );
        }
        $count = $log_info->where($where)->getCount();
        $page = new Page($count, 10);
        $page->setConfig('prev','上一页');
        $page->setConfig('next','下一页');
        $page->setConfig('header','');
        $page->setPageHtml('normal_page_html','<a href="%PAGE_HREF%" class="tcdNumber">%PAGE_NUMBER%</a>');
        $page->setPageHtml('current_page_html','<span class="current">%CURRENT_PAGE_NUMBER%</span>');
        $page->setConfig('theme','%UP_PAGE% %LINK_PAGE% %DOWN_PAGE%');
        $list = $log_info->where($where)->getList($page->firstRow, $page->listRows);
        foreach($list as $k => $v){
            $list[$k]['child_count'] = $log_info->where(array('type' => $v['id']))->getCount();
        }
        return $this->assign(array(
            'count' => $count,
            'list' => $list,
            'page' => $page->show(),
        ))->display();
    }


    /**
     * 修改日志分类
     * 
     * @return void
     * @author xiaoyutab<xiaoyutab@qq.com>
     * @version v1.0.0
     * @copyright (c) 2017, xiaoyutab
     * @adtime 2017-08-30 17:42:41
     */
    public function log_info_saveAction(){
        $log_info = new LogInfoModel();
        if(!I('post.')){
        	$this->assign('g_info',$log_info->where(array('id'=>I('get.id'),'type'=>0,'status'=>array('neq',98)))->find());
            return $this->display();
        }
        if($log_info->edit_info(I('get.id'),I('post.name'))){
            return $this->success('修改成功',U('log_info'));
        }
        return $this->error('修改失败，可能为值没有变化');
    }


    /**
     * 删除日志分类
     * 
     * @return void
     * @author xiaoyutab<xiaoyutab@qq.com>
     * @version v1.0.0
     * @copyright (c) 2017, xiaoyutab
     * @adtime 2017-08-30 17:50:39
     */
    public function log_info_delAction(){
        $log_info = new LogInfoModel();
        if($log_info->delete_info(I('get.id'))){
            return $this->success('删除成功',U('log_info'));
        }
        return $this->error('删除失败，可能为其下有子分类');
    }
}