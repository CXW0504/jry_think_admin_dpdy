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
    if (strlen($phone) != 11) {
        $phone = substr($phone, -11);
    }
    switch ($type) {
        case 1:
            // 全部显示
            return $phone;
            break;
        case 2:
            // 全部隐藏
            return '***********';
            break;
        case 4:
            // 只显示后四位
            return '*******' . substr($phone, -4);
            break;
        default:
            // 中间隐藏
            return substr($phone, 0, 3) . '****' . substr($phone, -4);
            break;
    }
}

/**
 * 获取某个时间的周时间段
 * 
 * @param string $times strtotime的时间参数
 * @return array 获取到的时间段区间。[0]开始时间[1]结束时间
 * @author xiaoyutab<xiaoyutab@qq.com>
 * @version v1.0.0
 * @copyright (c) 2017, xiaoyutab
 * @adtime 2017-08-03 11:05:10
 */
function get_week_time($times = 'now') {
    $tiems = strtotime($times);
    return array(
        date('Y-m-d', strtotime(date('Y-m-d', $tiems)) - date('w', strtotime(date('Y-m-d', $tiems))) * 86400),
        date('Y-m-d', strtotime(date('Y-m-d', $tiems)) + ( 6 - date('w', strtotime(date('Y-m-d', $tiems)))) * 86400)
    );
}

/**
 * 关键词突出显示
 * 
 * @param string $get 要搜索的关键词，如果是多个可以用空格分开
 * @param string $finds 从哪里面开始替换
 * @param string $color 突出的背景色RPG的16进制值
 * @param string $eng 是否以贪婪模式替换[不区分大小写]
 * @return string 替换后的字符串
 * @version v1.0.1
 * @copyright (c) 2017, xiaoyutab
 * @author xiaoyutab<xiaoyutab@qq.com>
 * @adtime 2017-08-01 19:21:41
 */
function replace_keywords($get = '', $finds = '', $color = '0f3',$eng = false) {
    $ext = explode(' ', $get);
    foreach ($ext as $v) {
        $finds = str_replace($v, '<span style="background:#' . $color . '">' . $v . '</span>', $finds);
        if($eng){
            // 将V转换成大写字母再次替换一遍
            $v = strtoupper($v);
            $finds = str_replace($v, '<span style="background:#' . $color . '">' . $v . '</span>', $finds);
        }
        // 判断，如果传入的get参数是数字而且是带-的区间数字，则匹配需要替换的数字是否在这个区间内
        if (intval($v) > 0) {
            $lists = explode('-', $v);
            if (count($lists) > 1) {
                if ($finds >= $lists[0] && end($lists) > $finds) {
                    $finds = '<span style="background:#' . $color . '">' . $finds . '</span>';
                }
            }
        }
    }
    return $finds;
}

/**
 * 获取用户性别
 * 
 * @param number $sex 用户性别信息
 * @return string 获取到的性别信息
 * @version v1.0.0
 * @copyright (c) 2017, xiaoyutab
 * @author xiaoyutab<xiaoyutab@qq.com>
 * @adtime 2017-08-01 19:21:45
 */
function user_sex($sex = 0) {
    $arr = array('保密', '男', '女');
    if (empty($arr[$sex])) {
        return $arr[0];
    }
    return $arr[$sex];
}

/**
 * 文件大小单位转换GB MB KB  
 * 
 * @param number $size 文件大小信息
 * @return string 转换后的大小信息
 * @version v1.0.0
 * @copyright (c) 2017, xiaoyutab
 * @author xiaoyutab<xiaoyutab@qq.com>
 * @adtime 2017-08-01 19:21:45
 */
function size_conversion($size = 0) {
    $units = array(' B', ' KB', ' MB', ' GB', ' TB');
    for ($i = 0; $size >= 1024 && $i < 4; $i++)
        $size /= 1024;
    return round($size, 2) . $units[$i];
}



/**
 * 检测是否是手机登录
 * 
 * @return boolean
 * @version v1.0.0
 * @copyright (c) 2017, xiaoyutab
 * @author xiaoyutab<xiaoyutab@qq.com>
 * @adtime 2017年09月01日23:38:33
 */
function check_wap() {
    if (isset($_SERVER['HTTP_VIA'])) {
        // 先检查是否为wap代理，准确度高
        if (stristr($_SERVER['HTTP_VIA'], "wap")) {
            return true;
        } elseif (strpos(strtoupper($_SERVER['HTTP_ACCEPT']), "VND.WAP.WML") > 0) {
            return true;
        }
    }
    //检查USER_AGENT
    elseif (preg_match('/(blackberry|configuration\/cldc|hp |hp-|htc |htc_|htc-|iemobile|kindle|midp|mmp|motorola|mobile|nokia|opera mini|opera |Googlebot-Mobile|YahooSeeker\/M1A1-R2D2|android|iphone|ipod|mobi|palm|palmos|pocket|portalmmm|ppc;|smartphone|sonyericsson|sqh|spv|symbian|treo|up.browser|up.link|vodafone|windows ce|xda |xda_)/i', $_SERVER['HTTP_USER_AGENT'])) {
        return true;
    }
    return false;
}

/**
 * 将变量转换成Boolean类型
 *      如果系统存在boolval函数就忽略，如果不存在就定义该函数
 * 
 * @return boolean
 * @version v1.0.0
 * @copyright (c) 2017, xiaoyutab
 * @author xiaoyutab<xiaoyutab@qq.com>
 * @adtime 2017-09-05 13:42:54
 */
if (!function_exists('boolval')) {
    function boolval($val) {
        return (bool) $val;
    }
}