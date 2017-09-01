<?php
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2014 http://xiaoyutab.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: xiaoyutab <xiaoyutab@qq.com>
// +----------------------------------------------------------------------

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