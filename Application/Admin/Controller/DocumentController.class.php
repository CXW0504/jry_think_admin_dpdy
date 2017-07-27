<?php

namespace Admin\Controller;
use Admin\Model\ProjectModel;
use Think\Page;
use Admin\Model\ProjectParameterTypeModel;
use Admin\Model\ProjectApiModel;

/**
 * 网站文档管理操作控制器
 * 
 * @author xiaoyutab<xiaoyutab@qq.com>
 * @version v1.0.2
 * @copyright (c) 2017, xiaoyutab
 * @adtime 2017-07-25 17:50:57
 */
class DocumentController extends CommonController {
    /**
     * 接口文档管理页面
     *      管理有项目列表，页面内部包含接口列表
     * 
     * @return void
     * @author xiaoyutab<xiaoyutab@qq.com>
     * @version v1.0.0
     * @copyright (c) 2017, xiaoyutab
     * @adtime 2017-07-25 17:54:45
     */
    public function apisAction(){
        $this->wget('bootstrap')->wget('bootstrap-daterangepicker');
        $project = new ProjectModel();
        $times = explode(' ~ ', I('get.times_end'));
        $where = array(
            'name|com_name'=>array('like','%'.I('get.keywords').'%'),
            'ad_time' => array(
                array('gt',strtotime($times[0].' 00:00:01')),
                array('lt',strtotime($times[1].' 23:59:59'))
            )
        );
        if(I('get.times_end')){
            $count = $project->where($where)->getCount();
        } else {
            $count = $project->getCount();
        }
        $page = new Page($count, 10);
    	$page->setConfig('prev','上一页');
    	$page->setConfig('next','下一页');
    	$page->setConfig('header','');
    	$page->setPageHtml('normal_page_html','<a href="%PAGE_HREF%" class="tcdNumber">%PAGE_NUMBER%</a>');
    	$page->setPageHtml('current_page_html','<span class="current">%CURRENT_PAGE_NUMBER%</span>');
    	$page->setConfig('theme','%UP_PAGE% %LINK_PAGE% %DOWN_PAGE%');
        if(I('get.times_end')){
            $list = $project->where($where)->getList($page->firstRow, $page->listRows);
        } else {
            $list = $project->getList($page->firstRow, $page->listRows);
        }
        return $this->assign(array(
            'count' => $count,
            'list' => $list,
            'page' => $page->show(),
        ))->display();
    }
    
    /**
     * 接口文档中的添加项目
     * 
     * @return void
     * @author xiaoyutab<xiaoyutab@qq.com>
     * @version v1.0.0
     * @copyright (c) 2017, xiaoyutab
     * @adtime 2017-07-26 18:42:21
     */
    public function add_apisAction(){
        if(!I('post.')){
            return $this->display();
        }
        $project = new ProjectModel();
        if($project->create_info(I('post.'))){
            return $this->success('添加成功',U('apis'));
        }
        return $this->error('添加失败');
    }
    
    /**
     * 参数类型接口,该操作管理有接口的参数类型信息
     * 
     * @return void
     * @author xiaoyutab<xiaoyutab@qq.com>
     * @version v1.0.0
     * @copyright (c) 2017, xiaoyutab
     * @adtime 2017-07-26 23:24:19
     */
    public function parameterAction(){
        $project = new ProjectParameterTypeModel();
        $where = array(
            'name|desc'=>array('like','%'.I('get.keywords').'%'),
        );
        $count = $project->where($where)->getCount();
        $page = new Page($count, 10);
    	$page->setConfig('prev','上一页');
    	$page->setConfig('next','下一页');
    	$page->setConfig('header','');
    	$page->setPageHtml('normal_page_html','<a href="%PAGE_HREF%" class="tcdNumber">%PAGE_NUMBER%</a>');
    	$page->setPageHtml('current_page_html','<span class="current">%CURRENT_PAGE_NUMBER%</span>');
    	$page->setConfig('theme','%UP_PAGE% %LINK_PAGE% %DOWN_PAGE%');
        $list = $project->where($where)->getList($page->firstRow, $page->listRows);
        return $this->assign(array(
            'count' => $count,
            'list' => $list,
            'page' => $page->show(),
        ))->display();
    }
    
    /**
     * 添加参数名称的操作
     * 
     * @return void
     * @author xiaoyutab<xiaoyutab@qq.com>
     * @version v1.0.0
     * @copyright (c) 2017, xiaoyutab
     * @adtime 2017-07-26 23:38:34
     */
    public function add_parameterAction(){
        if(!I('post.')){
            return $this->display();
        }
        $project = new ProjectParameterTypeModel();
        if($project->create_info(I('post.'))){
            return $this->success('添加成功',U('parameter'));
        }
        return $this->error('添加失败');
    }
    
