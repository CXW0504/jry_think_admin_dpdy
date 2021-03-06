<?php

namespace Admin\Controller;
use Admin\Model\ProjectModel;
use Think\Page;
use Admin\Model\ProjectParameterTypeModel;
use Admin\Model\ProjectApiModel;
use Admin\Model\ProjectApiParameterModel;
use Org\Office\Word\PHPWord;
use Admin\Model\ProjectErrorCodeModel;
use Admin\Model\TagModel;
use Admin\Model\FileModel;
use Admin\Model\FileLinkModel;

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
        $project = new ProjectModel();
        $wheres = array();
        foreach(explode(' ', I('get.keywords')) as $v){
            $wheres[] = '%'.$v.'%';
        }
        $where = array(
            'name|com_name'=>array('like',$wheres,'OR'),
        );
        $count = $project->where($where)->getCount();
        $page = new Page($count, 10);
    	$page->setConfig('prev','上一页');
    	$page->setConfig('next','下一页');
    	$page->setConfig('header','');
    	$page->setPageHtml('top_html','<div class="pagination" style="margin:0;"><ul>%PAGE_CONTENT_HTML%</ul></div>');
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
        $file = new FileModel();
        $fid = $file->upload_file('file');
        if($fid || $project->create_info(I('post.'))){
            if($fid){
                $p_id = $project->getLastInsID();// 获取项目编号
                $file_link = new FileLinkModel();
                $file_link->create_link($fid,$p_id,'project');
            }
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
        $wheres = array();
        foreach(explode(' ', I('get.keywords')) as $v){
            $wheres[] = '%'.$v.'%';
        }
        $where = array(
            'name|desc'=>array('like',$wheres,'OR'),
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
        $file_link = new FileLinkModel();
        $info = $project->where(array('id'=>I('get.id'),'status'=>array('neq',98)))->find();
        $info['images'] = $file_link->get_file_info($info['id'],'project',true);
        if(!I('post.')){
            $this->assign('l_info',$info);
            return $this->display('add_apis');
        }
        $file = new FileModel();
        $fid = $file->upload_file('file');
        if($fid){
            $p_id = $project->getLastInsID();// 获取项目编号
            $file_link->create_link($fid,$info['id'],'project',true);
        }
        if($fid || $project->update_info(I('get.id'),$info,I('post.'))){
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
            'a_name|desc|href'=>array('like','%'.I('get.keywords').'%'),
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
            $_pt = new ProjectParameterTypeModel();
            $this->assign(array(
                'type_in' => $_pt->where(array('type'=>1))->getList(0, 100),
                'type_out' => $_pt->where(array('type'=>2))->getList(0, 100),
                'p_info' => $pro_info,
            ));
            return $this->display();
        }
        $api = new ProjectApiModel();
        $api_id = $api->create_info($pro_info['id'],I('post.a_name'),I('post.href'),I('post.desc'),I('post.in_type'),I('post.out_type'));
        if(!$api_id){
            return $this->error('系统错误');
        }
        $par = new ProjectApiParameterModel();
        $success = $par->update_info($api_id,I('post.type_in'),I('post.type_out'));
        if(!$success){
            return $this->success('系统内部出现部分错误',U('list_apis',array('id',$pro_info['id'])));
        }
        return $this->success('添加完成',U('list_apis',array('id'=>$pro_info['id'])));
    }
    
    /**
     * 删除接口操作
     * 
     * @return void
     * @author xiaoyutab<xiaoyutab@qq.com>
     * @version v1.0.0
     * @copyright (c) 2017, xiaoyutab
     * @adtime 2017-07-27 19:48:51
     */
    public function del_list_apisAction(){
        $project = new ProjectApiModel();
        if($project->delete_info(I('get.id'))){
            return $this->success('删除成功',U('list_apis',array('id'=>I('get.p_id'))));
        }
        return $this->error('删除失败');
    }
    
    /**
     * 修改接口信息
     * 
     * @return void
     * @author xiaoyutab<xiaoyutab@qq.com>
     * @version v1.0.0
     * @copyright (c) 2017, xiaoyutab
     * @adtime 2017-07-27 20:42:06
     */
    public function save_list_apisAction(){
        $project = new ProjectApiModel();
        $a_info = $project->where(array('id'=>I('get.id'),'status'=>array('neq',98)))->find();
        if(I('post.')){
            if($project->update_info(I('get.id'),I('post.'),$a_info)){
                return $this->success('更新成功',U('list_apis',array('id'=>$a_info['p_id'])));
            }
            return $this->error('更新失败');
        }
        $project_p = new ProjectModel();
        $p_info = $project_p->where(array('id'=>$a_info['p_id'],'status'=>array('neq',98)))->find();
        $parameter = new ProjectApiParameterModel();
        $in_par = $parameter->where(array(
            'a_id'=>$a_info['id'],
            'status'=>array('neq',98),
            'type' => 1
        )) -> select();
        $out_par = $parameter->where(array(
            'a_id'=>$a_info['id'],
            'status'=>array('neq',98),
            'type' => 2
        )) -> select();
        $parameter_type = new ProjectParameterTypeModel();
        $in_type = $parameter_type->get_type(1);
        $out_type = $parameter_type->get_type(2);
        return $this->assign(array(
            'a_info' => $a_info,
            'p_info' => $p_info,
            'in_par' => $in_par,
            'out_par' => $out_par,
            'in_type' => $in_type,
            'out_type' => $out_type,
        ))->display();
    }
    
    /**
     * 添加接口参数
     * 
     * @return void
     * @author xiaoyutab<xiaoyutab@qq.com>
     * @version v1.0.0
     * @copyright (c) 2017, xiaoyutab
     * @adtime 2017-07-28 16:49:50
     */
    public function add_project_parameterAction(){
        if(!I('post.')){
            $canshu = new ProjectParameterTypeModel();
            $this->assign('c_type',$canshu->get_type(I('get.type')));
            return $this->display();
        }
        $apis = new ProjectApiParameterModel();
        if($apis->create_info(I('get.a_id'), I('get.type'), I('post.name'), I('post.desc'), I('post.max_length'), I('post.c_type'), I('post.is_must'), I('post.msg'))){
            return $this->success('添加成功',U('save_list_apis',array('id'=>I('get.a_id'))));
        }
        return $this->error('系统错误');
    }
    
    /**
     * 修改接口参数信息
     * 
     * @return void
     * @author xiaoyutab<xiaoyutab@qq.com>
     * @version v1.0.0
     * @copyright (c) 2017, xiaoyutab
     * @adtime 2017-07-28 17:49:51
     */
    public function save_project_parameterAction(){
        $apis = new ProjectApiParameterModel();
        $info = $apis->where(array('id'=>I('get.id'),'status'=>array('neq',98)))->find();
        if(!I('post.')){
            $canshu = new ProjectParameterTypeModel();
            $this->assign('c_type',$canshu->get_type(I('get.type')));
            $this->assign('l_info',$info);
            return $this->display('add_project_parameter');
        }
        if($apis->save_info(I('post.'),$info)){
            return $this->success('修改成功',U('save_list_apis',array('id'=>$info['a_id'])));
        }
        return $this->error('参数无变化或系统错误');
    }
    
    
    /**
     * 删除接口参数
     * 
     * @return void
     * @author xiaoyutab<xiaoyutab@qq.com>
     * @version v1.0.0
     * @copyright (c) 2017, xiaoyutab
     * @adtime 2017-07-28 18:04:09
     */
    public function del_project_parameterAction(){
        $apis = new ProjectApiParameterModel();
        if($apis->del_info(I('get.id'))){
            return $this->success('删除成功',U('save_list_apis',array('id'=>I('get.type'))));
        }
        return $this->error('删除失败');
    }
    
    /**
     * 错误代码列表页面以及相关搜索功能
     * 
     * @return void
     * @author xiaoyutab<xiaoyutab@qq.com>
     * @version v1.0.0
     * @copyright (c) 2017, xiaoyutab
     * @adtime 2017-08-07 17:07:48
     */
    public function project_error_codeAction(){
        $project = new ProjectErrorCodeModel();
        if(I('get.keywords')){
            $ids = array();
            $wheres = array();
            foreach(explode(' ', I('get.keywords')) as $v){
                $wheres[] = '%'.$v.'%';
            }
            $where['name|desc'] = array('like',$wheres,'OR');
        }
        $where['pid'] = I('get.id',0,'intval');
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
     * 添加接口错误代码列表
     * 
     * @return void
     * @author xiaoyutab<xiaoyutab@qq.com>
     * @version v1.0.0
     * @copyright (c) 2017, xiaoyutab
     * @adtime 2017-08-07 17:12:11
     */
    public function project_error_code_addAction(){
        if(!I('post.')){
            $project = new ProjectModel();
            $this->assign('project_info',$project->where(array('id'=>I('get.p_id')))->find());
            return $this->display();
        }
        $apis = new ProjectErrorCodeModel();
        if($apis->create_info(I('get.p_id'),I('post.'))){
            return $this->success('添加成功',U('project_error_code',array('id'=>I('get.p_id'))));
        }
        return $this->error('系统错误');
    }
    
    /**
     * 修改接口错误代码
     * 
     * @return void
     * @author xiaoyutab<xiaoyutab@qq.com>
     * @version v1.0.0
     * @copyright (c) 2017, xiaoyutab
     * @adtime 2017-07-28 17:49:51
     */
    public function project_error_code_saveAction(){
        $apis = new ProjectErrorCodeModel();
        $info = $apis->where(array('id'=>I('get.id'),'status'=>array('neq',98)))->find();
        if(!I('post.')){
            $project = new ProjectModel();
            $this->assign('project_info',$project->where(array('id'=>$info['pid']))->find());
            $this->assign('l_info',$info);
            return $this->display('project_error_code_add');
        }
        if($apis->save_info(I('post.'),$info)){
            return $this->success('修改成功',U('project_error_code',array('id'=>$info['pid'])));
        }
        return $this->error('参数无变化或系统错误');
    }
    
    /**
     * 删除错误代码
     * 
     * @return void
     * @author xiaoyutab<xiaoyutab@qq.com>
     * @version v1.0.0
     * @copyright (c) 2017, xiaoyutab
     * @adtime 2017-08-07 17:47:02
     */
    public function project_error_code_delAction(){
        $project = new ProjectErrorCodeModel();
        if($project->delete_info(I('get.id'))){
            return $this->success('删除成功',U('project_error_code',array('id'=>I('get.pid'))));
        }
        return $this->error('删除失败');
    }
    
    /**
     * 标签列表
     * 
     * @return void
     * @author xiaoyutab<xiaoyutab@qq.com>
     * @version v1.0.0
     * @copyright (c) 2017, xiaoyutab
     * @adtime 2017-08-09 14:28:11
     */
    public function tag_listAction(){
        $tag = new TagModel();
        $where = array();
        if(I('get.keywords')){
            $lists = explode(' ', I('get.keywords'));
            $where_s = array();
            foreach($lists as $v){
                $where_s[] = '%'.$v.'%';
            }
            $where['name'] = array('like',$where_s,'or');
        }
        $count = $tag->where($where)->getCount();
        $page = new Page($count, 10);
    	$page->setConfig('prev','上一页');
    	$page->setConfig('next','下一页');
    	$page->setConfig('header','');
    	$page->setPageHtml('normal_page_html','<a href="%PAGE_HREF%" class="tcdNumber">%PAGE_NUMBER%</a>');
    	$page->setPageHtml('current_page_html','<span class="current">%CURRENT_PAGE_NUMBER%</span>');
    	$page->setConfig('theme','%UP_PAGE% %LINK_PAGE% %DOWN_PAGE%');
        $list = $tag->where($where)->getList($page->firstRow, $page->listRows);
        return $this->assign(array(
            'count' => $count,
            'list' => $list,
            'type_list' => array(1=>'文章','小说','新闻','图片','评论','用户','附件'),
            'open_type' => array(1=>'隐藏','显示'),
            'page' => $page->show(),
        ))->display();
    }
    
    /**
     * 添加标签信息
     * 
     * @return void
     * @author xiaoyutab<xiaoyutab@qq.com>
     * @version v1.0.0
     * @copyright (c) 2017, xiaoyutab
     * @adtime 2017-08-09 14:33:26
     */
    public function tag_addAction(){
        if(!I('post.')){
            $this->assign('type_list',array(1=>'文章','小说','新闻','图片','评论','用户','附件'));
            return $this->display();
        }
        $apis = new TagModel();
        if($apis->create_info(I('post.name'), I('post.open_type'), I('post.type'))){
            return $this->success('添加成功',U('tag_list'));
        }
        return $this->error('系统错误');
    }
    
    
    /**
     * 删除标签信息
     * 
     * @return void
     * @author xiaoyutab<xiaoyutab@qq.com>
     * @version v1.0.0
     * @copyright (c) 2017, xiaoyutab
     * @adtime 2017-08-10 18:14:11
     */
    public function tag_delAction(){
        $tag = new TagModel();
        if($tag->delete_info(I('get.id'))){
            return $this->success('删除成功',U('tag_list'));
        }
        return $this->error('删除失败');
    }
    
    /**
     * 修改标签信息
     * 
     * @return void
     * @author xiaoyutab<xiaoyutab@qq.com>
     * @version v1.0.0
     * @copyright (c) 2017, xiaoyutab
     * @adtime 2017-08-10 18:17:15
     */
    public function tag_saveAction(){
        $tag = new TagModel();
        $info = $tag->where(array('id'=>I('get.id'),'status'=>array('neq',98)))->find();
        if(!I('post.')){
            return $this->assign(array(
                't_info' => $info,
                'type_list' => array(1=>'文章','小说','新闻','图片','评论','用户','附件'),
                'open_type' => array(1=>'隐藏','显示'),
            ))->display();
        }
        if($tag->save_info(I('post.'),$info)){
            return $this->success('修改成功',U('tag_list'));
        }
        return $this->error('参数无变化或系统错误');
    }
    
    /**
     * 图片列表信息
     * 
     * @return void
     * @author xiaoyutab<xiaoyutab@qq.com>
     * @version v1.0.0
     * @copyright (c) 2017, xiaoyutab
     * @adtime 2017-08-10 18:26:01
     */
    public function images_listAction(){
        $this->wget('viewer');
        $file = new FileModel();
        $where = array();
        if(I('get.size_min')){
            $where[] = array('size'=>array('egt',I('get.size_min',0,'intval') * 1024));
        }
        if(I('get.size_max')){
            $where[] = array('size'=>array('elt',I('get.size_max',0,'intval') * 1024));
        }
        $count = $file->where($where)->getCount();
        $page = new Page($count, 10);
    	$page->setConfig('prev','上一页');
    	$page->setConfig('next','下一页');
    	$page->setConfig('header','');
    	$page->setPageHtml('normal_page_html','<a href="%PAGE_HREF%" class="tcdNumber">%PAGE_NUMBER%</a>');
    	$page->setPageHtml('current_page_html','<span class="current">%CURRENT_PAGE_NUMBER%</span>');
    	$page->setConfig('theme','%UP_PAGE% %LINK_PAGE% %DOWN_PAGE%');
        $list = $file->where($where)->getList($page->firstRow, $page->listRows);
        $file_link = new FileLinkModel();
        foreach($list as $k => $v){
            $list[$k]['count_num'] = $file_link->where(array(
                'file_id'=>$v['id'],
                'status' => array('neq',98)
                ))->count();
        }
        return $this->assign(array(
            'count' => $count,
            'list' => $list,
            'page' => $page->show(),
        ))->display();
    }
    
    /**
     * 添加图片信息
     * 
     * @return void
     * @author xiaoyutab<xiaoyutab@qq.com>
     * @version v1.0.0
     * @copyright (c) 2017, xiaoyutab
     * @adtime 2017-08-10 18:26:01
     */
    public function images_addAction(){
        if($_FILES['file']['name']){
            $file = new FileModel();
            $fid = $file->upload_file();
            if($fid){
                return $this->success('添加成功',U('images_list'));
            }
            return $this->error('系统错误');
        }
        return $this->display();
    }
}