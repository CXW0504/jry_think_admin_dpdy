<?php
namespace Admin\Model;
use Admin\Model\LogModel;
/**
 * 公告管理模型
 * 
 * @author xiaoyutab<xiaoyutab@qq.com>
 * @version v1.0.0
 * @copyright (c) 2017, xiaoyutab
 * @adtime 2017-08-31 14:26:38
 */
class NoticeModel extends \Common\Model\AllModel{
	/**
	 * 添加公告信息
	 * 
	 * @param array $post POST提交过来的值
	 * @return boolean 是否添加成功
	 * @author xiaoyutab<xiaoyutab@qq.com>
	 * @version v1.0.0
	 * @copyright (c) 2017, xiaoyutab
	 * @adtime 2017-08-31 14:27:25
	 */
	public function create_info($post = array()){
		if(empty($post)){
			return false;
		}
		if(empty($post['title'])){
			return false;
		}
		$post['status'] = 99;
		$post['ad_time'] = NOW_TIME;
		$success = $this->add($post);
		if($success){
			$log = new LogModel();
			$log->create_log('notice',$success);
			return true;
		}
		return false;
	}
	
	/**
	 * 修改公告信息
	 * 
	 * @param number $id 要修改的条目编号
	 * @param array $post POST提交过来的值
	 * @return boolean 是否添加成功
	 * @author xiaoyutab<xiaoyutab@qq.com>
	 * @version v1.0.0
	 * @copyright (c) 2017, xiaoyutab
	 * @adtime 2017-08-31 14:27:25
	 */
	public function update_info($id = 1,$post = array()){
		if(empty($post)){
			return false;
		}
		if(empty($post['title'])){
			return false;
		}
		$info = $this->where(array('id'=>$id,'status'=>array('neq',98)))->find();
		$success = $this->where(array('id'=>$id,'status'=>array('neq',98)))->save($post);
		if($success){
			$log = new LogModel();
			$log->update_log('notice',$id,$post,$info);
			return true;
		}
		return false;
	}
	
	/**
	 * 删除公告信息
	 * 
	 * @param number $id 要删除的条目编号
	 * @return boolean 是否删除成功
	 * @author xiaoyutab<xiaoyutab@qq.com>
	 * @version v1.0.0
	 * @copyright (c) 2017, xiaoyutab
	 * @adtime 2017-08-31 14:27:25
	 */
	public function delete_info($id = 1){
		if($id <= 0){
			return false;
		}
		$success = $this->setDelete($id);
		if($success){
			$log = new LogModel();
			$log->delete_log('notice',$id);
			return true;
		}
		return false;
	}
}