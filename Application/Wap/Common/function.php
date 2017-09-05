<?php
/**
 * 网站前台操作函数文件
 * 
 * @version v1.0.1
 * @copyright (c) 2017, xiaoyutab
 * @author xiaoyutab<xiaoyutab@qq.com>
 * @adtime 2017-06-30 10:32:56
 */
use Org\Net\Curl;

/**
 * 请求接口数据
 * 
 * @param String $api_url 请求地址
 * @param array $api_data 附加参数
 * @param string $types 请求方式。get|post
 * @return boolean
 */
function get_api_data($api_url = '',$api_data = array(),$types = 'get'){
    $curl = new Curl();
    $type = strtolower($types);// 讲类型转换为小写
    $api_url = C('HTTP_URL_FIX') . $api_url;
    if($type == 'get'){
        // 如果是GET请求，就将参数直接追加到URL后面进行请求
        if(strpos($api_url, '?')){
            // 如果查询到？且位置不是最开始
            $api_url .= '&'.implode($api_data, '&');
        } else {
            // 如果没有找到?
            $api_url .= '?_rand_nid=' . NOW_TIME . rand(1000, 9999);// 拼接一个随机字符串的_rand_nid，然后后面追加正常参数
            $api_url .= '&'.implode($api_data, '&');
        }
        return $curl->get($api_url);
    } else if($type == 'post'){
        return $curl->post($api_url, $api_data);
    }
    return FALSE;
}