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
        'child' => array(
            'distribution_add' => '发起新任务',
            'distribution_view' => '查看任务',
            'distribution_update' => '修改任务',
        ),
    ),
    'Word' => array(
        'name' => '考勤管理',
        'class' => 'icon-clock-o',
        'content' => array(
            'timecard' => '考勤列表',
            'setting' => '考勤规则配置',
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
    'System' => array(
        'name' => '系统配置',
        'class' => 'icon-cogs',
        'content' => array(
            'department_list' => '通讯录部门管理',
            'directories_user_list' => '通讯录管理',
            'loan_mold_list' => '抵押顺位管理',
            'loan_customer_marriage_list' => '借款人婚姻情况管理',
            'loan_hous_type_list' => '房产状态管理',
            'loan_insurance_list' => '抵押权人管理',
        ),
        'child' => array(
            'department_add' => '添加通讯录部门',
            'department_save' => '修改通讯录部门',
            'department_del' => '删除通讯录部门',
            'directories_user_add' => '添加通讯录联系人',
            'directories_user_save' => '修改通讯录联系人',
            'directories_user_del' => '删除通讯录联系人',
            'loan_mold_add' => '添加抵押顺位',
            'loan_customer_marriage_add' => '添加借款人婚姻情况',
            'loan_hous_type_add' => '添加房产状态',
            'loan_insurance_add' => '添加抵押权人',
        ),
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