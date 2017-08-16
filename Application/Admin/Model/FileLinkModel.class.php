<?php

namespace Admin\Model;

use Admin\Model\LogModel;
use Admin\Model\FileModel;
use Admin\Model\UserModel;

/**
 * 文件链接相关表操作
 * 
 * @author xiaoyutab<xiaoyutab@qq.com>
 * @version v1.0.0
 * @copyright (c) 2017, xiaoyutab
 * @adtime 2017-08-16 19:40:39
 */
class FileLinkModel extends \Common\Model\AllModel {

    /**
     * 添加图片链接
     * 
     * @param intval $fid 要链接的图片编号
     * @param intval $pid 要链接的目标表编号
     * @param string $tab 要链接的目标表名
     * @param boolean $old 是否要删除之前存在的链接文件[根据链接目标表名和目标表编号进行删除]
     * @return boolean 是否添加成功
     * @author xiaoyutab<xiaoyutab@qq.com>
     * @version v1.0.0
     * @copyright (c) 2017, xiaoyutab
     * @adtime 2017-08-16 19:53:42
     */
    public function create_link($fid = 0, $pid = 0, $tab = '', $old = false) {
        if($fid <= 0){
            return FALSE;
        }
        if($pid <= 0){
            return FALSE;
        }
        if(empty($tab)){
            return FALSE;
        }
        $log = new LogModel();
        if ($old) {
            $info = $this->where(array('target_id' => $pid, 'target_name' => $tab))->select();
            foreach ($info as $v) {
                $success = $this->where(array('id' => $v['id']))->save(array('status' => 98, 'del_time' => NOW_TIME));
                if ($success) {
                    $log->delete_log('file_link', $v['id'], $v['status']);
                }
            }
        }
        // 获取用户id
        $user = new UserModel();
        $info = $user->get_user_info();
        $uid = $info['id'];
        $success = $this->add(array(
            'file_id' => $fid,
            'target_id' => $pid,
            'target_name' => $tab,
            'ad_time' => NOW_TIME,
            'status' => 99,
            'party_id' => $uid,
            'party_name' => 'user'
        ));
        if ($success) {
            $log->create_log('file_link', $success);
            return TRUE;
        }
        return FALSE;
    }
    
    /**
     * 查询图片信息
     * 
     * @param intval $pid 要链接的目标表编号
     * @param string $tab 要链接的目标表名
     * @param boolean $find 是否是查询单条数据，false为查询全部
     * @return array 查询到的数据，其中详情中的file_info为具体的图片信息
     * @author xiaoyutab<xiaoyutab@qq.com>
     * @version v1.0.0
     * @copyright (c) 2017, xiaoyutab
     * @adtime 2017-08-16 20:01:56
     */
    public function get_file_info($pid = 0, $tab = '',$find = FALSE){
        $info = $this->where(array('target_id'=>$pid,'target_name'=>$tab,'status' => array('neq',98)))->select();
        $file = new FileModel();
        $return_data = array();
        foreach($info as $v){
            $v['file_info'] = $file->get_info($v['file_id']);
            $return_data[] = $v;
        }
        if($file){
            return $return_data[0];
        }
        return $return_data;
    }

}
