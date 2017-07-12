<?php
namespace Admin\Controller;
use Admin\Model\UserGroupModel;
use Admin\Model\UserModel;
use Think\Page;
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
        $this->wget('bootstrap')->wget('bootstrap-daterangepicker');
        $group = new UserGroupModel();
        $list = $group->getList(0, 100);
        $this->assign('group_list',$list);
        $user = new UserModel();
        $count = $user->get_count(I('get.keywords',''),I('get.group_id',-1,'intval'),I('get.times',''),I('get.times_end',''));
        $page = new Page($count, 10);
    	$page->setConfig('prev','上一页');
    	$page->setConfig('next','下一页');
    	$page->setConfig('header','');
    	$page->setPageHtml('normal_page_html','<a href="%PAGE_HREF%" class="tcdNumber">%PAGE_NUMBER%</a>');
    	$page->setPageHtml('current_page_html','<span class="current">%CURRENT_PAGE_NUMBER%</span>');
    	$page->setConfig('theme','%UP_PAGE% %LINK_PAGE% %DOWN_PAGE%');
        $list = $user->get_list(I('get.keywords',''),I('get.group_id',-1,'intval'),I('get.times',''),I('get.times_end',''),$page->firstRow, $page->listRows);
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
}