<?php
namespace Admin\Controller;
use Admin\Model\DirectoriesDepartmentModel;
use Admin\Model\DirectoriesUserModel;
use Admin\Model\LoanMoldModel;
use Admin\Model\LoanCustomerMarriageModel;
use Admin\Model\LoanHousTypeModel;
use Admin\Model\LoanInsuranceModel;
use Think\Page;
use Admin\Model\BannerModel;
use Admin\Model\FileModel;

/**
 * 系统管理模块控制器
 * 
 * @author xiaoyutab<xiaoyutab@qq.com>
 * @version v1.0.0
 * @copyright (c) 2017, xiaoyutab
 * @adtime 2017-07-12 17:17:47
 */
class SystemController extends CommonController {

    /**
     * 通讯录部门管理
     *      仅提供部门的添加修改删除，不允许查看部门下的详细人员
     * 
     * @return void
     * @author xiaoyutab<xiaoyutab@qq.com>
     * @version v1.0.0
     * @copyright (c) 2017, xiaoyutab
     * @adtime 2017-07-10 10:30:37
     */
    public function department_listAction() {
        $dir = new DirectoriesDepartmentModel();
        $count = $dir->where(array('name'=>array('like','%'.I('get.keywords').'%')))->getCount();
        $page = new Page($count, 10);
    	$page->setConfig('prev','上一页');
    	$page->setConfig('next','下一页');
    	$page->setConfig('header','');
    	$page->setPageHtml('normal_page_html','<a href="%PAGE_HREF%" class="tcdNumber">%PAGE_NUMBER%</a>');
    	$page->setPageHtml('current_page_html','<span class="current">%CURRENT_PAGE_NUMBER%</span>');
    	$page->setConfig('theme','%UP_PAGE% %LINK_PAGE% %DOWN_PAGE%');
        $list = $dir->where(array('name'=>array('like','%'.I('get.keywords').'%')))->getList($page->firstRow, $page->listRows);
        $user = new DirectoriesUserModel();
        foreach($list as $k => $v){
            $list[$k]['fid_name'] = $dir->get_directories_name($v['fid']);
            $list[$k]['count_people'] = $user->get_directories_count_people($v['id']);
            $list[$k]['count_directories'] = $dir->get_directories_count_directories($v['id']);
        }
        return $this->assign(array(
            'count' => $count,
            'list' => $list,
            'page' => $page->show(),
        ))->display();
    }

    /**
     * 添加通讯录部门相关信息
     * 
     * @return void
     * @author xiaoyutab<xiaoyutab@qq.com>
     * @version v1.0.0
     * @copyright (c) 2017, xiaoyutab
     * @adtime 2017-07-12 18:24:40
     */
    public function department_addAction(){
        $dir = new DirectoriesDepartmentModel();
        if(!I('post.')){
            $group_list = $dir->get_directories_department_list();
            $this->assign('group_list',$group_list);
            return $this->display();
        }
        if($dir->create_directories_department(I('post.fid'),I('post.name'),I('post.remarks'))){
            return $this->success('添加成功',U('department_list'));
        }
        return $this->error('添加失败');
    }
    
    /**
     * 修改部门操作
     * 
     * @return void
     * @author xiaoyutab<xiaoyutab@qq.com>
     * @version v1.0.0
     * @copyright (c) 2017, xiaoyutab
     * @adtime 2017-07-13 10:49:50
     */
    public function department_saveAction(){
        $dir = new DirectoriesDepartmentModel();
        if(!I('post.')){
            $group_list = $dir->get_directories_department_list();
            unset($group_list[I('get.id')]);
            $info = $dir->get_directories_info(I('get.id'));
            $this->assign('group_list',$group_list);
            return $this->assign('g_info',$info)->display();
        }
        if($dir->save_directories_department(I('get.id'),I('post.fid'),I('post.name'),I('post.remarks'))){
            return $this->success('添加成功',U('department_list'));
        }
        return $this->error('添加失败');
    }
    