    /**
     * 修改参数类型的操作
     * 
     * @return void
     * @author xiaoyutab<xiaoyutab@qq.com>
     * @version v1.0.0
     * @copyright (c) 2017, xiaoyutab
     * @adtime 2017-07-26 23:38:34
     */
    public function save_parameterAction(){
        $project = new ProjectParameterTypeModel();
        $info = $project->where(array('id'=>I('get.id'),'status'=>array('neq',98)))->find();
        if(!I('post.')){
            $this->assign('l_info',$info);
            return $this->display('add_parameter');
        }
        if($project->update_info(I('get.id'),$info,I('post.'))){
            return $this->success('修改成功',U('parameter'));
        }
        return $this->error('修改失败');
    }
    
    /**
     * 删除参数类型操作
     * 
     * @return void
     * @author xiaoyutab<xiaoyutab@qq.com>
     * @version v1.0.0
     * @copyright (c) 2017, xiaoyutab
     * @adtime 2017-07-27 00:38:56
     */
    public function del_parameterAction(){
        $project = new ProjectParameterTypeModel();
        if($project->delete_info(I('get.id'))){
            return $this->success('删除成功',U('parameter'));
        }
        return $this->error('删除失败');
    }
    
    /**
     * 修改项目信息操作
     * 
     * @return void
     * @author xiaoyutab<xiaoyutab@qq.com>
     * @version v1.0.0
     * @copyright (c) 2017, xiaoyutab
     * @adtime 2017-07-27 00:42:13
     */
    public function save_apisAction(){
        $project = new ProjectModel();
        $info = $project->where(array('id'=>I('get.id'),'status'=>array('neq',98)))->find();
        if(!I('post.')){
            $this->assign('l_info',$info);
            return $this->display('add_apis');
        }
        if($project->update_info(I('get.id'),$info,I('post.'))){
            return $this->success('修改成功',U('apis'));
        }
        return $this->error('修改失败');
    }
    
    /**
     * 删除参数类型操作
     * 
     * @return void
     * @author xiaoyutab<xiaoyutab@qq.com>
     * @version v1.0.0
     * @copyright (c) 2017, xiaoyutab
     * @adtime 2017-07-27 00:38:56
     */
    public function del_apisAction(){
        $project = new ProjectModel();
        if($project->delete_info(I('get.id'))){
            return $this->success('删除成功',U('apis'));
        }
        return $this->error('删除失败');
    }
    
    /**
     * 获取接口列表操作
     * 
     * @return void
     * @author xiaoyutab<xiaoyutab@qq.com>
     * @version v1.0.0
     * @copyright (c) 2017, xiaoyutab
     * @adtime 2017-07-27 13:47:00
     */
    public function list_apisAction(){
        $project = new ProjectApiModel();
        $where = array(
            'a_name|desc'=>array('like','%'.I('get.keywords').'%'),
            'p_id' => I('get.id',0,'intval')
        );
        $count = $project->where($where)->getCount();
        $page = new Page($count, 10);
    	$page->setConfig('prev','上一页');
    	$page->setConfig('next','下一页');
    	$page->setConfig('header','');
    	$page->setPageHtml('normal_page_html','<a href="%PAGE_HREF%" class="tcdNumber">%PAGE_NUMBER%</a>');
    	$page->setPageHtml('current_page_html','<span class="current">%CURRENT_PAGE_NUMBER%</span>');
    	$page->setConfig('theme','%UP_PAGE% %LINK_PAGE% %DOWN_PAGE%');
        $list = $project->where($where)->getList($page->firstRow, $page->listRows);
        return $this->assign(array(
            'count' => $count,
            'list' => $list,
            'page' => $page->show(),
        ))->display();
    }
    
    /**
     * 添加接口信息操作
     * 
     * @return void
     * @author xiaoyutab<xiaoyutab@qq.com>
     * @version v1.0.0
     * @copyright (c) 2017, xiaoyutab
     * @adtime 2017-07-27 13:47:10
     */
    public function add_list_apisAction(){
        $project = new ProjectModel();
        $pro_info = $project->where(array('id'=>I('get.p_id'),'status'=>array('neq',98)))->find();
        if(!I('post.')){
            $this->assign('p_info',$pro_info);
            return $this->display();
        }
    }
}