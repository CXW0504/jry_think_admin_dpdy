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
use Admin\Model\UserOfficeModel;
use Admin\Model\UserSchoolModel;
use Admin\Model\UserCityModel;

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
     * 清除缓存功能
     *      作用为清除本网站全部的缓存、日志
     * 
     * @return void
     * @author xiaoyutab<xiaoyutab@qq.com>
     * @version v1.0.0
     * @copyright (c) 2017, xiaoyutab
     * @adtime 2017-07-30 17:42:33
     */
    public function refresh_allAction(){
        // 删除缓存
        $this->deldir(RUNTIME_PATH);
        return $this->success('缓存清除成功,正在跳回首页',U('Index/hello'),5);
    }
    
    /**
     * 循环删除文件操作
     *      仅删除文件,不删除文件夹[该操作会输出删除的每一个文件]
     * 
     * @param string $dir 要删除的文件夹
     * @return boolean
     * @author xiaoyutab<xiaoyutab@qq.com>
     * @version v1.0.0
     * @copyright (c) 2017, xiaoyutab
     * @adtime 2017-07-30 17:43:18
     */
    private function deldir($dir = '') {
        if(empty($dir)){
            return FALSE;
        }
        $dh = opendir($dir);
        while ($file = readdir($dh)) {
            if ($file != "." && $file != "..") {
                $fullpath = $dir . "/" . $file;
                if (!is_dir($fullpath)) {
                    echo $fullpath.'<br/>';
                    unlink($fullpath);
                } else {
                    $this->deldir($fullpath);
                }
            }
        }
    }

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
    	$page->setPageHtml('top_html','<div class="pagination" style="margin:0;"><ul>%PAGE_CONTENT_HTML%</ul></div>');
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
        $file = new FileModel();
        foreach($list as $k => $v){
            $info = $file->get_info($v['file_id']);
            // 设置文件图片，如果没有就显示暂无图片的标
            $list[$k]['thumb_100'] = $info['thumb_100']?$info['thumb_100']:'../Images/Admin/none.jpg';
        }
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
        if($loan->create_info(I('post.'),$fid)){
            return $this->success('添加成功',U('banner_list'));
        }
        return $this->error('操作失败');
    }
    
    /**
     * 删除banner操作
     * 
     * @return void
     * @author xiaoyutab<xiaoyutab@qq.com>
     * @version v1.0.0
     * @copyright (c) 2017, xiaoyutab
     * @adtime 2017-7-19 15:40:36
     */
    public function banner_delAction(){
        $banner = new BannerModel();
        if($banner->delete_banner(I('get.id'))){
            return $this->success('删除成功',U('banner_list'));
        }
        return $this->error('删除失败');
    }
    
    /**
     * 修改Banner详情信息
     * 
     * @return void
     * @author xiaoyutab<xiaoyutab@qq.com>
     * @version v1.0.0
     * @copyright (c) 2017, xiaoyutab
     * @adtime 2017-7-19 16:06:16
     */
    public function banner_saveAction(){
        $banner = new BannerModel();
        $info = $banner->get_info(I('get.id'));
        if(!$info || $info['status'] == '98'){
            return $this->error('查无数据');
        }
        $file = new FileModel();
        $info_temp = $file->get_info($info['file_id']);
        // 设置文件图片，如果没有就显示暂无图片的标
        $info['thumb_100'] = $info_temp['file_path']?$info_temp['file_path']:'../Images/Admin/none.jpg';
        if(!I('post.')){
            return $this->assign('l_info',$info)->display('banner_add');
        }
        $fid = $file->upload_file();
        if($banner->update_info(I('get.id'),I('post.'),$fid)){
            return $this->success('更新成功',U('banner_list'));
        }
        return $this->error('修改失败，可能未更改条目信息');
    }
    
    /**
     * 用户职位管理详情
     * 
     * @return void
     * @author xiaoyutab<xiaoyutab@qq.com>
     * @version v1.0.0
     * @copyright (c) 2017, xiaoyutab
     * @adtime 2017-08-03 11:24:55
     */
    public function user_officeAction(){
        $user_office = new UserOfficeModel();
        $wheres = array();
        foreach(explode(' ', I('get.keywords')) as $v){
            $wheres[] = '%'.$v.'%';
        }
        $where = array('office_name' => array('like',$wheres,'OR'));
        $count = $user_office->where($where)->getCount();
        $page = new Page($count, 10);
    	$page->setConfig('prev','上一页');
    	$page->setConfig('next','下一页');
    	$page->setConfig('header','');
    	$page->setPageHtml('normal_page_html','<a href="%PAGE_HREF%" class="tcdNumber">%PAGE_NUMBER%</a>');
    	$page->setPageHtml('current_page_html','<span class="current">%CURRENT_PAGE_NUMBER%</span>');
    	$page->setConfig('theme','%UP_PAGE% %LINK_PAGE% %DOWN_PAGE%');
        $list = $user_office->where($where)->getList($page->firstRow, $page->listRows,FALSE,'`fid` ASC,`ad_time` DESC,`id` DESC');
        foreach($list as $k => $v){
            $fids = $user_office->where(array('id'=>$v['fid']))->cache(TRUE)->find();
            $list[$k]['fid_name'] = $fids['office_name']?$fids['office_name']:'--';
            $list[$k]['count_office'] = $user_office->where(array('fid'=>$v['id']))->getCount();
        }
        return $this->assign(array(
            'count' => $count,
            'list' => $list,
            'page' => $page->show(),
        ))->display();
    }
    
    /**
     * 添加用户职位信息
     * 
     * @return void
     * @author xiaoyutab<xiaoyutab@qq.com>
     * @version v1.0.0
     * @copyright (c) 2017, xiaoyutab
     * @adtime 2017-08-03 12:39:32
     */
    public function user_office_addAction(){
        $user_office = new UserOfficeModel();
        if(!I('post.')){
            $user_office_list = $user_office->get_user_office_list();
            $this->assign('user_office_list',$user_office_list);
            return $this->display();
        }
        if($user_office->create_user_office_data(I('post.fid'),I('post.office_name'))){
            return $this->success('添加成功',U('user_office'));
        }
        return $this->error('添加失败');
    }
    
    /**
     * 删除用户职位信息
     * 
     * @return void
     * @author xiaoyutab<xiaoyutab@qq.com>
     * @version v1.0.0
     * @copyright (c) 2017, xiaoyutab
     * @adtime 2017-08-03 12:51:45
     */
    public function user_office_delAction(){
        $user_office = new UserOfficeModel();
        $id = I('get.id');
        if($id < 0){
            return $this->error('参数错误');
        }
        if($user_office->where(array('fid'=>$id))->getCount() > 0){
            return $this->error('该职位下子职位不为空');
        }
        if($user_office->delete_user_office(I('get.id'))){
            return $this->success('删除成功');
        }
        return $this->error('删除失败');
    }
    
    /**
     * 修改用户职位操作
     * 
     * @return void
     * @author xiaoyutab<xiaoyutab@qq.com>
     * @version v1.0.0
     * @copyright (c) 2017, xiaoyutab
     * @adtime 2017-08-03 13:19:32
     */
    public function user_office_saveAction(){
        $user_office = new UserOfficeModel();
        if(!I('post.')){
            $user_office_info = $user_office->where(array('id'=>I('get.id'),'status'=>array('neq',98)))->find();
            if(empty($user_office_info)){
                return $this->error('参数错误');
            }
            $list = $user_office->get_user_office_list($fid);
            unset($list[I('get.id')]);
            $this->assign('user_office_list',$list)->assign('u_info',$user_office_info);
            return $this->display();
        }
        if($user_office->save_user_office(I('get.id'),I('post.fid'),I('post.office_name'))){
            return $this->success('更新成功',U('user_office'));
        }
        return $this->error('更新失败');
    }
    
    /**
     * 用户大学学校管理详情
     * 
     * @return void
     * @author xiaoyutab<xiaoyutab@qq.com>
     * @version v1.0.0
     * @copyright (c) 2017, xiaoyutab
     * @adtime 2017-08-03 13:53:28
     */
    public function user_schoolAction(){
        $user_office = new UserSchoolModel();
        $wheres = array();
        foreach(explode(' ', I('get.keywords')) as $v){
            $wheres[] = '%'.$v.'%';
        }
        $where = array(
            'fid' => 0,
            'name' => array('like',$wheres,'OR')
        );
        $count = $user_office->where($where)->getCount();
        $page = new Page($count, 10);
    	$page->setConfig('prev','上一页');
    	$page->setConfig('next','下一页');
    	$page->setConfig('header','');
    	$page->setPageHtml('normal_page_html','<a href="%PAGE_HREF%" class="tcdNumber">%PAGE_NUMBER%</a>');
    	$page->setPageHtml('current_page_html','<span class="current">%CURRENT_PAGE_NUMBER%</span>');
    	$page->setConfig('theme','%UP_PAGE% %LINK_PAGE% %DOWN_PAGE%');
        $list = $user_office->where($where)->getList($page->firstRow, $page->listRows);
        foreach($list as $k => $v){
            $list[$k]['count_office'] = $user_office->where(array('fid'=>$v['id']))->getCount();
        }
        return $this->assign(array(
            'count' => $count,
            'list' => $list,
            'page' => $page->show(),
        ))->display();
    }
    
    /**
     * 添加用户学区信息
     *      添加学校所在的省市
     * 
     * @return void
     * @author xiaoyutab<xiaoyutab@qq.com>
     * @version v1.0.0
     * @copyright (c) 2017, xiaoyutab
     * @adtime 2017-08-03 14:00:57
     */
    public function user_school_addAction(){
        if(!I('post.')){
            return $this->display();
        }
        $user_school = new UserSchoolModel();
        if($user_school->create_user_office_data(I('post.office_name'))){
            return $this->success('添加成功',U('user_school'));
        }
        return $this->error('添加失败');
    }
    
    /**
     * 修改用户学校地区操作
     * 
     * @return void
     * @author xiaoyutab<xiaoyutab@qq.com>
     * @version v1.0.0
     * @copyright (c) 2017, xiaoyutab
     * @adtime 2017-08-03 19:01:04
     */
    public function user_school_saveAction(){
        $user_school = new UserSchoolModel();
        if(!I('post.')){
            $user_school_info = $user_school->where(array('id'=>I('get.id'),'status'=>array('neq',98)))->find();
            if(empty($user_school_info)){
                return $this->error('参数错误');
            }
            $this->assign('u_info',$user_school_info);
            return $this->display();
        }
        if($user_school->save_user_school_city(I('get.id'),I('post.office_name'))){
            return $this->success('更新成功',U('user_school'));
        }
        return $this->error('更新失败');
    }
    
    /**
     * 删除用户学校地区信息
     * 
     * @return void
     * @author xiaoyutab<xiaoyutab@qq.com>
     * @version v1.0.0
     * @copyright (c) 2017, xiaoyutab
     * @adtime 2017-08-03 19:08:59
     */
    public function user_school_delAction(){
        $user_school = new UserSchoolModel();
        $id = I('get.id');
        if($id < 0){
            return $this->error('参数错误');
        }
        if($user_school->delete_user_school_city(I('get.id'))){
            return $this->success('删除成功');
        }
        return $this->error('删除失败');
    }
    
    /**
     * 用户大学学校列表
     * 
     * @return void
     * @author xiaoyutab<xiaoyutab@qq.com>
     * @version v1.0.0
     * @copyright (c) 2017, xiaoyutab
     * @adtime 2017-08-03 19:12:59
     */
    public function user_school_listAction(){
        $user_office = new UserSchoolModel();
        $wheres = array();
        foreach(explode(' ', I('get.keywords')) as $v){
            $wheres[] = '%'.$v.'%';
        }
        $where = array(
            'fid' => I('get.id',1,'intval'),
            'name' => array('like',$wheres,'OR')
        );
        $count = $user_office->where($where)->getCount();
        $page = new Page($count, 10);
    	$page->setConfig('prev','上一页');
    	$page->setConfig('next','下一页');
    	$page->setConfig('header','');
    	$page->setPageHtml('normal_page_html','<a href="%PAGE_HREF%" class="tcdNumber">%PAGE_NUMBER%</a>');
    	$page->setPageHtml('current_page_html','<span class="current">%CURRENT_PAGE_NUMBER%</span>');
    	$page->setConfig('theme','%UP_PAGE% %LINK_PAGE% %DOWN_PAGE%');
        $list = $user_office->where($where)->getList($page->firstRow, $page->listRows);
        $f_city_name = $user_office->where(array('id'=>I('get.id'),'status'=>array('neq',98)))->find();
        return $this->assign(array(
            'count' => $count,
            'list' => $list,
            'city_name' => $f_city_name['name'],
            'page' => $page->show(),
        ))->display();
    }
    
    /**
     * 添加本校区的学校信息
     * 
     * @return void
     * @author xiaoyutab<xiaoyutab@qq.com>
     * @version v1.0.0
     * @copyright (c) 2017, xiaoyutab
     * @adtime 2017-08-04 14:13:17
     */
    public function user_school_list_addAction(){
        $user_school = new UserSchoolModel();
        if(I('post.')){
            if($user_school->create_user_school_name(I('get.fid'),I('post.name'))){
                return $this->success('添加成功',U('user_school_list',array('id'=>I('get.fid'))));
            }
            return $this->error('添加失败');
        }
        $info = $user_school->where(array('id'=>I('get.fid')))->find();
        if(empty($info)){
            return $this->error('参数错误');
        }
        return $this->assign('city_name',$info['name'])->display();
    }
    
    /**
     * 修改用户学校信息
     * 
     * @return void
     * @author xiaoyutab<xiaoyutab@qq.com>
     * @version v1.0.0
     * @copyright (c) 2017, xiaoyutab
     * @adtime 2017-08-04 15:52:24
     */
    public function user_school_list_saveAction(){
        $user_school = new UserSchoolModel();
        $info = $user_school->where(array('id'=>I('get.fid')))->find();
        $s_info = $user_school->where(array('id'=>I('get.id')))->find();
        if(empty($info) || empty($s_info)){
            return $this->error('参数错误');
        }
        if(I('post.')){
            if($user_school->update_user_school_name(I('get.id'),I('post.name'))){
                return $this->success('更新成功',U('user_school_list',array('id'=>I('get.fid'))));
            }
            return $this->error('更新失败');
        }
        return $this->assign('city_name',$info['name'])
                ->assign('school_name',$s_info['name'])
                ->display('user_school_list_add');
    }
    
    /**
     * 删除用户学校信息
     * 
     * @return void
     * @author xiaoyutab<xiaoyutab@qq.com>
     * @version v1.0.0
     * @copyright (c) 2017, xiaoyutab
     * @adtime 2017-08-04 15:52:32
     */
    public function user_school_list_delAction(){
        $user_school = new UserSchoolModel();
        $id = I('get.id');
        if($id < 0){
            return $this->error('参数错误');
        }
        if($user_school->delete_user_school_city(I('get.id'))){
            return $this->success('删除成功',U('user_school_list',array('id'=>I('get.fid'))));
        }
        return $this->error('删除失败');
    }
    
    /**
     * 省级单位列表
     * 
     * @return void
     * @author xiaoyutab<xiaoyutab@qq.com>
     * @version v1.0.0
     * @copyright (c) 2017, xiaoyutab
     * @adtime 2017-08-22 11:08:55
     */
    public function user_cityAction(){
        $city = new UserCityModel();
        $wheres = array();
        foreach(explode(' ', I('get.keywords')) as $v){
            $wheres[] = '%'.$v.'%';
        }
        $where = array(
            'name|code|abbreviation' => array('like',$wheres,'OR'),
            'id' => array('lt',100),
            'commend' => array('neq',2)
        );
        $count = $city->where($where)->count();
        $page = new Page($count, 10);
    	$page->setConfig('prev','上一页');
    	$page->setConfig('next','下一页');
    	$page->setConfig('header','');
    	$page->setPageHtml('normal_page_html','<a href="%PAGE_HREF%" class="tcdNumber">%PAGE_NUMBER%</a>');
    	$page->setPageHtml('current_page_html','<span class="current">%CURRENT_PAGE_NUMBER%</span>');
    	$page->setConfig('theme','%UP_PAGE% %LINK_PAGE% %DOWN_PAGE%');
        $list = $city->where($where)->limit($page->firstRow, $page->listRows)->order('`id` ASC')->select();
        foreach ($list as $k => $v){
            $where = array();
            $where['id'] = array(array('gt',$v['id'] * 100),array('lt',$v['id'] * 100 + 100));
            $where['commend'] = array('neq',2);
            $list[$k]['child_count'] = $city->where($where)->count();
        }
        return $this->assign(array(
            'count' => $count,
            'list' => $list,
            'page' => $page->show(),
        ))->display();
    }
    
    /**
     * 添加省级单位列表
     * 
     * @return void
     * @author xiaoyutab<xiaoyutab@qq.com>
     * @version v1.0.0
     * @copyright (c) 2017, xiaoyutab
     * @adtime 2017-08-22 11:09:22
     */
    public function user_city_addAction(){
        $city = new UserCityModel();
        if(I('post.')){
            $post = I('post.');
            $post['code'] = str_pad($post['id'],6,0,STR_PAD_RIGHT);
            if($city->create_user_city($post)){
                return $this->success('添加成功',U('user_city'));
            }
            return $this->error('添加失败');
        }
        $list = $city->where(array('id' => array('lt',100),'commend' => array('neq',2)))->select();
        $ids = array();
        foreach($list as $v){
            $ids[] = $v['id'];
        }
        $this->assign('ids', implode(',', $ids));
        return $this->display();
    }
}
