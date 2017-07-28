<?php
namespace Api\Model;
use Think\Model;

/**
 * App应用信息查询类，作用为查询应用的一些信息
 * 
 * @author xiaoyutab<xiaoyutab@qq.com>
 * @adtime 2017-3-6 15:32:35
 * @version v1.0.0
 * @copyright (c) 2017, xiaoyutab
 */
class AppModel extends Model{
    /**
     * 构造函数类
     * 作用为设置当前操作表名
     * 当前操作表：app，与类名相同，暂不设置
     * 
     * @author xiaoyutab<xiaoyutab@qq.com>
     * @version v1.0.0
     * @copyright (c) 2017, xiaoyutab
     * @adtime 2017-3-6 15:36:01
     */
    public function __construct() {
        parent::__construct('authorize_app');
    }
    
    /**
     * 查询应用的唯一识别码
     * 
     * @param intval          $id  要查询的应用的编号
     * @return boolean|string      查询失败|查询的结果
     * @version v1.0.0
     * @author xiaoyutab<xiaoyutab@qq.com>
     * @copyright (c) 2017, xiaoyutab
     * @adtime 2017-3-6 15:40:41
     */
    public function getOther($id = 1){
        if($id < 1){
            return FALSE;
        }
        $data = $this->field(array('other'))->where(array('id'=>$id,'status'=>99))->find();
        if(empty($data['other'])){
            return FALSE;
        }
        return $data['other'];
    }
}