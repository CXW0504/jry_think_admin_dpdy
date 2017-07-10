<?php

// 菜单列表
return array(
    'Loan' => array(
        'name' => '订单管理',
        'class' => 'icon-file',
        'content' => array(
            'distribution' => '分配订单',
            'handle' => '处理中订单',
            'pending' => '待处理订单',
            'complete' => '审核完成订单',
            'file' => '已完成订单',
            'termination' => '已终止订单',
        ),
        'child' => array(),
    ),
    'Word' => array(
        'name' => '考勤管理',
        'class' => 'icon-clock-o',
        'content' => array(
            'timecard' => '考勤列表',
        ),
        'child' => array(),
    ),
    'Data' => array(
        'name' => '数据分析',
        'class' => 'icon-bar-chart-o',
        'content' => array(
            'analysis' => '数据分析',
        ),
        'child' => array(),
    ),
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
    'Log' => array(
        'name' => '日志管理',
        'class' => 'icon-save',
        'content' => array(
            'login' => '后台登录日志',
        ),
        'child' => array(),
    ),
);