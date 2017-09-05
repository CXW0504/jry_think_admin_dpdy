<?php
namespace Api\Model;
use Common\Model\AllModel;

/**
 * 前端用户操作模型,用来控制用户登录与退出
 * 
 * @author xiaoyutab<xiaoyutab@qq.com>
 * @version v1.0.0
 * @copyright (c) 2017, xiaoyutab
 * @adtime 2017-09-04 22:15:15
 */
class UserReceptionModel extends AllModel{
    /**
     * 用户登录操作
     * 
     * @param string $username 登录用户名
     * @param string $password 登录用户密码
     * @return array|boolean 登录后的信息|查无此用户
     * @author xiaoyutab<xiaoyutab@qq.com>
     * @version v1.0.0
     * @copyright (c) 2017, xiaoyutab
     * @adtime 2017年09月04日22:26:55
     */
    public function login($username = '',$password = ''){
        if(empty($username)){
            return FALSE;// 用户名不能为空
        }
        if(empty($password)){
            return FALSE;// 密码不能为空
        }
        $password = trim($password);// 密码去空格,因为MD5后的密码不可能存在空格
        if(strlen($password) != 32){
            return FALSE;// 密码经过MD5处理后不可能不是32位长度
        }
        $data = $this->where(array(
            'username|phone|email' => trim($username),// 用户名相同
            'status' => array('neq',98) // 非删除用户
        ))->cache(true)->select();
        // 如果没有该用户就返回false
        if(empty($data)){
            return FALSE;
        }
        foreach ($data as $v){
            if($v['password'] == pass($password,$v['rand_code'])){
                return $v;// 如果密码输入正确就直接返回用户的信息
            }
        }
        return FALSE;// 用户密码输入错误
    }
}