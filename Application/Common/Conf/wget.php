<?php

return array(
    // 使用ueditor网页编辑器
    'ueditor' => array(
        'css' => array('wget/ueditor/1.4.3/themes/default/css/ueditor'),
        'js' => array('wget/ueditor/1.4.3/lang/zh-cn/zh-cn', 'wget/ueditor/1.4.3/ueditor.config', 'wget/ueditor/1.4.3/ueditor.all.min'),
    ),
    // 使用jQuery插件
    'jquery' => array(
        'js' => array('wget/jquery/2.0.3/jquery-2.0.3.min'),
    ),
    // 使用jquery局部打印插件[该插件依赖jquery插件]
    'jquery_print' => array(
        'js' => array('wget/jquery/jquery.PrintArea'),
    ),
    // 使用bootstrap网页样式表
    'bootstrap' => array(
        'css' => array('wget/bootstrap/3.0.0/bootstrap.min'),
        'js' => array('wget/bootstrap/3.0.0/bootstrap.min'),
    ),
    // 后台常用bootstrap-ace集成插件
    'bootstrap-ace' => array(
        'js' => array('wget/bootstrap/ace/ace.min', 'wget/bootstrap/ace/ace-extra.min'),
        'css' => array('wget/bootstrap/ace/ace.min', 'wget/bootstrap/ace/font-awesome.min', 'wget/bootstrap/ace/style'),
    ),
    // bootstrap时间日期选择插件
    'bootstrap-daterangepicker' => array(
        'js' => array('wget/bootstrap/bootstrap-daterangepicker/moment.min', 'wget/bootstrap/bootstrap-daterangepicker/daterangepicker'),
        'css' => array('wget/bootstrap/bootstrap-daterangepicker/font-awesome.min', 'wget/bootstrap/bootstrap-daterangepicker/daterangepicker-bs3'),
    ),
    // 网页轮播插件
    'unslider' => array(
        'css' => array('wget/unslider/unslider'),
        'js' => array('wget/unslider/unslider.min'),
    ),
    // angular插件，该插件可以使用绑定参数
    'angular' => array(
        'js' => array('wget/angular/angular-1.3.0/angular.min'),
    ),
    // angular-touch插件
    'angular-touch' => array(
        'js' => array('wget/angular/angular-1.3.0/angular-touch.min'),
    ),
    // angular-route路由插件
    'angular-route' => array(
        'js' => array('wget/angular/angular-1.3.0/angular-route.min'),
    ),
    // 图片查看插件
    'viewer' => array(
        'js' => array('wget/viewer/viewer-0.1.0/viewer'),
        'css' => array('wget/viewer/viewer-0.1.0/viewer'),
    ),
    // 蓝色MUI模板框架
    'jquery_pintuer' => array(
        'js' => array('wget/jquery/pintuer-1.0/js/pintuer'),
        'css' => array('wget/jquery/pintuer-1.0/css/pintuer'),
    ),
);
