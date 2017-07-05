<?php

/**
 * 生成随机文件名称
 * @param  string $hash   要生成的名字前缀
 * @param  number $length 要生成的名字字符串长度
 * @return string         生成的文件名字符串
 */
function random($hash = '',$length = 10){
    $chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789abcdefghijklmnopqrstuvwxyz';
    $max = strlen($chars) - 1;
    mt_srand((double)microtime() * 1000000);
    for($i = 0; $i < $length; $i++){
        $hash .= $chars[mt_rand(0, $max)];
    }
    return $hash;
}

/**
 * 设置密码函数，用来将密码进行加密
 * 为了在传输过程中不泄密，建议再客户端进行一层MD5加密后再进行本函数的加密
 * @param  string $password 未经加密后的密码
 * @param  string $rand_code 用户密码的安全验证码
 * @return string 加密后的32位定长不可逆密码
 */
function pass($password = '',$rand_code = 'http://xiaoyutab.cn') {
    $addr = base64_encode($rand_code);
    return md5(md5(md5($password . md5($password) . md5($addr)) . $addr));
}