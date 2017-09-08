<?php
namespace Api\Controller;
use Api\Model\UserReceptionModel;
use Api\Model\UserTokenModel;
use Api\Model\UserReceptionLogModel;
use Admin\Model\FileLinkModel;

/**
 * 用户相关接口列表
 * 
 * @author xiaoyutab<xiaoyutab@qq.com>
 * @version v1.0.0
 * @copyright (c) 2017, xiaoyutab
 * @adtime 2017-09-04 22:09:24
 */
class UserController extends ApiController{
    /**
     * 用户退出操作
     * 
     * @return json
     * @author xiaoyutab<xiaoyutab@qq.com>
     * @version v1.0.0
     * @copyright (c) 2017, xiaoyutab
     * @adtime 2017-09-04 23:59:47
     */
    public function log_outAction(){
        $token = new UserTokenModel();
        $dat = I('post.');
        if($dat['uid'] <= 0){
            return $this->returnCode('F0001');// UID必须大于0
        }
        if(strlen($dat['token']) != 32){
            return $this->returnCode('F0001');// token必须长度32位
        }
        $temp1 = C('LOGIN_TYPE');
        $temp2 = C('LOGIN_TYPE_AUTO_CLEAR');
        if($temp1 && !$temp2){
            return $this->returnCode('U0004');// 禁止退出的状态
        }
        if($token->del_user_token($dat['uid'], $dat['token'])){
            $user_log = new UserReceptionLogModel();
            $user_log->set_log($dat['uid'], 2);// 写退出日志
            return $this->returnCode('R0000');// 成功
        }
        return $this->returnCode('S0002');// 系统错误
    }

    /**
     * 前台用户登录操作
     * 
     * @return json
     * @author xiaoyutab<xiaoyutab@qq.com>
     * @version v1.0.0
     * @copyright (c) 2017, xiaoyutab
     * @adtime 2017-09-04 22:11:53
     */
    public function loginAction(){
        $user = new UserReceptionModel();
        $token = new UserTokenModel();
        $dat = I('post.');
        $username = $dat['username'];// 用户名
        $password = $dat['password'];// 密码,需要用户进行MD5计算以后再传输过来
        $type = intval($dat['type']);// 登录设备类型 1网页登录2Android登录3IOS登录4微信登录5支付宝登录
        $info = $user->login($username, $password);
        if(!$info){
            // 如果密码输入错误或者用户不存在
            return $this->returnCode('U0001');
        }
        if($info['status'] == 97){
            // 用户禁止登录
            return $this->returnCode('U0002');
        }
        // 设置登录标识
        $user_token = $token->get_range_token();
        $info_token = $token->set_user_token($info['id'],$user_token, $type);
        if(!$info_token){
            return $this->returnCode('U0003');
        }
        $user_log = new UserReceptionLogModel();
        $user_log->set_log($info['id'],1);// 设置登录日志
        $file = new FileLinkModel();
        $avatar = $file->get_file_info($info['id'], 'user_reception', TRUE);
        return $this->returnCode(array(
            'uid' => intval($info['id']),
            'token' => $user_token,
            'username' => $info['username'].'',
            'nickname' => $info['nickname'].'',
            'phone' => phont_view_type(3,$info['phone']).'',// 手机号隐藏中间四位
            'email' => $info['email'].'',
            'avatar' => $avatar['thumb_200'],
            'type' => intval($info['type']),// 用户类型,1抵押专员2调评专员3普通用户
            'reg_time' => date('Y-m-d H:i:s',$info['ad_time']),
            'is_login' => boolval($info['status'] != 97),
            'is_del' => boolval($info['status'] == 98),
                'info' => $avatar,
        ));
    }
    
    /**
     * 检测用户是否登录
     * 
     * @return json
     * @author xiaoyutab<xiaoyutab@qq.com>
     * @version v1.0.0
     * @copyright (c) 2017, xiaoyutab
     * @adtime 2017-09-05 17:01:16
     */
    public function check_user_loginAction(){
        $tokens = new UserTokenModel();
        $dat = I('post.');
        $uid = $dat['uid'];// 用户名
        $token = $dat['token'];// 密码,需要用户进行MD5计算以后再传输过来
        $type = intval($dat['type']);// 登录设备类型 1网页登录2Android登录3IOS登录4微信登录5支付宝登录
        if($tokens->check_user_token($uid, $token, $type)){
            return $this->returnCode('R0000');
        }
        return $this->returnCode('U0005');
    }
    
    /**
     * 获取用户登录信息
     * 
     * @return json
     * @author xiaoyutab<xiaoyutab@qq.com>
     * @version v1.0.0
     * @copyright (c) 2017, xiaoyutab
     * @adtime 2017-09-05 17:20:13
     */
    public function get_user_infoAction(){
        $tokens = new UserTokenModel();
        $dat = I('post.');
        $uid = $dat['uid'];// 用户名
        $token = $dat['token'];// 密码,需要用户进行MD5计算以后再传输过来
        $type = intval($dat['type']);// 登录设备类型 1网页登录2Android登录3IOS登录4微信登录5支付宝登录
        
        if($tokens->check_user_token($uid, $token, $type)){
            // 如果检测token通过后则获取用户的信息进行返回
            $users = new UserReceptionModel();
            $info = $users->where(array('id'=>$uid,'status'=>array('neq',98)))->cache(true)->find();
            $file = new FileLinkModel();
            $avatar = $file->get_file_info($info['id'], 'user_reception', TRUE);
            $return_info = array(
                'uid' => intval($info['id']),
                'token' => $token,
                'username' => $info['username'].'',
                'nickname' => $info['nickname'].'',
                'phone' => phont_view_type(3,$info['phone']).'',// 手机号隐藏中间四位
                'email' => $info['email'].'',
                'avatar' => $avatar['thumb_200'],
                'type' => intval($info['type']),// 用户类型,1抵押专员2调评专员3普通用户
                'reg_time' => date('Y-m-d H:i:s',$info['ad_time']),
                'is_login' => boolval($info['status'] != 97),
                'is_del' => boolval($info['status'] == 98),
                'info' => $avatar,
            );
            if(!empty($info)){
                return $this->returnCode($return_info);
            }
        }
        return $this->returnCode('U0005');
    }
}