    /**
     * 删除部门信息
     * 
     * @return void
     * @author xiaoyutab<xiaoyutab@qq.com>
     * @version v1.0.0
     * @copyright (c) 2017, xiaoyutab
     * @adtime 2017-07-13 11:12:18
     */
    public function department_delAction(){
        $dir = new DirectoriesDepartmentModel();
        $user = new DirectoriesUserModel();
        if($dir->get_directories_count_directories(I('get.id')) > 0){
            return $this->error('该部门的下属部门不为空');
        }
        if($dir->get_directories_count_directories(I('get.id')) > 0){
            return $this->error('该部门的下属人员不为空');
        }
        if($dir->delete_directories(I('get.id'))){
            return $this->success('删除成功');
        }
        return $this->error('删除失败');
    }
    
    /**
     * 添加通讯录人员
     * 
     * @return void
     * @author xiaoyutab<xiaoyutab@qq.com>
     * @version v1.0.0
     * @copyright (c) 2017, xiaoyutab
     * @adtime 2017-07-13 21:14:34
     */
    public function directories_user_addAction(){
        $this->wget('bootstrap')->wget('cropper')->wget('sitelogo');
        $dir = new DirectoriesDepartmentModel();
        $user = new DirectoriesUserModel();
        if(!I('post.')){
            $group_list = $dir->get_directories_department_list();
            $this->assign('group_list',$group_list);
            return $this->display();
        }
        if($user->create_directories_user(I('post.name'),I('post.phone'),I('post.tel'),I('post.email'),I('post.dep_id'),I('post.avatar'),I('post.position'),I('post.phone_type'),I('post.job_no'))){
            return $this->success('添加成功',U('directories_user_list'));
        }
        return $this->error('添加失败');
    }

    /**
     * 通讯录人员列表
     * 
     * @return void
     * @author xiaoyutab<xiaoyutab@qq.com>
     * @version v1.0.2
     * @copyright (c) 2017, xiaoyutab
     * @adtime 2017-07-14 13:22:08
     */
    public function directories_user_listAction(){
        $times = explode(' ~ ', I('get.times_end'));
        $this->wget('bootstrap')->wget('bootstrap-daterangepicker');
        $dir = new DirectoriesDepartmentModel();
        $user = new DirectoriesUserModel();
        $group_list = $dir->get_directories_department_list();
        $this->assign('group_list',$group_list);// 获取部门列表
        $dep_id = I('get.dep_id',-1,'intval');
        if(isset($_GET['dep_id'])){
            $user->where(array(
                'ad_time' => array(array('gt',strtotime($times[0].' 00:00:01')),array('lt',strtotime($times[1].' 23:59:59')))
            ))->where(array(array(
                'name' => array('like','%'.I('get.keywords').'%'),
                'phone' => array('like','%'.I('get.keywords').'%'),
                '_logic' => 'or',
            )));
            if($dep_id >= 0){
                $user->where(array('dep_id'=>$dep_id));
            }
        }
        $count = $user->getCount();
        $page = new Page($count, 10);
    	$page->setConfig('prev','上一页');
    	$page->setConfig('next','下一页');
    	$page->setConfig('header','');
    	$page->setPageHtml('normal_page_html','<a href="%PAGE_HREF%" class="tcdNumber">%PAGE_NUMBER%</a>');
    	$page->setPageHtml('current_page_html','<span class="current">%CURRENT_PAGE_NUMBER%</span>');
    	$page->setConfig('theme','%UP_PAGE% %LINK_PAGE% %DOWN_PAGE%');
        if(isset($_GET['dep_id'])){
            $user->where(array(
                'ad_time' => array(array('gt',strtotime($times[0].' 00:00:01')),array('lt',strtotime($times[1].' 23:59:59')))
            ))->where(array(array(
                'name' => array('like','%'.I('get.keywords').'%'),
                'phone' => array('like','%'.I('get.keywords').'%'),
                '_logic' => 'or',
            )));
            if($dep_id >= 0){
                $user->where(array('dep_id'=>$dep_id));
            }
        }
        $list = $user->getList($page->firstRow, $page->listRows);
        foreach($list as $k => $v){
            $list[$k]['dep_id_name'] = $group_list[$v['dep_id']];
        }
        return $this->assign(array(
            'count' => $count,
            'g_list' => $list,
            'page' => $page->show(),
        ))->display();
    }
    

