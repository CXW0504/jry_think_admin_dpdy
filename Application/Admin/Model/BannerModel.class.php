<?php
namespace Admin\Model;
use Admin\Model\LogModel;
/**
 * Banner管理模型
 * 
 * @author xiaoyutab<xiaoyutab@qq.com>
 * @version v1.0.0
 * @copyright (c) 2017, xiaoyutab
 * @adtime 2017-7-18 20:56:15
 */
class BannerModel extends \Common\Model\AllModel{
    /**
     * 获取Banner详情
     * 
     * @param number $id 要获取的用户组编号
     * @return boolean 查询到的信息
     * @author xiaoyutab<xiaoyutab@qq.com>
     * @version v1.0.0
     * @copyright (c) 2017, xiaoyutab
     * @adtime 2017-07-17 14:08:52
     */
    public function get_info($id = 0){
        if ($id <= 0) {
            return FALSE;
        }
        $info = $this->where(array('id' => $id))->find();
        return $info;
    }
    
    /**
     * 添加Banner情况
     * 
     * @param array $post 传入的POST数据列表
     * @param number $file 上传的文件编号
     * @return boolean 是否添加成功
     * @author xiaoyutab<xiaoyutab@qq.com>
     * @version v1.0.0
     * @copyright (c) 2017, xiaoyutab
     * @adtime 2017-7-19 15:17:44
     */
    public function create_info($post = array(),$file = 0){
        if($file > 0){
            $post['file_id'] = $file;
        }
        if(!isset($post['title'])){
            return FALSE;
        }
        $post['ad_time'] = NOW_TIME;
        $ids = $this->add($post);
        if($ids){
            $log = new LogModel();
            $log->create_log('banner', $ids);
        }
        return $ids;
    }
    
    /**
     * 删除banner图片
     * 
     * @param number $id 图片编号
     * @return boolean 是否删除成功
     * @author xiaoyutab<xiaoyutab@qq.com>
     * @version v1.0.0
     * @copyright (c) 2017, xiaoyutab
     * @adtime 2017-7-19 15:43:15
     */
    public function delete_banner($id = 0){
        if($id <= 0){
            return FALSE;
        }
        if($this->where(array('id'=>$id,'status'=>array('neq',98)))->save(array('status'=>98,'del_time'=>NOW_TIME))){
            $log = new LogModel();
            $log->delete_log('banner', $id);
            return TRUE;
        }
        return FALSE;
    }
    
    /**
     * 修改Banner信息
     * 
     * @param number $id banner编号
     * @param array $post 提交过来的数组数据
     * @param number $fid 上传的图片编号，如果未上传则未0或者false
     * @return boolean 是否修改成功
     * @author xiaoyutab<xiaoyutab@qq.com>
     * @version v1.0.0
     * @copyright (c) 2017, xiaoyutab
     * @adtime 2017-7-19 16:15:01
     */
    public function update_info($id = 0,$post = array(),$fid = 0){
        if($fid){
            $post['file_id'] = $fid;
        }
        $info = $this->get_info($id);
        $old_arr = array();
        $new_arr = array();
        foreach($post as $k => $v){
            if($info[$k] != $v){
                $old_arr[$k] = $info[$k];
                $new_arr[$k] = $v;
            }
        }
        if($this->where(array('id'=>$id,'status'=>array('neq',98)))->save($new_arr)){
            $log = new LogModel();
            $log->update_log('banner', $id, $new_arr, $old_arr);
            return TRUE;
        }
        return FALSE;
    }
}