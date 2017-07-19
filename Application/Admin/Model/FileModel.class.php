<?php
namespace Admin\Model;
use Admin\Model\LogModel;
use Think\Upload;
use Think\Image;
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
    
    /**
     * 上传图片
     * 
     * @param string $name 表单中的上传文件选项的标识名
     * @return boolean 上传完保存后的文件编号
     * @author xiaoyutab<xiaoyutab@qq.com>
     * @version v1.0.0
     * @copyright (c) 2017, xiaoyutab
     * @adtime 2017-7-18 22:19:53
     */
    public function upload_file($name = 'file'){
        if (empty($_FILES[$name])) {
            return false;
        }
        $upload = new Upload(array(
            'rootPath' => C('IMAGE_SAVE_PATH', null, './Public/Upload/'), // 设置上传的ROOT目录所在，默认在Public下的Upload目录
        ));
        $upload->maxSize = intval(C('IMG_maxSize', null, 2) * 1024 * 1024); //设置上传附件大小
        $upload->exts = C('IMG_exts', null, array('jpg', 'gif', 'jpeg', 'png')); //设置上传附件类型
        $upload->savePath =  $name.'/';
        //上传时指定一个要上传的图片的名称,否则会把表中所有的图片都处理,
        $info = $upload->upload(array($name => $_FILES[$name]));
        if (empty($info)) {
            return FALSE;
        }
        $info = $info['file'];
        // 检测文件是否存在，如果存在就直接返回标识信息
        if($id = $this->check_file($info['md5'],$info['sha1'],$info['size'],$info['savepath'].$info['savename'])){
            return $id;
        }
        $image = new Image();
        $date = array(array(100, 100), array(200, 200));
        $file = $info['savepath'].$info['savename'];
        unset($info['savepath']);
        unset($info['savename']);
        unset($info['name']);
        $info['file_path'] = $file;
        foreach ($date as $k => $v) {
            $file_path = C('IMAGE_SAVE_PATH', null, './Public/Upload/') . dirname($file) . '/thumb_' . $v[0] . 'x' . $v[1] . '_' . basename($file);
            //打开要处理的图片
            $image->open(C('IMAGE_SAVE_PATH', null, './Public/Upload/') . $file);
            $image->thumb($v[0], $v[1], Image::IMAGE_THUMB_FIXED)->save($file_path);
            $info['thumb_'.$v[0]] = dirname($file) . '/thumb_' . $v[0] . 'x' . $v[1] . '_' . basename($file);
        }
        return $this->create_file_data($info['ext'],$info['key'],$info['md5'],$info['sha1'],$info['size'],$info['type'],$info['file_path'],$info['thumb_200'],$info['thumb_100']);
    }
    
    /**
     * 检测文件是否存在，如果存在就直接返回编号并删除新上传的文件
     * 
     * @param string $md5 文件签名
     * @param string $sha1 文件指纹
     * @param number $size 文件大小，单位B
     * @param string $file_name 文件地址
     * @return boolean
     * @author xiaoyutab<xiaoyutab@qq.com>
     * @version v1.0.0
     * @copyright (c) 2017, xiaoyutab
     * @adtime 2017-7-18 22:16:31
     */
    public function check_file($md5 = '',$sha1 = '',$size = '',$file_name = ''){
        $info = $this->where(array('md5'=>$md5,'sha1'=>$sha1,'size'=>$size))->find();
        if(empty($info)){
            return FALSE;
        }
        // 如果传入了新文件名就将新文件删除
        if(!empty($file_name)){
            unlink(C('IMAGE_SAVE_PATH', null, './Public/Upload/') . $file_name);
        }
        return $info['id'];
    }
    
    /**
     * 获取图片文件详情信息
     * @param number $fid 图片编号
     * @return array 查询到的信息
     */
    public function get_info($fid = 0){
        if($fid <= 0){
            return array();
        }
        $info = $this->where(array('id'=>$fid))->find();
        return $info;
    }
}