    /**
     * 修改通讯录人员信息
     * 
     * @return void
     * @author xiaoyutab<xiaoyutab@qq.com>
     * @version v1.0.0
     * @copyright (c) 2017, xiaoyutab
     * @adtime 2017-07-14 16:24:48
     */
    public function directories_user_saveAction(){
        $this->wget('bootstrap')->wget('cropper')->wget('sitelogo');
        $dir = new DirectoriesDepartmentModel();
        $user = new DirectoriesUserModel();
        if(!I('post.')){
            $group_list = $dir->get_directories_department_list();
            $this->assign('group_list',$group_list);
            $info = $user->where(array('id'=>I('get.id'),'status'=>array('neq',98)))->find();
            $this->assign('u_info',$info);
            return $this->display();
        }
        if($user->save_directories_user(I('get.id'),I('post.name'),I('post.phone'),I('post.tel'),I('post.email'),I('post.dep_id'),I('post.avatar'),I('post.position'),I('post.phone_type'),I('post.job_no'))){
            return $this->success('修改成功',U('directories_user_list'));
        }
        return $this->error('修改失败，可能未更改该人员信息');
    }
    
    /**
     * 删除通讯录条目信息
     * 
     * @return void
     * @author xiaoyutab<xiaoyutab@qq.com>
     * @version v1.0.0
     * @copyright (c) 2017, xiaoyutab
     * @adtime 2017-07-14 16:29:57
     */
    public function directories_user_delAction(){
        $user = new DirectoriesUserModel();
        if($user->delete_directories_user(I('get.id'))){
            return $this->success('删除成功');
        }
        return $this->error('删除失败');
    }
    
    /**
     * 抵押顺位管理列表
     * 
     * @return void
     * @author xiaoyutab<xiaoyutab@qq.com>
     * @version v1.0.0
     * @copyright (c) 2017, xiaoyutab
     * @adtime 2017-07-14 17:33:33
     */
    public function loan_mold_listAction(){
        $loan = new LoanMoldModel();
        $key = I('get.keywords','','trim');
        $count = $loan->where(array('name'=>array('like','%'.$key.'%')))->getCount();
        $page = new Page($count, 10);
    	$page->setConfig('prev','上一页');
    	$page->setConfig('next','下一页');
    	$page->setConfig('header','');
    	$page->setPageHtml('normal_page_html','<a href="%PAGE_HREF%" class="tcdNumber">%PAGE_NUMBER%</a>');
    	$page->setPageHtml('current_page_html','<span class="current">%CURRENT_PAGE_NUMBER%</span>');
    	$page->setConfig('theme','%UP_PAGE% %LINK_PAGE% %DOWN_PAGE%');
        $list = $loan->where(array('name'=>array('like','%'.$key.'%')))->getList($page->firstRow, $page->listRows);
        $this->assign(array(
            'l_list' => $list,
            'count' => $count,
            'page' => $page->show(),
        ));
        return $this->display();
    }
    
    /**
     * 删除抵押顺位信息
     * 
     * @return void
     * @author xiaoyutab<xiaoyutab@qq.com>
     * @version v1.0.0
     * @copyright (c) 2017, xiaoyutab
     * @adtime 2017-07-14 17:03:27
     */
    public function loan_mold_delAction(){
        $loan = new LoanMoldModel();
        if($loan->delete_loan_mold(I('get.id'))){
            return $this->success('删除成功');
        }
        return $this->error('删除失败');
    }
    
    /**
     * 修改抵押顺位信息
     * 
     * @return void
     * @author xiaoyutab<xiaoyutab@qq.com>
     * @version v1.0.0
     * @copyright (c) 2017, xiaoyutab
     * @adtime 2017-07-14 17:33:09
     */
    public function loan_mold_saveAction(){
        $loan = new LoanMoldModel();
        if(!I('post.')){
            $info = $loan->get_info(I('get.id'));
            if($info && $info['status'] != '98'){
                // 获取到了详情信息，进行编辑操作
                return $this->assign('l_info',$info)->display();
            }
            return $this->error('未查询到该抵押顺位');
        }
        if($loan->save_loan_mold(I('get.id'),I('post.name'))){
            return $this->success('修改成功',U('loan_mold_list'));
        }
        return $this->error('修改失败，可能未更改条目名称');
    }
    
