<?php

namespace Api\Model;
use Org\User\IpCity;
use Org\User\System;

class LogInfoModel extends \Common\Model\AllModel{
    
    /**
     * 根据条目值获取条目编号
     * 
     * @param string $value 要获取编号的值
     * @param intval $type 上级分类的条目编号
     * @return intval 获取到的编号
     * @author xiaoyutab<xiaoyutab@qq.com>
     * @version v1.1.0
     * @copyright (c) 2017, xiaoyutab
     * @adtime 2017-09-05 00:11:35
     */
    public function get_id($value = '',$type = 7){
        $id = $this->where(array('value'=>$value))->cache(TRUE)->find();
        if(empty($id)){
            return $this->add(array(
                'name' => $value,
                'value' => $value,
                'type' => $type,
                'ad_time' => NOW_TIME,
                'status' => 99,
                'del_time' => 0
            ));
        }
        return $id['id'];
    }
}