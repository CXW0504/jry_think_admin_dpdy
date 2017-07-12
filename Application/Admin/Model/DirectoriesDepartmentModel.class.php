<?php
namespace Admin\Model;
use Admin\Model\LogModel;

/**
 * 部门操作类，作用为获取部门相关信息
 * 
 * @author xiaoyutab<xiaoyutab@qq.com>
 * @version v1.0.0
 * @copyright (c) 2017, xiaoyutab
 * @adtime 2017-07-12 17:46:00
 */
class DirectoriesDepartmentModel extends \Common\Model\AllModel {

    private $leaver = 3;
    private static $temp;

    /**
     * 获取部门列表信息列表
     * 
     * @return array 查询到的数组
     * @author xiaoyutab<xiaoyutab@qq.com>
     * @version v1.0.0
     * @copyright (c) 2017, xiaoyutab
     * @adtime 2017-07-12 17:45:16
     */
    public function get_directories_department_list() {
        $list = $this->_get_directories_department_list(0, '&nbsp;&nbsp;&nbsp;&nbsp;');
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
    private function _get_directories_department_list($id = 0, $temps = '  ', $le = 0, $temp = '') {
        if ($le > 6) {
            return self::$temp;
        }
        $info = $this->where(array('fid' => $id))->getList(0, 100);
        foreach ($info as $v) {
            self::$temp[$v['id']] = $temp . $v['name'];
            self::_get_directories_department_list($v['id'], $temps, $le + 1, $temp . $temps);
        }
        return self::$temp;
    }
    
    /**
     * 添加部门信息
     * 
     * @param number $fid 父级编号
     * @param string $name 部门名称
     * @param string $remarks 部门备注
     * @return boolean
     * @author xiaoyutab<xiaoyutab@qq.com>
     * @version v1.0.0
     * @copyright (c) 2017, xiaoyutab
     * @adtime 2017-07-12 18:20:44
     */
    public function create_directories_department($fid = 0,$name = '',$remarks = ''){
        if(empty($name) || $fid < 0){
            return FALSE;
        }
        // 添加数据操作
        $id = $this->add(array(
            'fid' => intval($fid),
            'name' => trim($name),
            'remarks' => trim($remarks),
            'ad_time' => NOW_TIME,
            'status' => 99,
        ));
        // 添加操作日志
        $log = new LogModel();
        $log->create_log('directories_department', $id);
        return $id;
    }
    
    
    /**
     * 根据部门编号获取部门名称
     * 
     * @param number $fid 父级编号
     * @return string
     * @author xiaoyutab<xiaoyutab@qq.com>
     * @version v1.0.0
     * @copyright (c) 2017, xiaoyutab
     * @adtime 2017-07-12 18:31:46
     */
    public function get_directories_name($fid = 0){
        if($fid <= 0){
            return '--';
        }
        $info = $this->where(array('id'=> intval($fid)))->find();
        if(isset($info['name'])){
            return $info['name'];
        }
        return '未知';
    }
    
    /**
     * 获取部门下的部门数量
     * @param number $id 部门编号
     * @return number
     * @author xiaoyutab<xiaoyutab@qq.com>
     * @version v1.0.0
     * @copyright (c) 2017, xiaoyutab
     * @adtime 2017-07-12 18:40:34
     */
    public function get_directories_count_directories($id = 0){
        $count = $this->where(array('fid' => intval($id),'status' => array('neq',98)))->count();
        return $count;
    }
    
    /**
     * 获取部门详情信息
     * 
     * @param number $id 部门编号
     * @return number
     * @author xiaoyutab<xiaoyutab@qq.com>
     * @version v1.0.0
     * @copyright (c) 2017, xiaoyutab
     * @adtime 2017-07-12 21:03:36
     */
    public function get_directories_info($id = 0){
        if($id <= 0){
            return array();
        }
        $info = $this->where(array('id'=> intval($id),'status'=>array('neq',98)))->find();
        if(empty($info)){
            return array();
        }
        unset($info['status']);
        unset($info['del_time']);
        return $info;
    }
    
    /**
     * 修改部门信息
     * 
     * @param number $id 部门编号
     * @param string $fid 父部门编号
     * @param string $name 部门名称
     * @param string $remarks 部门备注
     * @return number
     * @author xiaoyutab<xiaoyutab@qq.com>
     * @version v1.0.0
     * @copyright (c) 2017, xiaoyutab
     * @adtime 2017-07-12 21:12:45
     */
    public function save_directories_department($id = 0,$fid = 0,$name = '',$remarks = ''){
        $info = $this->get_directories_info($id);
        if(empty($info)){
            return FALSE;
        }
        $save_arr = array();
        $old_arr = array();
        if($name != $info['name']){
            $save_arr['name'] = $name;
            $old_arr['name'] = $info['name'];
        }
        if($name != $info['fid']){
            $save_arr['fid'] = $fid;
            $old_arr['fid'] = $info['fid'];
        }
        if($name != $info['remarks']){
            $save_arr['remarks'] = $remarks;
            $old_arr['remarks'] = $info['remarks'];
        }
        $status = $this->where(array('id'=> intval($id),'status'=>array('neq',98)))->save($save_arr);
        if($status){
            $log = new LogModel();
            $log->update_log('directories_department',$id,$save_arr,$old_arr);
        }
        return TRUE;
    }

}