    /**
     * 添加抵押顺位操作
     * 
     * @return void
     * @author xiaoyutab<xiaoyutab@qq.com>
     * @version v1.0.0
     * @copyright (c) 2017, xiaoyutab
     * @adtime 2017-07-14 17:36:14
     */
    public function loan_mold_addAction(){
        if(!I('post.')){
            return $this->display('loan_mold_save');
        }
        $loan = new LoanMoldModel();
        if($loan->create_loan_mold(I('post.name'))){
            return $this->success('添加成功',U('loan_mold_list'));
        }
        return $this->error('操作失败');
    }
    
    /**
     * 借款人婚姻情况管理
     * 
     * @return void
     * @author xiaoyutab<xiaoyutab@qq.com>
     * @version v1.0.0
     * @copyright (c) 2017, xiaoyutab
     * @adtime 2017-07-14 18:19:50
     */
    public function loan_customer_marriage_listAction(){
        $loan = new LoanCustomerMarriageModel();
        $key = I('get.keywords','','trim');
        $count = $loan->where(array('name'=>array('like','%'.$key.'%')))->getCount();
        $page = new Page($count, 10);
    	$page->setConfig('prev','上一页');
    	$page->setConfig('next','下一页');
    	$page->setConfig('header','');
    	$page->setPageHtml('normal_page_html','<a href="%PAGE_HREF%" class="tcdNumber">%PAGE_NUMBER%</a>');
    	$page->setPageHtml('current_page_html','<span class="current">%CURRENT_PAGE_NUMBER%</span>');
    	$page->setConfig('theme','%UP_PAGE% %LINK_PAGE% %DOWN_PAGE%');
        $list = $loan->where(array('name'=>array('like','%'.$key.'%')))->getList($page->firstRow, $page->listRows);
        $this->assign(array(
            'l_list' => $list,
            'count' => $count,
            'page' => $page->show(),
        ));
        return $this->display();
    }
    
    /**
     * 添加借款人婚姻情况
     * 
     * @return void
     * @author xiaoyutab<xiaoyutab@qq.com>
     * @version v1.0.0
     * @copyright (c) 2017, xiaoyutab
     * @adtime 2017-07-14 17:36:14
     */
    public function loan_customer_marriage_addAction(){
        if(!I('post.')){
            return $this->display();
        }
        $loan = new LoanCustomerMarriageModel();
        if($loan->create_loan_customer_marriage(I('post.name'))){
            return $this->success('添加成功',U('loan_customer_marriage_list'));
        }
        return $this->error('操作失败');
    }
    
    /**
     * 修改借款人婚姻情况
     * 
     * @return void
     * @author xiaoyutab<xiaoyutab@qq.com>
     * @version v1.0.0
     * @copyright (c) 2017, xiaoyutab
     * @adtime 2017-07-14 18:32:12
     */
    public function loan_customer_marriage_saveAction(){
        $loan = new LoanCustomerMarriageModel();
        if(!I('post.')){
            $info = $loan->get_info(I('get.id'));
            if($info && $info['status'] != '98'){
                // 获取到了详情信息，进行编辑操作
                return $this->assign('l_info',$info)->display('loan_customer_marriage_add');
            }
            return $this->error('未查询到该借款人婚姻情况');
        }
        if($loan->save_loan_customer_marriage(I('get.id'),I('post.name'))){
            return $this->success('修改成功',U('loan_mold_list'));
        }
        return $this->error('修改失败，可能未更改条目名称');
    }
    
    /**
     * 删除借款人婚姻情况
     * 
     * @return void
     * @author xiaoyutab<xiaoyutab@qq.com>
     * @version v1.0.0
     * @copyright (c) 2017, xiaoyutab
     * @adtime 2017-07-14 18:38:22
     */
    public function loan_customer_marriage_delAction(){
        $loan = new LoanCustomerMarriageModel();
        if($loan->delete_loan_customer_marriage(I('get.id'))){
            return $this->success('删除成功');
        }
        return $this->error('删除失败');
    }
    
