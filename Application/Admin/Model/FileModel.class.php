<?php
namespace Admin\Model;
use Admin\Model\LogModel;
/**
 * 文件相关表操作
 * 
 * @author xiaoyutab<xiaoyutab@qq.com>
 * @version v1.0.0
 * @copyright (c) 2017, xiaoyutab
 * @adtime 2017-07-13 20:32:43
 */
class FileModel extends \Common\Model\AllModel {

    /**
     * 创建文件记录操作
     * 
     * @param type $ext
     * @param type $key
     * @param type $md5
     * @param type $sha1
     * @param type $size
     * @param type $type
     * @param type $file_path
     * @param type $thumb_200
     * @param type $thumb_100
     * @return number 是否上传成功
     * @author xiaoyutab<xiaoyutab@qq.com>
     * @version v1.0.0
     * @copyright (c) 2017, xiaoyutab
     * @adtime 2017-07-13 20:33:01
     */
    public function create_file_data($ext = 'jpg',$key = 'image',$md5 = '',$sha1 = '',$size = '',$type = 'image/jpeg',$file_path = '',$thumb_200 = '',$thumb_100 = ''){
        // 如果没有保存路径则返回false
        if(empty($file_path)){
            return FALSE;
        }
        // 进行添加操作
        $id = $this->add(array(
            'ext' => trim($ext),
            'key' => trim($key),
            'md5' => trim($md5),
            'sha1' => trim($sha1),
            'size' => intval($size),
            'type' => trim($type),
            'file_path' => trim($file_path),
            'thumb_100' => trim($thumb_100),
            'thumb_200' => trim($thumb_200),
            'ad_time' => NOW_TIME,
            'status' => 99,
        ));
        if($id){
            $log = new LogModel();
            $log->create_log('file', $id);
        }
        return $id;
    }
}
