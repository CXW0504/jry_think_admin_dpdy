<?php
return array(
    'HTTP_URL_FIX' => 'http://xiaoyutab.cn/Api/',// URL前缀地址
    'COOKIE_PREFIX' => 'xy_'.md5('xiaoyutab_wap_'), // Cookie前缀 避免冲突
    'TOKEN_CHECK_TIME' => 60, // TOKEN检测时间，即每隔多长时间检测一次。单位：秒
    'TMPL_ACTION_ERROR' => 'Common:tips_error',
    'TMPL_ACTION_SUCCESS' => 'Common:tips_success',
    'HTML_CACHE_ON' => true, // 开启静态缓存
    'HTML_CACHE_TIME ' => -1, // 设置永久缓存
    'HTML_FILE_SUFFIX' => '.html',
    'HTML_CACHE_RULES' => array(
        // 配置Doc控制器下的全部操作均永久缓存
        'Doc:' => array('Home/Doc/{$_SERVER.REQUEST_URI|md5}'),
    ),
);
