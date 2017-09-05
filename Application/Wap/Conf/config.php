<?php
return array(
    'HTTP_URL_FIX' => 'http://xiaoyutab.cn/Api/',// URL前缀地址
    'HTML_CACHE_ON' => true, // 开启静态缓存
    'HTML_CACHE_TIME ' => -1, // 设置永久缓存
    'HTML_FILE_SUFFIX' => '.html',
    'HTML_CACHE_RULES' => array(
        // 配置Doc控制器下的全部操作均永久缓存
        'Doc:' => array('Home/Doc/{$_SERVER.REQUEST_URI|md5}'),
    ),
);
