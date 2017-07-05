<?php

/**
 * 亿美软通短信发送设置
 * @category   ORG
 * @package  ORG
 * @subpackage  Yimei
 * @author    xiaoyutab<xiaoyutab@qq.com>
 * @version   v1.0.0
 * 依赖文件：
 *      Org.Net.Curl
 *      Org.Util.Xml
 */

namespace Org\Sms;
use Org\Net\Curl;
use Org\Util\Xml;

class Yimei {

    // 配置亿美软通的帐号
    private $zh = '';
    // 配置亿美软通的密码
    private $pw = '';
    // 默认的配置数组
    private $default_config = array();

    /**
     * 初始化该SDK类
     * @param String $zh 帐号
     * @param String $pw 密码
     * @return $this
     */
    public function __construct($zh = false, $pw = false) {
        if ($zh)
            $this->zh = $zh;
        if ($pw)
            $this->pw = $pw;
        $this->default_config = array(
            'cdkey' => $this->zh,
            'password' => $this->pw,
        );
        return $this;
    }

    // 序列号注册
    public function regist() {
        return $this->curl_content_arr('http://hprpt2.eucp.b2m.cn:8080/sdkproxy/regist.action');
    }
    
    // 注册企业信息的Http接口说明
    public function registdetailinfo(){
        return $this->curl_content_arr('http://hprpt2.eucp.b2m.cn:8080/sdkproxy/querybalance.action');
        // ......开发中
    }
    
    // 注销序列号
    public function logout(){
        return $this->curl_content_arr('http://hprpt2.eucp.b2m.cn:8080/sdkproxy/logout.action');
    }
    
    /**
     * 发送即时短信
     * @param type $phone 手机号
     * @param type $message 短信内容（UTF-8编码）（最多500个汉字或1000个纯英文）。
     * @param type $addserial 附加号（最长10位，可置空）
     * @param type $seqid 长整型值企业内部必须保持唯一，获取状态报告使用
     * @param type $smspriority 短信优先级1-5
     * @return type
     */
    public function sendsms($phone = '',$message = '',$addserial = '',$seqid = '',$smspriority = 1){
        return $this->curl_content_arr('http://hprpt2.eucp.b2m.cn:8080/sdkproxy/sendsms.action',array(
            'phone' => $phone,
            'message' => $message,
            'addserial' => $addserial,
            'seqid' => $seqid,
            'smspriority' => $smspriority,
        ));
    }

    public function getBalance(){
        return $this->curl_content_arr('http://hprpt2.eucp.b2m.cn:8080/sdkproxy/querybalance.action');
    }
    
    /**
     * 根据地址获取详细信息
     * @param type $url
     * @param type $canshu
     * @param type $def
     * @return type
     */
    public function curl_content_arr($url,$canshu = array(),$def = '?'){
        $canshu = array_merge($this->default_config,$canshu);
        $url = $this->get_url($url,$canshu,$def);
        $xml = $this->get($url);
        return $this->xml_arr($xml);
    }

    /**
     * 获取get请求
     * @param type $url
     * @return type
     */
    public function get($url){
        $curl = new Curl();
        $data = $curl->get($url);
        return $data;
    }
    
    /**
     * 拼接GET网址参数
     * @param type $url 初始参数
     * @param type $data 传入的数组
     * @param type $first 第一个连接符，默认？
     * @return string
     */
    private function get_url($url = '',$data = array(),$first = '?'){
        $key = 0;
        if(empty($data)){
            $data = $this->default_config;
        }
        foreach ($data as $k => $v){
            if($key == 0){
                $url .= $first . $k . '=' . $v;
            } else {
                $url .= '&'.$k.'='.urlencode($v);
            }
            $key ++;
        }
        return $url;
    }
    
    /**
     * PHP中的XML转数组
     * @param type $xml
     * @return type
     */
    private function xml_arr($xml){
        $xmls = new Xml();
        return $xmls->xmltoarray($xml);
    }
}
