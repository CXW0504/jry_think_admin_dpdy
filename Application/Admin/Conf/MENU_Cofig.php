<?php

// 菜单列表
return array(
//    'Index' => array(
//        'name' => '基本信息',
//        'class' => 'icon-home',
//        'content' => array(
//            'info' => '网站基本信息',
//        ),
//        'child' => array(
//            // 隐藏权限信息
//            'index' => '首页框架',
//            'info' => '后台详情页面',
//            'hello' => '后台欢迎页面',
//        ),
//    ),
    'User' => array(
        'name' => '用户管理',
        'class' => 'icon-user',
        'content' => array(
            'group_list' => '权限组列表',
            'group_add' => '添加权限组',
            'user_list' => '用户列表',
            'user_add' => '添加用户',
        ),
        'child' => array(
            'group_del' => '删除权限组',
            'group_save' => '修改权限组',
            'group_jurisdiction' => '为权限组授权',
            'user_del' => '删除用户',
            'user_save' => '更新用户信息',
        ),
    ),
);