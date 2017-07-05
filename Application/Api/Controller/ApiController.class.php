<?php
namespace Api\Controller;
use Think\Controller;

/**
 * 公共控制器方法，输出json、输出jsonp等方式均在此处定义
 * 
 * @author xiaoyutab<xiaoyutab@qq.com>
 * @adtime 2017-2-16 15:32:35
 * @version v1.0.0
 * @copyright (c) 2017, xiaoyutab
 */
class ApiController extends Controller {

    /**
     * 定义输出方式，该方式兼容JSONP格式
     * @param  string $code 要返回的代码，如果是数据的话需要在此处传入数组
     * @return void 
     * @echo   json         输出json格式字符串。如果是jsonp请求就输出jsonp格式字符串
     * 
     * @author xiaoyutab<xiaoyutab@qq.com>
     * @version v1.0.0
     * @copyright (c) 2017, xiaoyutab
     * @adtime 2017-2-16 15:36:01
     */
    public function returnCode($code = '') {
        $error_code = C('ERROR_CODE');
        if (empty($code)) {
            $success = json_encode(array(
                'code' => 'R0001',
                'msg' => $error_code['R0001'],
                'time' => date('Y-m-d H:i:s'),
                'data' => array(),
            ));
        } else if (is_array($code)) {
            $success = json_encode(array(
                'code' => 'R0000',
                'msg' => $error_code['R0000'],
                'time' => date('Y-m-d H:i:s'),
                'data' => $code,
            ));
        } else {
            $success = json_encode(array(
                'code' => $code,
                'msg' => $error_code[$code],
                'time' => date('Y-m-d H:i:s'),
                'data' => array(),
            ));
        }
        // 设置json返回头信息
        header("Content-Type:application/Json");
        // 支持jsonp格式调用
        if (!empty($_GET['jsoncallback'])) {
            die($_GET['jsoncallback'] . "(" . $success . ')');
        }
        die($success);
    }
    
    /**
     * 测试接口页面
     */
    public function indexAction(){
        $this->returnCode(array());
    }
}
