<?php
namespace Home\Model;
/**
 * 参数类型操作模型
 * 
 * @author xiaoyutab<xiaoyutab@qq.com>
 * @version v1.0.0
 * @copyright (c) 2017, xiaoyutab
 * @adtime 2017-07-26 23:37:52
 */

class ProjectParameterTypeModel extends \Common\Model\AllModel{
    
    /**
     * 获取参数类型的参数
     * 
     * @param number $type 获取类型1请求参数2返回参数
     * @return array 整理好的数组列表
     * @author xiaoyutab<xiaoyutab@qq.com>
     * @version v1.0.0
     * @copyright (c) 2017, xiaoyutab
     * @adtime 2017-07-27 20:10:37
     */
    public function get_type($type = 1){
        $list = $this->where(array('type'=>$type))->getList(0, 100);
        $return = array();
        foreach($list as $v){
            $return[$v['id']] = $v;
        }
        return $return;
    }
}