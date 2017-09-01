<?php
/**
 * 【此文件不允许上传】
 */
if(check_wap()){
    $defaults = 'Wap';
} else {
    $defaults = 'Home';
}
return array(
//    'HTML_CACHE_ON' => true, // 开启静态缓存
//    'HTML_CACHE_TIME ' => -1, // 设置永久缓存
//    'HTML_FILE_SUFFIX' => '.html',
//    'HTML_CACHE_RULES' => array(
//        '*' => array('{:module}/{:action}/{$_SERVER.REQUEST_URI|md5}'),
//    ),
    
    // 页面需要的插件列表，需要就在此处配置，然后使用$this->wget进行引用
    'SHOW_WGET_LIST' => include_once __DIR__ . '/wget.php',
    'MODULE_ALLOW_LIST' => array('Home','Wap','Admin','Api','Mcch','Other'), //允许访问的模块列表
    'DEFAULT_MODULE' => $defaults,
    'MODULE_DENY_LIST' => array('Common', 'Runtime'), // 禁止访问的模块列表
    'SHOW_PAGE_TRACE' =>FALSE, // 显示页面Trace信息
    'URL_MODEL'  =>  2, // URL访问模式,可选参数0、1、2、3,代表以下四种模式：
    // 0 (普通模式); 1 (PATHINFO 模式); 2 (REWRITE  模式); 3 (兼容模式)  默认为PATHINFO 模式
    
    'ACTION_SUFFIX'         =>  'Action', // 操作方法后缀
    'IMAGE_SAVE_PATH' => './Public/Upload/',
    'IMAGE_SAVE_HOST' => 'http://xiaoyutab.cn/Public/Upload/',
    //'IMAGE_SAVE_HOST' => 'http://xiaoyutab.com/Public/Upload/',
    'IMG_maxSize' => 2,
    'IMG_exts' => array('jpg','gif','jpeg','pjpeg','png'),
    
    // 此处配置为远程数据库配置
    // 'DB_TYPE' =>  'mysql',     // 数据库类型 默认配置为mysql，故此处已注释
    'DB_HOST' =>  'localhost', // 服务器地址
    'DB_NAME' =>  'jry_think_admin_dpdy', // 数据库名
    'DB_USER' =>  'root',      // 用户名
    'DB_PWD' =>  'root',          // 密码
    'DB_PREFIX' =>  'dpdy_',    // 数据库表前缀
    'DB_PORT' =>  '3306',    // 端口
//    'DB_CHARSET'  =>  'utf8', // 数据库编码默认采用utf8。默认配置为mysql，故此处已注释
    // 此处配置为本地数据库配置【此文件不允许上传】
    // 'DB_TYPE' =>  'mysql',     // 数据库类型
    // 'DB_HOST' =>  'xiaoyutab.cn', // 服务器地址
    // 'DB_NAME' =>  'xiaoyutab_emlog',          // 数据库名
    // 'DB_USER' =>  'actine',      // 用户名
    // 'DB_PWD' =>  'yumaojing',          // 密码
    // 'DB_PREFIX' =>  'emlog_',    // 数据库表前缀
    // 'DB_PORT' =>  '3306',    // 端口

    // 模板文件后缀
    'TMPL_TEMPLATE_SUFFIX' => '.html',
    // 启用自动布局
    'LAYOUT_ON' => true,
    // 自动布局的布局文件
    'LAYOUT_NAME' => 'Common/layout',
    // 模板页调用内容页时的关键字
    'TMPL_LAYOUT_ITEM' => '{__CONTENT__}',
    // 模板缓存有效期，单位秒
    'TMPL_CACHE_TIME' =>  0,
);
