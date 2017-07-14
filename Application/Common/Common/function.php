<?php

/**
 * 生成随机字符串
 * 
 * @param  string $hash   要生成的名字前缀
 * @param  number $length 要生成的名字字符串长度
 * @return string         生成的文件名字符串
 * @author xiaoyutab<xiaoyutab@qq.com>
 * @version v1.0.0
 * @copyright (c) 2017, xiaoyutab
 * @adtime 2017-07-14 11:54:01
 */
function random($hash = '', $length = 10) {
    $chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789abcdefghijklmnopqrstuvwxyz';
    $max = strlen($chars) - 1;
    mt_srand((double) microtime() * 1000000);
    for ($i = 0; $i < $length; $i++) {
        $hash .= $chars[mt_rand(0, $max)];
    }
    return $hash;
}

/**
 * 设置密码函数，用来将密码进行加密
 * 为了在传输过程中不泄密，建议再客户端进行一层MD5加密后再进行本函数的加密
 * 
 * @param  string $password 未经加密后的密码
 * @param  string $rand_code 用户密码的安全验证码
 * @return string 加密后的32位定长不可逆密码
 * @author xiaoyutab<xiaoyutab@qq.com>
 * @version v1.0.0
 * @copyright (c) 2017, xiaoyutab
 * @adtime 2017-07-14 11:54:05
 */
function pass($password = '', $rand_code = 'http://xiaoyutab.cn') {
    $addr = base64_encode($rand_code);
    return md5(md5(md5($password . md5($password) . md5($addr)) . $addr));
}

/**
 * 根据手机号隐藏规则进行处理手机号
 * 
 * @param intval $type 处理类型
 * @param string $phone 用户手机号，如果超过了11位则截取后是一位进行处理
 * @return string 处理后的手机号
 * @author xiaoyutab<xiaoyutab@qq.com>
 * @version v1.0.0
 * @copyright (c) 2017, xiaoyutab
 * @adtime 2017-07-14 12:51:45
 */
function phont_view_type($type = 1, $phone = '') {
    if(strlen($phone) != 11){
        $phone = substr($phone,-11);
    }
    switch ($type) {
        case 1:
            // 全部显示
            return $phone;
            break;
        case 2:
            // 全部隐藏
            return '****';
            break;
        case 4:
            // 只显示后四位
            return '****'.substr($phone, -4);
            break;
        default:
            // 中间隐藏
            return substr($phone,0,3).'****'.substr($phone,-4);
            break;
    }
}
