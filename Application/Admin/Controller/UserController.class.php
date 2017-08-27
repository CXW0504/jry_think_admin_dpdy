<?php
namespace Admin\Controller;
use Admin\Model\UserGroupModel;
use Admin\Model\UserModel;
use Admin\Model\UserReceptionGroupModel;
use Think\Page;
use Admin\Model\UserReceptionModel;
/**
 * 网站首页信息
 * 
 * @author xiaoyutab<xiaoyutab@qq.com>
 * @version v1.0.2
 * @copyright (c) 2017, xiaoyutab
 * @adtime 2017-07-05 16:41:11
 */
class UserController extends CommonController{
    /**
     * 获取权限组列表页面
     * 
     * @return void
     * @version v1.0.0
     * @author xiaoyutab<xiaoyutab@qq.com>
     * @copyright (c) 2017, xiaoyutab
     * @adtime 2017-07-06 16:25:14
     */
    public function group_listAction(){
        $group = new UserGroupModel();
        $count = $group->where(array('name'=>array('like','%'.I('get.keywords').'%')))->getCount();
        $page = new Page($count, 10);
    	$page->setConfig('prev','上一页');
    	$page->setConfig('next','下一页');
    	$page->setConfig('header','');
    	$page->setPageHtml('normal_page_html','<a href="%PAGE_HREF%" class="tcdNumber">%PAGE_NUMBER%</a>');
    	$page->setPageHtml('current_page_html','<span class="current">%CURRENT_PAGE_NUMBER%</span>');
    	$page->setConfig('theme','%UP_PAGE% %LINK_PAGE% %DOWN_PAGE%');
        $list = $group->where(array('name'=>array('like','%'.I('get.keywords').'%')))->getList($page->firstRow, $page->listRows);
        return $this->assign(array(
            'count' => $count,
            'list' => $list,
            'page' => $page->show(),
        ))->display();
    }
    
    /**
     * 添加权限组信息
     * 
     * @return void
     * @version v1.0.0
     * @author xiaoyutab<xiaoyutab@qq.com>
     * @copyright (c) 2017, xiaoyutab
     * @adtime 2017-07-06 17:50:51
     */
    public function group_addAction(){
        if(!I('post.')){
            return $this->display();
        }
        $group = new UserGroupModel();
        if($group->create_group(I('post.name'))){
            return $this->success('添加成功');
        }
        return $this->error('添加失败');
    }
    
    /**
     * 修改权限组信息
     * 
     * @return void
     * @version v1.0.0
     * @author xiaoyutab<xiaoyutab@qq.com>
     * @copyright (c) 2017, xiaoyutab
     * @adtime 2017-07-06 18:25:47
     */
    public function group_saveAction(){
        $group = new UserGroupModel();
        $info = $group->where(array('id'=>I('get.id'),'status'=>array('neq',98)))->find();
        if(empty($info)){
            return $this->error('查无此数据');
        }
        if(!I('post.')){
            $this->assign('name',$info['name']);
            return $this->display('group_add');
        }
        if($group->save_group(I('get.id'),I('post.name'),$info['name'])){
            return $this->success('修改成功',U('group_list'));
        }
        return $this->error('添加失败');
    }
    
    /**
     * 删除权限组信息
     * 
     * @return void
     * @version v1.0.0
     * @author xiaoyutab<xiaoyutab@qq.com>
     * @copyright (c) 2017, xiaoyutab
     * @adtime 2017-07-06 19:37:53
     */
    public function group_delAction(){
        $group = new UserGroupModel();
        $info = $group->where(array('id'=>I('get.id'),'status'=>array('neq',98)))->find();
        if(empty($info)){
            return $this->error('查无此数据');
        }
        if($group->delete_group($info['id'],$info['status'])){
            return $this->success('删除成功');
        }
        return $this->error('删除失败');
    }
    
    /**
     * 为权限组进行授权
     * 
     * @return void
     * @version v1.0.0
     * @author xiaoyutab<xiaoyutab@qq.com>
     * @copyright (c) 2017, xiaoyutab
     * @adtime 2017-07-06 21:11:06
     */
    public function group_jurisdictionAction(){
        $group = new UserGroupModel();
        $info = $group->where(array('id'=>I('get.id'),'status'=>array('neq',98)))->find();
        if(!I('post.')){
            $this->assign('jurisdiction_list', explode(',', $info['content']));
            return $this->display();
        }
        if($group->save_group_jurisdiction(I('get.id'),$info['content'],I('post.content'))){
            return $this->success('更新成功',U('group_list'));
        }
        return $this->error('更新失败');
    }
    
