<?php
namespace Admin\Model;
use Admin\Model\LogModel;
/**
 * 前台用户组列表
 * 
 * @author xiaoyutab<xiaoyutab@qq.com>
 * @version v1.0.0
 * @copyright (c) 2017, xiaoyutab
 * @adtime 2017-07-17 14:08:42
 */
class UserReceptionGroupModel extends \Common\Model\AllModel{
    private $leaver = 3;
    private static $temp;
    
    /**
     * 获取用户组详情
     * 
     * @param number $id 要获取的用户组编号
     * @return boolean 查询到的信息
     * @author xiaoyutab<xiaoyutab@qq.com>
     * @version v1.0.0
     * @copyright (c) 2017, xiaoyutab
     * @adtime 2017-07-17 14:08:52
     */
    public function get_info($id = 0){
        if ($id <= 0) {
            return FALSE;
        }
        $info = $this->where(array('id' => $id))->find();
        return $info;
    }
    
    /**
     * 添加前台用户组
     * 
     * @param intval $fid 父级分组编号
     * @param string $name 用户组名称
     * @return boolean 是否添加成功
     * @author xiaoyutab<xiaoyutab@qq.com>
     * @version v1.0.0
     * @copyright (c) 2017, xiaoyutab
     * @adtime 2017-07-17 14:09:03
     */
    public function create_info($fid = 0,$name = ''){
        if(empty($name)){
            return FALSE;
        }
        $id = $this->add(array(
            'fid' => intval($fid),
            'name' => $name,
            'status' => 99,
            'ad_time' => NOW_TIME,
        ));
        if($id){
            $log = new LogModel();
            $log->create_log('user_reception_group', $id);
        }
        return $id;
    }
    
    /**
     * 更新前台用户组
     * 
     * @param number $id 要更新的用户组名称
     * @param string $name 修改成什么样的值
     * @return boolean
     * @author xiaoyutab<xiaoyutab@qq.com>
     * @version v1.0.0
     * @copyright (c) 2017, xiaoyutab
     * @adtime 2017-07-17 14:09:19
     */
    public function save_info($id = 0,$fid = 0,$name = ''){
        if ($id <= 0) {
            return FALSE;
        }
        $info = $this->get_info($id);
        $save_arr = array();
        $old_arr = array();
        if($name != $info['name']){
            $save_arr['name'] = $name;
            $old_arr['name'] = $info['name'];
        }
        if($fid != $info['fid']){
            $save_arr['fid'] = $fid;
            $old_arr['fid'] = $info['fid'];
        }
        $success = $this->where(array('status'=>array('neq',98),'id'=>$info['id']))->save($save_arr);
        if($success){
            $log = new LogModel();
            $log->update_log('user_reception_group', $info['id'], $save_arr, $old_arr);
        }
        return $success;
    }

    /**
     * 删除前台用户组
     * 
     * @param number $id 要删除的用户组编号
     * @return boolean
     * @author xiaoyutab<xiaoyutab@qq.com>
     * @version v1.0.0
     * @copyright (c) 2017, xiaoyutab
     * @adtime 2017-07-14 18:39:34
     */
    public function delete_info($id = 0) {
        if ($id <= 0) {
            return FALSE;
        }
        $info = $this->get_info($id);
        $success = $this->where(array('id' => $id, 'status' => array('neq', 98)))->save(array(
            'status' => 98,
            'del_time' => NOW_TIME,
        ));
        if ($success) {
            $log = new LogModel();
            $log->delete_log('user_reception_group', $id, $info['status']);
        }
        return $success;
    }
    
    /**
     * 获取父级部门/分组名称
     * @param intval $id 要获取的编号
     * @return string 获取到的值
     * @author xiaoyutab<xiaoyutab@qq.com>
     * @version v1.0.0
     * @copyright (c) 2017, xiaoyutab
     * @adtime 2017-7-17 14:24:13
     */
    public function get_reception_group_name($id = 0){
        // 根据编号获取名称。如果为空返回'--'
        $info = $this->get_info($id);
        if(empty($info['name'])){
            return '--';
        }
        return $info['name'];
    }
    
    /**
     * 获取旗下部门数量
     * @param intval $id 部门编号
     * @return int 获取到的数量
     * @author xiaoyutab<xiaoyutab@qq.com>
     * @version v1.0.0
     * @copyright (c) 2017, xiaoyutab
     * @adtime 2017-7-17 14:38:59
     */
    public function get_reception_group_count($id = 0){
        if($id <= 0){
            return 0;
        }
        $count = $this->where(array('fid'=> intval($id)))->getCount();
        return intval($count);
    }
    
    /**
     * 获取分组树形列表
     * @return array 查询到的数据
     * @author xiaoyutab<xiaoyutab@qq.com>
     * @version v1.0.0
     * @copyright (c) 2017, xiaoyutab
     * @adtime 2017-7-17 16:00:06
     */
    public function get_tree_group_list(){
        $list = $this->_get_tree_group_list(0, '&nbsp;&nbsp;&nbsp;&nbsp;');
        return $list;
    }

    /**
     * 递归获取部门列表
     * 
     * @param type $id 开始搜索的编号，默认0顶级
     * @param type $temps 缩进字段，默认双空格
     * @param type $le 当前循环等级，保留字段【循环次数最多为6次】
     * @param type $temp 保留字段
     * @return array 查询到的数组
     * @author xiaoyutab<xiaoyutab@qq.com>
     * @version v1.0.0
     * @copyright (c) 2017, xiaoyutab
     * @adtime 2017-07-12 17:45:02
     */
    private function _get_tree_group_list($id = 0, $temps = '  ', $le = 0, $temp = '') {
        if ($le > 6) {
            return self::$temp;
        }
        $info = $this->where(array('fid' => $id))->getList(0, 100);
        foreach ($info as $v) {
            self::$temp[$v['id']] = $temp . $v['name'];
            self::_get_tree_group_list($v['id'], $temps, $le + 1, $temp . $temps);
        }
        return self::$temp;
    }
}