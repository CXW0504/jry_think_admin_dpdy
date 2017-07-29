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
        'child' => array(
            'add_timecard' => '添加考勤规则',
            'save_timecard' => '修改考勤规则',
            'del_timecard' => '删除考勤规则',
            'out_timecard' => '导出考勤规则',
            'help_timecard_coordinate' => '经纬度坐标获取帮助',
        ),
    ),
    'Data' => array(
        'name' => '数据管理',
        'class' => 'icon-bar-chart-o',
        'content' => array(
            'analysis' => '订单数据分析',
            'mcch_data_list' => 'mcch财务计算器',
            'other_data_list' => 'other费用计算器',
        ),
        'child' => array(
            'mcch_data_list_download' => '导出mcch财务计算器还款计划表',
            'mcch_data_list_delete' => '删除mcch财务计算器',
        ),
    ),
    'System' => array(
        'name' => '系统配置',
        'class' => 'icon-cogs',
        'content' => array(
            'banner_list' => '广告管理',// 该功能已完成，但是项目中的Banner其实为公告缩略图，故此处注释掉
            'department_list' => '通讯录部门管理',
            'directories_user_list' => '通讯录管理',
            'loan_mold_list' => '抵押顺位管理',
            'loan_customer_marriage_list' => '借款人婚姻情况管理',
            'loan_hous_type_list' => '房产类型管理',
            'loan_insurance_list' => '抵押权人管理',
        ),
        'child' => array(
            'banner_add' => '添加广告',// 该功能已完成，但是项目中的Banner其实为公告缩略图，故此处注释掉
            'banner_save' => '修改广告',// 该功能已完成，但是项目中的Banner其实为公告缩略图，故此处注释掉
            'banner_del' => '删除广告',// 该功能已完成，但是项目中的Banner其实为公告缩略图，故此处注释掉
            'department_add' => '添加通讯录部门',
            'department_save' => '修改通讯录部门',
            'department_del' => '删除通讯录部门',
            'directories_user_add' => '添加通讯录联系人',
            'directories_user_save' => '修改通讯录联系人',
            'directories_user_del' => '删除通讯录联系人',
            'loan_mold_add' => '添加抵押顺位',
            'loan_mold_save' => '编辑抵押顺位',
            'loan_mold_del' => '删除抵押顺位',
            'loan_customer_marriage_add' => '添加借款人婚姻情况',
            'loan_customer_marriage_save' => '修改借款人婚姻情况',
            'loan_customer_marriage_del' => '删除借款人婚姻情况',
            'loan_hous_type_add' => '添加房产类型',
            'loan_hous_type_save' => '修改房产类型',
            'loan_hous_type_del' => '删除房产类型',
            'loan_insurance_add' => '添加抵押权人',
            'loan_insurance_save' => '修改抵押权人',
            'loan_insurance_del' => '删除抵押权人',
        ),
    ),
    'User' => array(
        'name' => '用户管理',
        'class' => 'icon-user',
        'content' => array(
            'group_list' => '权限组列表',
            'user_list' => '后台用户列表',
            'reception_group_list' => '前台用户组列表',
            'reception_list' => '前台用户列表',
        ),
        'child' => array(
            'group_del' => '删除权限组',
            'group_save' => '修改权限组',
            'group_add' => '添加权限组',
            'group_distribution' => '权限组授权分组',
            'group_jurisdiction' => '为权限组授权',
            'user_del' => '删除后台用户',
            'user_save' => '更新后台用户信息',
            'user_add' => '后台添加用户',
            'reception_group_add' => '添加前台用户组',
            'reception_group_save' => '修改前台用户组',
            'reception_group_del' => '删除前台用户组',
            'reception_add' => '添加前台用户',
            'reception_save' => '修改前台用户',
            'reception_del' => '删除前台用户',
        ),
    ),
    // 以后也要删除掉的接口管理模块，用来后期自动生成接口文档所用
    'Document' => array(
        'name' => '文档常量',
        'class' => 'icon-briefcase',
        'content' => array(
            'apis' => '接口文档',
            'parameter' => '参数类型',
        ),
        'child' => array(
            'add_apis' => '新建项目',
            'save_apis' => '更新项目',
            'del_apis' => '删除项目',
            'list_apis' => '项目下的接口',
            'save_list_apis' => '更新接口',
            'del_list_apis' => '删除接口',
            'add_list_apis' => '添加接口',
            'add_parameter' => '添加参数类型',
            'save_parameter' => '更新参数类型',
            'del_parameter' => '删除参数类型',
            'add_project_parameter' => '添加接口参数',
            'save_project_parameter' => '修改接口参数',
            'del_project_parameter' => '删除接口参数',
        ),
    ),
    // 暂时去掉日志浏览、检索功能，后期再进行添加
     'Log' => array(
         'name' => '日志管理',
         'class' => 'icon-save',
         'content' => array(
             'login' => '后台登录日志',
         ),
         'child' => array(),
     ),
);