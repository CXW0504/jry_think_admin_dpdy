<?php

namespace Home\Model;

use Think\Upload;
use Think\Image;

/**
 * 文件相关表操作
 * 
 * @author xiaoyutab<xiaoyutab@qq.com>
 * @version v1.0.0
 * @copyright (c) 2017, xiaoyutab
 * @adtime 2017-08-16 20:07:35
 */
class FileModel extends \Common\Model\AllModel {

    /**
     * 获取图片文件详情信息
     * 
     * @param number $fid 图片编号
     * @return array 查询到的信息
     * @author xiaoyutab<xiaoyutab@qq.com>
     * @version v1.0.0
     * @copyright (c) 2017, xiaoyutab
     * @adtime 2017-08-16 20:07:48
     */
    public function get_info($fid = 0) {
        if ($fid <= 0) {
            return array();
        }
        $info = $this->where(array('id' => $fid))->find();
        return $info;
    }

}
