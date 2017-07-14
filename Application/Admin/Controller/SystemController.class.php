<?php
namespace Admin\Controller;
use Admin\Model\DirectoriesDepartmentModel;
use Admin\Model\DirectoriesUserModel;
use Think\Page;

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

}
