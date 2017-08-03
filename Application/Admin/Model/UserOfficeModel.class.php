<?php

namespace Admin\Model;

use Admin\Model\LogModel;

/**
 * 用户工作职位操作模型
 * 
 * @author xiaoyutab<xiaoyutab@qq.com>
 * @version v1.0.0
 * @copyright (c) 2017, xiaoyutab
 * @adtime 2017-08-03 14:08:22
 */
class UserOfficeModel extends \Common\Model\AllModel {

    private static $temp;

    /**
     * 获取用户职位信息列表
     * 
     * @return array 查询到的数组
     * @author xiaoyutab<xiaoyutab@qq.com>
     * @version v1.0.0
     * @copyright (c) 2017, xiaoyutab
     * @adtime 2017-08-03 12:45:29
     */
    public function get_user_office_list($fid = 0) {
        $list = $this->_get_user_office_list(0, '&nbsp;&nbsp;&nbsp;&nbsp;');
        return $list;
    }

    /**
     * 递归获取用户职位
     * 
     * @param type $id 开始搜索的编号，默认0顶级
     * @param type $temps 缩进字段，默认双空格
     * @param type $le 当前循环等级，保留字段【循环次数最多为6次】
     * @param type $temp 保留字段
     * @return array 查询到的数组
     * @author xiaoyutab<xiaoyutab@qq.com>
     * @version v1.0.0
     * @copyright (c) 2017, xiaoyutab
     * @adtime 2017-08-03 12:45:35
     */
    private function _get_user_office_list($id = 0, $temps = '  ', $le = 0, $temp = '') {
        if ($le > 6) {
            return self::$temp;
        }
        $info = $this->where(array('fid' => $id))->getList(0, 100);
        foreach ($info as $v) {
            self::$temp[$v['id']] = $temp . $v['office_name'];
            self::_get_user_office_list($v['id'], $temps, $le + 1, $temp . $temps);
        }
        return self::$temp;
    }

    /**
     * 插入用户职位信息
     * @param number $fid 父级职位编号
     * @param string $name 当前职位名称
     * @return boolean 是否插入成功
     * @author xiaoyutab<xiaoyutab@qq.com>
     * @version v1.0.0
     * @copyright (c) 2017, xiaoyutab
     * @adtime 2017-08-03 12:48:39
     */
    public function create_user_office_data($fid = 0, $name = '') {
        if (empty($name)) {
            return FALSE;
        }
        $success = $this->add(array(
            'office_name' => $name,
            'ad_time' => NOW_TIME,
            'status' => 99,
            'del_time' => 0,
            'fid' => $fid
        ));
        if ($success) {
            $log = new LogModel();
            $log->create_log('user_office', $success);
            return TRUE;
        }
        return FALSE;
    }

    /**
     * 删除用户职位信息
     * 
     * @param number $id 要删除的条目编号
     * @return boolean 是否删除成功
     * @author xiaoyutab<xiaoyutab@qq.com>
     * @version v1.0.0
     * @copyright (c) 2017, xiaoyutab
     * @adtime 2017-08-03 12:56:28
     */
    public function delete_user_office($id = 0) {
        if ($id <= 0) {
            return FALSE;
        }
        $success = $this->setDelete($id);
        if ($success) {
            $log = new LogModel();
            $log->delete_log('user_office', $id);
            return TRUE;
        }
        return FALSE;
    }

    /**
     * 修改用户职位信息
     * 
     * @param number $id 要修改的条目编号
     * @param number $fid 父级职位编号
     * @param string $name 当前职位名称
     * @return boolean 是否插入成功
     * @author xiaoyutab<xiaoyutab@qq.com>
     * @version v1.0.0
     * @copyright (c) 2017, xiaoyutab
     * @adtime 2017-08-03 13:34:21
     */
    public function save_user_office($id = 0, $fid = 0, $name = '') {
        if (empty($name)) {
            return FALSE;
        }
        if ($id <= 0) {
            return FALSE;
        }
        $info = $this->where(array('id' => $id, 'status' => array('neq', 98)))->find();
        $success = $this->where(array('id' => $id, 'status' => array('neq', 98)))->save(array(
            'office_name' => $name,
            'fid' => $fid
        ));
        $new_arr = array();
        $temp_arr = array();
        if ($info['fid'] != $fid) {
            $new_arr['fid'] = $fid;
            $temp_arr['fid'] = $info['fid'];
        }
        if ($info['office_name'] != $name) {
            $new_arr['office_name'] = $name;
            $temp_arr['office_name'] = $info['office_name'];
        }
        if ($success) {
            $log = new LogModel();
            $log->update_log('user_office', $id, $new_arr, $temp_arr);
            return TRUE;
        }
        return FALSE;
    }

}
