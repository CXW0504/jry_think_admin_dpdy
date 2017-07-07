<?php
namespace Admin\Model;
use Admin\Model\UserLogModel;

class UserGroupModel extends \Common\Model\AllModel {

    /**
     * 添加分组名称
     * 
     * @param string $name 要添加的分组名称
     * @return boolean 是否添加成功
     * @author xiaoyutab<xiaoyutab@qq.com>
     * @version v1.0.2
     * @copyright (c) 2017, xiaoyutab
     * @adtime 2017-07-06 18:04:41
     */
    public function create_group($name = '') {
        if (!empty($name)) {
            return $this->add(array(
                        'name' => $name,
                        'content' => '',
                        'ad_time' => NOW_TIME,
                        'status' => 99
            ));
        }
        return false;
    }
    
    /**
     * 更新分组名称
     * 
     * @param number $id 要操作的条目编号
     * @param string $new 要更新成的内容
     * @param string $old 旧的内容
     * @return boolean 是否更新成功
     * @author xiaoyutab<xiaoyutab@qq.com>
     * @version v1.0.2
     * @copyright (c) 2017, xiaoyutab
     * @adtime 2017-07-06 18:51:35
     */
    public function save_group($id = 1,$new = '',$old = ''){
        $data = $this->where(array('id'=>$id,'status'=>array('neq',98)))->save(array('name'=>$new));
        if($data){
            $log = new UserLogModel();
            // 添加更新操作日志
            $log->update_log('user_group',$id, $old, $new);
            return TRUE;
        }
        return FALSE;
    }
    
    /**
     * 删除权限组
     * 
     * @param number $id 要删除的权限组编号
     * @param number $old_s 要删除的权限组现在状态
     * @return boolean 是否删除成功
     * @author xiaoyutab<xiaoyutab@qq.com>
     * @version v1.0.2
     * @copyright (c) 2017, xiaoyutab
     * @adtime 2017-07-07 11:33:00
     */
    public function delete_group($id = 1,$old_s = 99){
        $data = $this->where(array('id'=>$id,'status'=>array('neq',98)))->save(array(
            'status' => 98,
            'del_time' => NOW_TIME
        ));
        if($data){
            $log = new UserLogModel();
            $log->delete_log('user_group',$id,$old_s,98);// 添加删除日志
            return TRUE;
        }
        return FALSE;
    }
    
    /**
     * 为权限组重新授权
     * 
     * @param number $id 要删除的权限组编号
     * @param string $old 权限组现在的状态
     * @param string $new 修改成什么样的权限
     * @return boolean 是否删除成功
     * @author xiaoyutab<xiaoyutab@qq.com>
     * @version v1.0.2
     * @copyright (c) 2017, xiaoyutab
     * @adtime 2017-07-07 11:44:30
     */
    public function save_group_jurisdiction($id = 1,$old = '',$new = ''){
        $data = $this->where(array('id'=>$id,'status'=>array('neq',98)))->save(array(
            'content' => implode(',', $new)
        ));
        if($data){
            $log = new UserLogModel();
            $log->delete_log('user_group',$id,$old,implode(',', $new));// 添加删除日志
            return TRUE;
        }
        return FALSE;
    }
}
