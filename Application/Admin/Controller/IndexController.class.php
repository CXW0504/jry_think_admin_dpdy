<?php

namespace Admin\Controller;

/**
 * 网站首页信息
 * 
 * @author xiaoyutab<xiaoyutab@qq.com>
 * @version v1.0.2
 * @copyright (c) 2017, xiaoyutab
 * @adtime 2017-07-05 16:41:11
 */
class IndexController extends CommonController {

    /**
     * 构造函数类
     * 导入页面需要使用的jquery挂件和页面需要的样式表
     * 
     * @author xiaoyutab<xiaoyutab@qq.com>
     * @copyright (c) 2017, xiaoyutab
     * @adtime 2017-07-05 16:41:19
     */
    public function __construct() {
        $this->wget('jquery')->wget('jquery_pintuer')->css('Css/Admin/admin');
        parent::__construct();
    }

    /**
     * 网站首页信息
     * 
     * @return void
     * @author xiaoyutab<xiaoyutab@qq.com>
     * @copyright (c) 2017, xiaoyutab
     * @adtime 2017-07-05 16:41:48
     */
    public function indexAction() {
        return $this->display();
    }

    /**
     * 网站欢迎页面
     * 
     * @return void
     * @author xiaoyutab<xiaoyutab@qq.com>
     * @copyright (c) 2017, xiaoyutab
     * @adtime 2017-07-05 18:58:30
     */
    public function helloAction() {
        // 引入百度完整插件库，使用内部的饼状图
        $this->wget('echarts');
        $size = function_exists('disk_free_space') ? round((disk_free_space(".") / (1024 * 1024 * 1024)), 2) : 0;
        $all_size = function_exists('disk_total_space') ? round((disk_total_space("/") / (1024 * 1024 * 1024)), 2) : 0;
        $self_mb = function_exists('memory_get_usage') ? round((memory_get_usage() / (1024 * 1024)), 2) : 0;// 当前已用内存
        $self_all = function_exists('memory_get_peak_usage') ? round((memory_get_peak_usage () / (1024 * 1024)), 2) : 0;// 最大可用内存
        $this->assign(array(
            'free' => $size, // 剩余磁盘大小，单位：MB
            'all_size' => $all_size - $size, // 磁盘已用空间
            'self_mb' => $self_mb,
            'self_all' => ini_get('memory_limit') - $self_mb,
        ));
        return $this->display();
    }

    /**
     * 获取磁盘所占文件总大小
     * @param type $dir
     * @return type
     */
    private function getDirSize($dir = '.') {
        $handle = opendir($dir);
        while (false !== ($FolderOrFile = readdir($handle))) {
            if ($FolderOrFile != "." && $FolderOrFile != "..") {
                if (is_dir("$dir/$FolderOrFile")) {
                    $sizeResult += self::getDirSize("$dir/$FolderOrFile");
                } else {
                    $sizeResult += filesize("$dir/$FolderOrFile");
                }
            }
        }
        closedir($handle);
        return $sizeResult;
    }

}
