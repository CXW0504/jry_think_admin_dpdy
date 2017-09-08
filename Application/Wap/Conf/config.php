<?php
return array(
    'HTTP_URL_FIX' => 'http://xiaoyutab.cn/Api/',// URL前缀地址
    'FILE_URL_FIX' => 'http://xiaoyutab.cn/Public/Upload/',// 头像/下载文件等前缀地址
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
    // WAP端的菜单配置
    'MENU_CONFIG' => array(
        // 底部导航条。如果控制器不在这里面将不显示底部导航条
        'FOOTER_MENU' => array(
            // 首页信息展示
            'Index' => array(
                'class' => 'aui-icon-home',// 图标样式
                'right_top_smail' => FALSE,// 是否显示图标右上角的小红点
                'right_top_big_type' => FALSE, // 是否显示图标右上角的大红点【数字通知】
                'right_top_big' => '0',// 显示在大红点上的数字
                'label' => '首页',// 图标对应的名称
                'index' => 'index',// 默认操作方法
            ),
            // 健康信息展示
            'Healthy' => array(
                'class' => 'aui-icon-paper',// 图标样式
                'right_top_smail' => FALSE,// 是否显示图标右上角的小红点
                'right_top_big_type' => FALSE, // 是否显示图标右上角的大红点【数字通知】
                'right_top_big' => '0',// 显示在大红点上的数字
                'label' => '健康',// 图标对应的名称
                'index' => 'index',// 默认操作方法
            ),
            // 发现状态展示
            'Discover' => array(
                'class' => 'aui-icon-location',// 图标样式
                'right_top_smail' => FALSE,// 是否显示图标右上角的小红点
                'right_top_big_type' => FALSE, // 是否显示图标右上角的大红点【数字通知】
                'right_top_big' => '0',// 显示在大红点上的数字
                'label' => '发现',// 图标对应的名称
                'index' => 'index',// 默认操作方法
            ),
            // 用户控制
            'User' => array(
                'class' => 'aui-icon-my',// 图标样式
                'right_top_smail' => FALSE,// 是否显示图标右上角的小红点
                'right_top_big_type' => FALSE, // 是否显示图标右上角的大红点【数字通知】
                'right_top_big' => '0',// 显示在大红点上的数字
                'label' => '我的',// 图标对应的名称
                'index' => 'info',// 默认操作方法
            ),
        ),
    ),
);