    /**
     * 房产类型管理
     * 
     * @return void
     * @author xiaoyutab<xiaoyutab@qq.com>
     * @version v1.0.0
     * @copyright (c) 2017, xiaoyutab
     * @adtime 2017-07-14 18:47:07
     */
    public function loan_hous_type_listAction(){
        $loan = new LoanHousTypeModel();
        $key = I('get.keywords','','trim');
        $count = $loan->where(array('name'=>array('like','%'.$key.'%')))->getCount();
        $page = new Page($count, 10);
    	$page->setConfig('prev','上一页');
    	$page->setConfig('next','下一页');
    	$page->setConfig('header','');
    	$page->setPageHtml('normal_page_html','<a href="%PAGE_HREF%" class="tcdNumber">%PAGE_NUMBER%</a>');
    	$page->setPageHtml('current_page_html','<span class="current">%CURRENT_PAGE_NUMBER%</span>');
    	$page->setConfig('theme','%UP_PAGE% %LINK_PAGE% %DOWN_PAGE%');
        $list = $loan->where(array('name'=>array('like','%'.$key.'%')))->getList($page->firstRow, $page->listRows);
        $this->assign(array(
            'l_list' => $list,
            'count' => $count,
            'page' => $page->show(),
        ));
        return $this->display();
    }
    
    /**
     * 添加房产类型
     * 
     * @return void
     * @author xiaoyutab<xiaoyutab@qq.com>
     * @version v1.0.0
     * @copyright (c) 2017, xiaoyutab
     * @adtime 2017-07-14 18:51:45
     */
    public function loan_hous_type_addAction(){
        if(!I('post.')){
            return $this->display();
        }
        $loan = new LoanHousTypeModel();
        if($loan->create_info(I('post.name'))){
            return $this->success('添加成功',U('loan_hous_type_list'));
        }
        return $this->error('操作失败');
    }
    
    /**
     * 修改房产类型
     * 
     * @return void
     * @author xiaoyutab<xiaoyutab@qq.com>
     * @version v1.0.1
     * @copyright (c) 2017, xiaoyutab
     * @adtime 2017-07-14 18:54:13
     */
    public function loan_hous_type_saveAction(){
        $loan = new LoanHousTypeModel();
        if(!I('post.')){
            $info = $loan->get_info(I('get.id'));
            if($info && $info['status'] != '98'){
                // 获取到了详情信息，进行编辑操作
                return $this->assign('l_info',$info)->display('loan_customer_marriage_add');
            }
            return $this->error('未查询到该借款人婚姻情况');
        }
        if($loan->save_info(I('get.id'),I('post.name'))){
            return $this->success('修改成功',U('loan_hous_type_list'));
        }
        return $this->error('修改失败，可能未更改条目名称');
    }
    
    /**
     * 删除房产类型
     * 
     * @return void
     * @author xiaoyutab<xiaoyutab@qq.com>
     * @version v1.0.0
     * @copyright (c) 2017, xiaoyutab
     * @adtime 2017-07-14 18:38:22
     */
    public function loan_hous_type_delAction(){
        $loan = new LoanHousTypeModel();
        if($loan->delete_info(I('get.id'))){
            return $this->success('删除成功');
        }
        return $this->error('删除失败');
    }
    
    /**
     * 抵押权人管理
     * 
     * @return void
     * @author xiaoyutab<xiaoyutab@qq.com>
     * @version v1.0.0
     * @copyright (c) 2017, xiaoyutab
     * @adtime 2017-07-14 18:57:11
     */
    public function loan_insurance_listAction(){
        $loan = new LoanInsuranceModel();
        $key = I('get.keywords','','trim');
        $count = $loan->where(array('name'=>array('like','%'.$key.'%')))->getCount();
        $page = new Page($count, 10);
    	$page->setConfig('prev','上一页');
    	$page->setConfig('next','下一页');
    	$page->setConfig('header','');
    	$page->setPageHtml('normal_page_html','<a href="%PAGE_HREF%" class="tcdNumber">%PAGE_NUMBER%</a>');
    	$page->setPageHtml('current_page_html','<span class="current">%CURRENT_PAGE_NUMBER%</span>');
    	$page->setConfig('theme','%UP_PAGE% %LINK_PAGE% %DOWN_PAGE%');
        $list = $loan->where(array('name'=>array('like','%'.$key.'%')))->getList($page->firstRow, $page->listRows);
        $this->assign(array(
            'l_list' => $list,
            'count' => $count,
            'page' => $page->show(),
        ));
        return $this->display();
    }
    
