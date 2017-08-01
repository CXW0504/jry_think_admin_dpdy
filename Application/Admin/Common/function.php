<?php
/**
 * 网站后台操作函数文件
 * 
 * @version v1.0.1
 * @copyright (c) 2017, xiaoyutab
 * @author xiaoyutab<xiaoyutab@qq.com>
 * @adtime 2017-06-30 10:32:56
 */

/**
 * 关键词突出显示
 * 
 * @param string $get 要搜索的关键词，如果是多个可以用空格分开
 * @param string $finds 从哪里面开始替换
 * @param string $color 突出的背景色RPG的16进制值
 * @return string 替换后的字符串
 * @version v1.0.0
 * @copyright (c) 2017, xiaoyutab
 * @author xiaoyutab<xiaoyutab@qq.com>
 * @adtime 2017-08-01 19:21:41
 */
function replace_keywords($get = '',$finds = '',$color = '0f3'){
    $ext = explode(' ', $get);
    foreach ($ext as $v){
        $finds = str_replace($v, '<span style="background:#'.$color.'">'.$v.'</span>', $finds);
    }
    return $finds;
}

/**
 * 获取用户性别
 * 
 * @param number $sex 用户性别信息
 * @return string 获取到的性别信息
 * @version v1.0.0
 * @copyright (c) 2017, xiaoyutab
 * @author xiaoyutab<xiaoyutab@qq.com>
 * @adtime 2017-08-01 19:21:45
 */
function user_sex($sex = 0){
    $arr = array('保密','男','女');
    if(empty($arr[$sex])){
        return $arr[0];
    }
    return $arr[$sex];
}