    /**
     * 获取用户列表页面
     * 
     * @return void
     * @version v1.0.0
     * @author xiaoyutab<xiaoyutab@qq.com>
     * @copyright (c) 2017, xiaoyutab
     * @adtime 2017-07-09 11:56:30
     */
    public function user_listAction(){
        $group = new UserGroupModel();
        $list = $group->getList(0, 100);
        $this->assign('group_list',$list);
        $user = new UserModel();
        $count = $user->get_count(I('get.keywords',''),I('get.group_id',-1,'intval'));
        $page = new Page($count, 10);
    	$page->setConfig('prev','上一页');
    	$page->setConfig('next','下一页');
    	$page->setConfig('header','');
    	$page->setPageHtml('top_html','<div class="pagination" style="margin:0;"><ul>%PAGE_CONTENT_HTML%</ul></div>');
    	$page->setPageHtml('normal_page_html','<a href="%PAGE_HREF%" class="tcdNumber">%PAGE_NUMBER%</a>');
    	$page->setPageHtml('current_page_html','<span class="current">%CURRENT_PAGE_NUMBER%</span>');
    	$page->setConfig('theme','%UP_PAGE% %LINK_PAGE% %DOWN_PAGE%');
        $list = $user->get_list(I('get.keywords',''),I('get.group_id',-1,'intval'),$page->firstRow, $page->listRows);
        return $this->assign(array(
            'count' => $count,
            'list' => $list,
            'page' => $page->show(),
        ))->display();
    }
    
    /**
     * 添加用户操作
     * 
     * @return void
     * @version v1.0.0
     * @author xiaoyutab<xiaoyutab@qq.com>
     * @copyright (c) 2017, xiaoyutab
     * @adtime 2017-07-09 15:12:18
     */
    public function user_addAction(){
        $user = new UserModel();
        if(!I('post.')){
            $this->wget('bootstrap')->wget('bootstrap-daterangepicker');
            $group = new UserGroupModel();
            $list = $group->getList(0, 100);
            $info = $user->get_user_info();
            if($info['group_id'] == 0){
                array_unshift($list, array('id'=>0,'name'=>'超级管理员'));
            }
            $this->assign('group_list',$list);
            return $this->display();
        }
        $info = $user->create_user(I('post.username'),I('post.phone'),I('post.birthdy'),I('post.group_id'),I('post.password'),I('post.sex'));
        if($info === TRUE){
            return $this->success('添加成功',U('User/user_add'));
        }
        return $this->error($info);
    }
    
    
    /**
     * 修改用户密码操作
     * 
     * @return void
     * @version v1.0.0
     * @author xiaoyutab<xiaoyutab@qq.com>
     * @copyright (c) 2017, xiaoyutab
     * @adtime 2017-07-09 15:12:18
     */
    public function user_saveAction(){
        if(!I('post.')){
            return $this->display();
        }
        $user = new UserModel();
        if($user->save_user_password(I('get.id'),I('post.password'))){
            return $this->success('修改成功',U('user_list'));
        }
        return $this->error('修改失败',U('user_list'));
    }
    
    /**
     * 获取前台用户组列表
     * 
     * @return void
     * @author xiaoyutab<xiaoyutab@qq.com>
     * @version v1.0.0
     * @copyright (c) 2017, xiaoyutab
     * @adtime 2017-7-17 14:12:05
     */
    public function reception_group_listAction(){
        $user = new UserReceptionGroupModel();
        $key = I('get.keywords','','trim');
        $count = $user->where(array('name'=>array('like','%'.$key.'%')))->getCount();
        $page = new Page($count, 10);
    	$page->setConfig('prev','上一页');
    	$page->setConfig('next','下一页');
    	$page->setConfig('header','');
    	$page->setPageHtml('normal_page_html','<a href="%PAGE_HREF%" class="tcdNumber">%PAGE_NUMBER%</a>');
    	$page->setPageHtml('current_page_html','<span class="current">%CURRENT_PAGE_NUMBER%</span>');
    	$page->setConfig('theme','%UP_PAGE% %LINK_PAGE% %DOWN_PAGE%');
        $list = $user->where(array('name'=>array('like','%'.$key.'%')))->getList($page->firstRow, $page->listRows);
        $_user = new UserReceptionModel();
        foreach($list as $k => $v){
            $list[$k]['fid_name'] = $user->get_reception_group_name($v['fid']);
            $list[$k]['count_people'] = $_user->get_group_count($v['id']);
            $list[$k]['count_reception_group'] = $user->get_reception_group_count($v['id']);
        }
        $this->assign(array(
            'g_list' => $list,
            'count' => $count,
            'page' => $page->show(),
        ));
        return $this->display();
    }
    
