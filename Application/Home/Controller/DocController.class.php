<?php

namespace Home\Controller;

use Common\Controller\PublicController;
use Home\Model\ProjectModel;
use Home\Model\ProjectApiModel;
use Home\Model\ProjectApiParameterModel;
use Home\Model\ProjectParameterTypeModel;
use Home\Model\ProjectErrorCodeModel;
use Home\Model\FileLinkModel;

/**
 * 文档控制器模型
 * 本控制器中的操作包含了本网站所有文档相关的操作需求
 * 
 * @author xiaoyutab<xiaoyutab@qq.com>
 * @version v1.0.2
 * @copyright (c) 2017, xiaoyutab
 * @adtime 2017-07-30 15:50:12
 */
class DocController extends PublicController {

    /**
     * 项目接口文档列表
     * get.id     项目编号
     * get.api_id 接口编号【如果未传该参数则获取接口列表中的第一条手】
     * 
     * @return void
     * @author xiaoyutab<xiaoyutab@qq.com>
     * @version v1.0.2
     * @copyright (c) 2017, xiaoyutab
     * @adtime 2017-07-30 15:51:23
     */
    public function api_viewAction() {
        $this->wget('jquery')->css('Css/Home/Doc/api.v5');
        $project = new ProjectModel();
        $info = $project->get_project_info(I('get.id',0,'intval'));
        if($info === FALSE){
            Header("HTTP/1.1 404 Not Found"); 
            echo '404 . Not Found';
            exit;
        }
        $file_link = new FileLinkModel();
        $info['logo'] = $file_link->get_file_info($info['id'], 'project', TRUE);
        $err_code = new ProjectErrorCodeModel();
        $error_code = $err_code->get_error_code_list(I('get.id',0,'intval'));
        // 获取应用列表
        $apis = new ProjectApiModel();
        $list = $apis->where(array('p_id'=>I('get.id',0,'intval')))->getList(0,9999);
        $ProjectApiParameterModel = new ProjectApiParameterModel();
        // 获取请求参数
        $ProjectApiParameter_1 = $ProjectApiParameterModel->where(array(
            'a_id'=>I('get.api_id',$list[0]['id'],'intval'),
            'type' => 1
        ))->getList(0,999);
        // 获取返回参数
        $ProjectApiParameter_2 = $ProjectApiParameterModel->where(array(
            'a_id'=>I('get.api_id',$list[0]['id'],'intval'),
            'type' => 2
        ))->getList(0,999);
        // 获取数据类型
        $ProjectParameterTypeModel = new ProjectParameterTypeModel();
        // 获取请求类型列表
        $ProjectParameterType_1 = $ProjectParameterTypeModel->get_type(1);
        // 获取返回类型列表
        $ProjectParameterType_2 = $ProjectParameterTypeModel->get_type(2);
        $this->assign(array(
            'project' => $info,
            'error_code' => $error_code,
            'api_list' => $list,
            'https' => array(1=>'GET',2=>'POST'),
            'request' => array(1=>'JSON','XML','JSONP'),
            'list_1' => $ProjectApiParameter_1,
            'list_2' => $ProjectApiParameter_2,
            'type_1' => $ProjectParameterType_1,
            'type_2' => $ProjectParameterType_2,
        ));
        return $this->display();
    }

}
