<?php

namespace Home\Model;

use Home\Model\FileModel;

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