    /**
     * 添加抵押权人
     * 
     * @return void
     * @author xiaoyutab<xiaoyutab@qq.com>
     * @version v1.0.0
     * @copyright (c) 2017, xiaoyutab
     * @adtime 2017-07-14 18:51:45
     */
    public function loan_insurance_addAction(){
        if(!I('post.')){
            return $this->display();
        }
        $loan = new LoanInsuranceModel();
        if($loan->create_info(I('post.name'))){
            return $this->success('添加成功',U('loan_insurance_list'));
        }
        return $this->error('操作失败');
    }
    
    /**
     * 修改抵押权人
     * 
     * @return void
     * @author xiaoyutab<xiaoyutab@qq.com>
     * @version v1.0.1
     * @copyright (c) 2017, xiaoyutab
     * @adtime 2017-07-14 18:54:13
     */
    public function loan_insurance_saveAction(){
        $loan = new LoanInsuranceModel();
        if(!I('post.')){
            $info = $loan->get_info(I('get.id'));
            if($info && $info['status'] != '98'){
                // 获取到了详情信息，进行编辑操作
                return $this->assign('l_info',$info)->display('loan_customer_marriage_add');
            }
            return $this->error('未查询到该借款人婚姻情况');
        }
        if($loan->save_info(I('get.id'),I('post.name'))){
            return $this->success('修改成功',U('loan_insurance_list'));
        }
        return $this->error('修改失败，可能未更改条目名称');
    }
    
    /**
     * 删除抵押权人
     * 
     * @return void
     * @author xiaoyutab<xiaoyutab@qq.com>
     * @version v1.0.0
     * @copyright (c) 2017, xiaoyutab
     * @adtime 2017-07-14 18:57:57
     */
    public function loan_insurance_delAction(){
        $loan = new LoanInsuranceModel();
        if($loan->delete_info(I('get.id'))){
            return $this->success('删除成功');
        }
        return $this->error('删除失败');
    }
    
    /**
     * 获取banner列表页面
     * 
     * @return void
     * @author xiaoyutab<xiaoyutab@qq.com>
     * @version v1.0.0
     * @copyright (c) 2017, xiaoyutab
     * @adtime 2017-7-18 21:19:33
     */
    public function banner_listAction(){
        $banner = new BannerModel();
        $key = I('get.keywords','','trim');
        $count = $banner->where(array('title'=>array('like','%'.$key.'%')))->getCount();
        $page = new Page($count, 10);
    	$page->setConfig('prev','上一页');
    	$page->setConfig('next','下一页');
    	$page->setConfig('header','');
    	$page->setPageHtml('normal_page_html','<a href="%PAGE_HREF%" class="tcdNumber">%PAGE_NUMBER%</a>');
    	$page->setPageHtml('current_page_html','<span class="current">%CURRENT_PAGE_NUMBER%</span>');
    	$page->setConfig('theme','%UP_PAGE% %LINK_PAGE% %DOWN_PAGE%');
        $list = $banner->where(array('title'=>array('like','%'.$key.'%')))->order('`order` DESC,`id` DESC')->getList($page->firstRow, $page->listRows,FALSE,'`order` DESC,`id` DESC');
        $this->assign(array(
            'l_list' => $list,
            'count' => $count,
            'page' => $page->show(),
        ));
        return $this->display();
    }
    
    /**
     * 添加banner页面
     * 
     * @return void
     * @author xiaoyutab<xiaoyutab@qq.com>
     * @version v1.0.0
     * @copyright (c) 2017, xiaoyutab
     * @adtime 2017-7-18 21:20:24
     */
    public function banner_addAction(){
        if(!I('post.')){
            return $this->display();
        }
        $loan = new BannerModel();
        $file = new FileModel();
        $fid = $file->upload_file();
        dump($fid);exit;// 获取上传的图片信息
        if($loan->create_info(I('post.'))){
            return $this->success('添加成功',U('banner_list'));
        }
        return $this->error('操作失败');
    }
}