    /**
     * 添加前台用户组
     * 
     * @return void
     * @author xiaoyutab<xiaoyutab@qq.com>
     * @version v1.0.0
     * @copyright (c) 2017, xiaoyutab
     * @adtime 2017-7-17 15:38:33
     */
    public function reception_group_addAction(){
        $loan = new UserReceptionGroupModel();
        if(!I('post.')){
            $this->assign('group_list',$loan->get_tree_group_list());
            return $this->display();
        }
        if($loan->create_info(I('post.fid'),I('post.name'))){
            return $this->success('添加成功',U('reception_group_list'));
        }
        return $this->error('操作失败');
    }
    
    /**
     * 修改前台用户分组信息
     * 
     * @return void
     * @author xiaoyutab<xiaoyutab@qq.com>
     * @version v1.0.1
     * @copyright (c) 2017, xiaoyutab
     * @adtime 2017-7-17 15:38:29
     */
    public function reception_group_saveAction(){
        $loan = new UserReceptionGroupModel();
        if(!I('post.')){
            $list = $loan->get_tree_group_list();
            unset($list[I('get.id')]);
            $this->assign('group_list',$list);
            $info = $loan->get_info(I('get.id'));
            if($info && $info['status'] != '98'){
                // 获取到了详情信息，进行编辑操作
                return $this->assign('g_info',$info)->display('reception_group_add');
            }
            return $this->error('未查询到该分组信息');
        }
        if($loan->save_info(I('get.id'), I('post.fid'), I('post.name'))){
            return $this->success('修改成功',U('reception_group_list'));
        }
        return $this->error('修改失败，可能未更改条目信息');
    }
    
    /**
     * 删除前台用户分组
     * 
     * @return void
     * @author xiaoyutab<xiaoyutab@qq.com>
     * @version v1.0.0
     * @copyright (c) 2017, xiaoyutab
     * @adtime 2017-7-17 15:38:25
     */
    public function reception_group_delAction(){
        $loan = new UserReceptionGroupModel();
        if($loan->delete_info(I('get.id'))){
            return $this->success('删除成功');
        }
        return $this->error('删除失败');
    }
    
    /**
     * 获取前台用户列表
     * 
     * @return void
     * @author xiaoyutab<xiaoyutab@qq.com>
     * @version v1.0.0
     * @copyright (c) 2017, xiaoyutab
     * @adtime 2017-7-17 14:12:05
     */
    public function reception_listAction(){
        $user = new UserReceptionModel();
        $user_group = new UserReceptionGroupModel();
        $this->assign('group_list',$user_group->get_tree_group_list());
        $where = array();
        if(I('get.keywords')){
            $where[] = array(
                    'username' => array('like','%'.I('get.keywords').'%'),
                    'phone' => array('like','%'.I('get.keywords').'%'),
                    'email' => array('like','%'.I('get.keywords').'%'),
                    'nickname' => array('like','%'.I('get.keywords').'%'),
                    '_logic' => 'or',
                );
        }
        if(I('get.group_id') >= 0 && isset($_GET['group_id'])){
            $where['group_id'] = I('get.group_id',0,'intval');
        }
        if(I('get.type')){
            $where['type'] = I('get.type',0,'intval');
        }
        $count = $user->where($where)->getCount();
        $page = new Page($count, 10);
    	$page->setConfig('prev','上一页');
    	$page->setConfig('next','下一页');
    	$page->setConfig('header','');
    	$page->setPageHtml('normal_page_html','<a href="%PAGE_HREF%" class="tcdNumber">%PAGE_NUMBER%</a>');
    	$page->setPageHtml('current_page_html','<span class="current">%CURRENT_PAGE_NUMBER%</span>');
    	$page->setConfig('theme','%UP_PAGE% %LINK_PAGE% %DOWN_PAGE%');
        $list = $user->where($where)->getList($page->firstRow, $page->listRows);
        $user_type = array(1=>'抵押专员','调评专员','普通用户');
        foreach($list as $k => $v){
            $list[$k]['type_name'] = $user_type[$v['type']]?$user_type[$v['type']]:$user_type[3];
            $info = $user_group->get_info($v['group_id']);
            $list[$k]['group_id_name'] = $info['name']?$info['name']:'--';
            $list[$k]['phone_view'] = phont_view_type(3, $v['phone']);
        }
        $this->assign(array(
            'g_list' => $list,
            'count' => $count,
            'page' => $page->show(),
        ));
        return $this->display();
    }
    
