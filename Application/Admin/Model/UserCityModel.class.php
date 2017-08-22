<?php
namespace Admin\Model;
use Admin\Model\LogModel;
/**
 * 用户城市管理模型
 *      管理所有的用户可选择城市列表
 * 
 * @author xiaoyutab<xiaoyutab@qq.com>
 * @version v1.0.0
 * @copyright (c) 2017, xiaoyutab
 * @adtime 2017-08-22 15:48:02
 */
class UserCityModel extends \Common\Model\AllModel{
    /**
     * 插入一条数据
     * 
     * @param array $data 插入的数据
     * @return boolean 是否添加成功
     * @author xiaoyutab<xiaoyutab@qq.com>
     * @version v1.0.0
     * @copyright (c) 2017, xiaoyutab
     * @adtime 2017-08-22 15:50:18
     */
    public function create_user_city($data = array()){
        if(!isset($data['id'])){
            return FALSE;
        }
        if($this->add($data)){
            $log = new LogModel();
            $log->create_log('user_city', $data['id']);
            return TRUE;
        }
        return FALSE;
    }
}