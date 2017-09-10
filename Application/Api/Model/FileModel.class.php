<?php
namespace Api\Model;
use Think\Model;

/**
 * 图片模型
 * 
 * @author xiaoyutab<xiaoyutab@qq.com>
 * @version v1.0.0
 * @copyright (c) 2017, xiaoyutab
 * @adtime 2017-09-10 23:05:35
 */
class FileModel extends Model{
    /**
     * 获取文件详情操作
     * 
     * @param intval $id 文件编号
     * @return array 获取到的详细信息
     *      id (主键)    文件编号
     *      ext		文件后缀名称
     *      key		文件类型关键字，如果是图片则为image
     *      md5		文件的md5值，防止重复文件上传
     *      sha1	文件的sha1值，防止文件重复上传
     *      size	文件大小，单位：B
     *      type	文件类型，图片为：image/jpeg
     *      file_path	文件在服务器上的存储路径，相对路径。前面需要追加网站上传文件根目录
     *      thumb_100	100x100像素的缩略图位置
     *      thumb_200	200x200像素的缩略图位置
     * @author xiaoyutab<xiaoyutab@qq.com>
     * @version v1.0.0
     * @copyright (c) 2017, xiaoyutab
     * @adtime 2017-09-10 23:20:32
     */
    public function get_file_info($id = 1){
        $fid = intval($id);
        if($fid <= 0){
            return array();
        }
        $info = $this
                ->cache(TRUE)
                ->where(array('id'=>$fid,'status'=>array('neq',98)))
                ->order('`id` DESC')
                ->find();
        if(empty($info)){
            return array();
        }
        unset($info['ad_time']);
        unset($info['status']);
        unset($info['del_time']);
        return $info;
    }
}