<?php

namespace Admin\Model;
use Org\User\IpCity;
use Org\User\System;
use Admin\Model\UserModel;

class LogInfoModel extends \Common\Model\AllModel{
    /**
     * 获取制定ID的指定字段值
     * 
     * @param intval $id 要查询的编号
     * @param string $find 要返回的字段
     * @return string 查询到的值
     * @author xiaoyutab<xiaoyutab@qq.com>
     * @version v1.0.0
     * @copyright (c) 2017, xiaoyutab
     * @adtime 2017-08-17 20:00:45
     */
    public function get_info($id = 0,$find = 'name'){
        if($id <= 0){
            return FALSE;
        }
        if(empty($find)){
            return FALSE;
        }
        $info = $this->where(array('id'=>$id))->cache(TRUE)->find();
        return $info[$find];
    }
    
    /**
     * 根据数组like搜索条件获取符合的ID编号
     * 
     * @param array $finds 要查询的条件
     * @return array 查询到的信息值
     * @author xiaoyutab<xiaoyutab@qq.com>
     * @version v1.0.0
     * @copyright (c) 2017, xiaoyutab
     * @adtime 2017-08-17 20:09:05
     */
    public function get_ids($finds = array()){
        if(!is_array($finds) || empty($finds)){
            return FALSE;
        }
        $selects = $this->where(array('name|value'=>array('like',$finds,'OR')))->cache(TRUE)->select();
        return $selects;
    }
    
    /**
     * 根据条目值获取条目编号
     * 
     * @param string $value 要获取编号的值
     * @param intval $type 上级分类的条目编号
     * @return intval 获取到的编号
     * @author xiaoyutab<xiaoyutab@qq.com>
     * @version v1.1.0
     * @copyright (c) 2017, xiaoyutab
     * @adtime 2017-08-29 22:54:26
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