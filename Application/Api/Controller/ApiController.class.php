<?php
namespace Api\Controller;
use Think\Controller;

/**
 * 公共控制器方法，输出json、输出jsonp等方式均在此处定义
 * 
 * @author xiaoyutab<xiaoyutab@qq.com>
 * @version v1.0.0
 * @copyright (c) 2017, xiaoyutab
 * @adtime 2017-2-16 15:32:35
 */
class ApiController extends Controller {

    /**
     * 定义输出方式，该方式兼容JSONP格式
     * 
     * @param  string $code 要返回的代码，如果是数据的话需要在此处传入数组
     * @return void 
     * @echo   json         输出json格式字符串。如果是jsonp请求就输出jsonp格式字符串
     * @author xiaoyutab<xiaoyutab@qq.com>
     * @version v1.0.0
     * @copyright (c) 2017, xiaoyutab
     * @adtime 2017-2-16 15:36:01
     */
    public function returnCode($code = 'R0000') {
        $error_code = C('ERROR_CODE');
        if (is_array($code)) {
            $success = array(
                'code' => 'R0000',
                'msg' => $error_code['R0000'],
                'data' => $code,
            );
        } else if(isset($error_code[$code])){
            $success = array(
                'code' => $code,
                'msg' => $error_code[$code],
                'data' => array(),
            );
        } else {
            $success = array(
                'code' => 'R0001',
                'msg' => $error_code['R0001'],
                'data' => array(),
            );
        }
        $success['time'] = date('Y-m-d H:i:s');
        // 设置json返回头信息
        header("Content-Type:application/Json");
        // 支持jsonp格式调用
        if (!empty($_GET['jsoncallback'])) {
            die($_GET['jsoncallback'] . "(" . json_encode($success) . ')');
        }
        die(json_encode($success));
    }
    
    /**
     * 配置空操作,如果用户随意访问就返回错误数据
     * 
     * @return json
     * @author xiaoyutab<xiaoyutab@qq.com>
     * @version v1.0.0
     * @copyright (c) 2017, xiaoyutab
     * @adtime 2017-09-04 22:05:44
     */
    public function _empty(){
        return $this->returnCode('S0001');
    }
}
