<?php

namespace Admin\Controller;
use Admin\Model\ProjectModel;
use Think\Page;
use Admin\Model\ProjectParameterTypeModel;
use Admin\Model\ProjectApiModel;
use Admin\Model\ProjectApiParameterModel;
use Org\Office\Word\PHPWord;

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
    	$page->setPageHtml('top_html','<div class="pagination" style="margin:0;"><ul>%PAGE_CONTENT_HTML%</ul></div>');
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
     * 接口文档导出操作
     * 
     * @return void
     * @author xiaoyutab<xiaoyutab@qq.com>
     * @version v1.0.0
     * @copyright (c) 2017, xiaoyutab
     * @adtime 2017-07-30 14:05:46
     */
    public function list_apis_downloadAction(){
        $project = new ProjectModel();
        $info = $project->where(array('id'=>I('get.id'),'status'=>array('neq',98)))->find();
        if(empty($info)){
            return $this->error('系统错误');
        }
        $week = array('日','一','二','三','四','五','六');
        return $this->exec_word(array(
            'title' => $info['name'],// 项目名称
            'author' => $info['doc_author'],// 文档编写人员
            'com_author' => $info['author'],// 项目参与人员
            'version' => 'v 1.0.0-'.date('Y-m-d').'_alpha',// 测试版
//            'version' => 'v 1.0.0-'.date('Y-m-d').'_Beta',// 正式版
//            'version' => 'v 1.0.0-'.date('Y-m-d').'_Release',// 发行版
            'create_date' => date('Y年m月d日 星期'.$week[date('w')]),
            'copy' => $info['com_name'],
        ));
    }
    
    /**
     * 生成Word文档并提供下载
     * 
     * @param array $data 接口文档中的数据
     * @return NULL
     * @author xiaoyutab<xiaoyutab@qq.com>
     * @version v1.0.0
     * @copyright (c) 2017, xiaoyutab
     * @adtime 2017-07-30 14:20:58
     */
    private function exec_word($data = array()){
        // 创建doc对象
        $PHPWord = new PHPWord();
        // 获取文档属性
        $properties = $PHPWord->getProperties();
        // 设置文档的作者
        $properties->setCreator('于茂敬');
        // 所属公司
        $properties->setCompany('个人');
        // 文档标题
        $properties->setTitle('API接口文档');
        // 以下就是Word的正文内容了
        // ---------------------------------
        // 设置文档默认字体
        $PHPWord->setDefaultFontName('微软雅黑');
        // 设置文档默认字体大小，单位px
        $PHPWord->setDefaultFontSize(12);
        // 添加一个页面并设置相关属性
        $section = $PHPWord->createSection();
        // 获取页脚属性，可以使用$footer->xxx来进行设置相关信息
        $footer = $section->createFooter();
        $footer->addPreserveText('{PAGE} / {NUMPAGES}',array(
            'italic' => true,// 使用斜体
            'color' => 'c4c4c4',// 灰色字体
        ),array(
            'align' => 'center',
        ));
        // 第一页------------------------
        $section->addTextBreak(10);
        $section->addText($data['title'], array(
            'size'=>40, // 字体大小
            'bold'=>true,// 粗体
            'underline' => \PHPWord_Style_Font::UNDERLINE_DASH,// 下划线
        ),array(
            'align' => 'center',
        ));
        $section->addTextBreak(20);
        // 添加表格
        $table = $section->addTable();
        // 为表格添加一行
        $table->addRow(30);
        $table_style = array(
            'borderTopSize' => 1,
            'borderLeftSize' => 1,
            'borderRightSize' => 1,
            'borderBottomSize' => 1,
            'valign' => 'center'
        );
        $table->addCell(null,$table_style)->addText('文档版本');
        $table->addCell(null,$table_style)->addText($data['version']);
        $table->addRow(30);
        $table->addCell(null,$table_style)->addText('创建日期');
        $table->addCell(null,$table_style)->addText($data['create_date']);
        $table->addRow(30);
        $table->addCell(null,$table_style)->addText('参与人员');
        $table->addCell(null,$table_style)->addText($data['com_author']);
        $table->addRow(30);
        $table->addCell(null,$table_style)->addText('文档作者');
        $table->addCell(null,$table_style)->addText($data['author']);
        $table->addRow(30);
        $table->addCell(null,$table_style)->addText('版权所有');
        $table->addCell(null,$table_style)->addText($data['copy']);
        // 以下为存储Word数据和导出下载的代码
        $objWriter = \PHPWord_IOFactory::createWriter($PHPWord, 'Word2007');
        $temp_runtime = 'Public/Runtime/temp_run.doc';
        $objWriter->save($temp_runtime);
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename="helloWorld.docx"');  
        header('Content-Transfer-Encoding: binary');  
        // load the file to send:  
        readfile($temp_runtime);
        return NULL;
    }
}