    /**
     * 添加前台用户
     * 
     * @return void
     * @author xiaoyutab<xiaoyutab@qq.com>
     * @version v1.0.0
     * @copyright (c) 2017, xiaoyutab
     * @adtime 2017-7-17 15:38:33
     */
    public function reception_addAction(){
        if(!I('post.')){
            $user_group = new UserReceptionGroupModel();
            $this->assign('group_list',$user_group->get_tree_group_list());
            return $this->display();
        }
        $user = new UserReceptionModel();
        if(!$user->name_value('username', I('post.username'))){
            return $this->error('登录用户名已存在');
        }
        if(!$user->name_value('phone', I('post.phone'))){
            return $this->error('用户手机号已存在');
        }
        if(!$user->name_value('email', I('post.email'))){
            return $this->error('用户邮箱已存在');
        }
        if($user->create_info(I('post.'))){
            return $this->success('添加成功',U('reception_list'));
        }
        return $this->error('操作失败');
    }
    
    /**
     * 修改前台用户信息
     * 
     * @return void
     * @author xiaoyutab<xiaoyutab@qq.com>
     * @version v1.0.1
     * @copyright (c) 2017, xiaoyutab
     * @adtime 2017-7-17 15:38:29
     */
    public function reception_saveAction(){
        $user = new UserReceptionModel();
        $info = $user->get_info(I('get.id'));
        if(!I('post.')){
            $user_group = new UserReceptionGroupModel();
            $list = $user_group->get_tree_group_list();
            $this->assign('group_list',$list);
            if($info && $info['status'] != '98'){
                // 获取到了详情信息，进行编辑操作
                return $this->assign('g_info',$info)->display('reception_add');
            }
            return $this->error('未查询到该分组信息');
        }
        if(I('post.username') != $info['username'] && !$user->name_value('username', I('post.username'))){
            return $this->error('登录用户名已存在');
        }
        if(I('post.phone') != $info['phone'] && !$user->name_value('phone', I('post.phone'))){
            return $this->error('用户手机号已存在');
        }
        if(I('post.email') != $info['email'] && !$user->name_value('email', I('post.email'))){
            return $this->error('用户邮箱已存在');
        }
        if($user->save_info(I('get.id'), I('post.'))){
            return $this->success('修改成功',U('reception_list'));
        }
        return $this->error('修改失败，可能未更改条目信息');
    }
    
    /**
     * 删除前台用户
     * 
     * @return void
     * @author xiaoyutab<xiaoyutab@qq.com>
     * @version v1.0.0
     * @copyright (c) 2017, xiaoyutab
     * @adtime 2017-7-17 15:38:25
     */
    public function reception_delAction(){
        $loan = new UserReceptionModel();
        if($loan->delete_info(I('get.id'))){
            return $this->success('删除成功');
        }
        return $this->error('删除失败');
    }
    
    /**
     * 理组操作
     *      为权限组进行授予用户分组，进行分工操作
     * 
     * @return void
     * @author xiaoyutab<xiaoyutab@qq.com>
     * @version v1.0.0
     * @copyright (c) 2017, xiaoyutab
     * @adtime 2017-7-18 16:03:40
     */
    public function group_distributionAction(){
        $group = new UserGroupModel();
        $info = $group->where(array('status'=>array('neq',98),'id'=>I('get.id',0,'intval')))->find();
        if(!I('post.')){
            if(empty($info)){
                return $this->error('查无数据');
            }
            $user_group = new UserReceptionGroupModel();
            $this->assign('group_list',$user_group->getList(0, 999));
            $this->assign('group_ids', explode(',', $info['all_group_id']));
            return $this->display();
        }
        if($group->save_group_distribution(I('get.id'),I('post.content'),$info['all_group_id'])){
            return $this->success('更新成功',U('group_list'));
        }
        return $this->error('更新失败');
    }
}