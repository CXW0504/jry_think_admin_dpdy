<?php
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2014 http://xiaoyutab.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: xiaoyutab <xiaoyutab@qq.com>
// +----------------------------------------------------------------------

// 应用入口文件
if (isset($_GET['s'])) {
    if ($_GET['s'] == 'info') {
        phpinfo();
        exit;
    }
}

//if (!isset($_GET['s']) || empty($_GET['s'])) {
//    if (check_wap()) {
//        header('Location:?s=/Wap');
//        exit;
//    }
//}

/**
 * 检测是否是手机登录
 * @return boolean
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

// 检测PHP环境
if (version_compare(PHP_VERSION, '5.3.0', '<')) die('require PHP > 5.3.0 !');

// 开启调试模式 建议开发阶段开启 部署阶段注释或者设为false
define('APP_DEBUG', TRUE);

// 定义Runtime目录为Public下的Runtime目录
define('RUNTIME_PATH', './Public/Runtime/');

// 定义页面静态缓存文件目录在Runtime目录下
define('HTML_PATH', './Public/Runtime/Html/');

// 定义应用目录
define('APP_PATH', './Application/');

// 引入ThinkPHP入口文件
require './ThinkPHP/ThinkPHP.php';