<?php

/**
 * XML类库操作
 * @category   ORG
 * @package  Utrl
 * @subpackage  Xml
 * @author    xiaoyutab<xiaoyutab@qq.com>
 * @version   v1.0.0
 */

namespace Org\Util;

class Xml {

    /**
     * 最简单的XML转数组
     * @param string $xmlstring XML字符串
     * @return array XML数组
     */
    public function simplest_xml_to_array($xmlstring = '') {
        return json_decode(json_encode((array) simplexml_load_string($xmlstring)), true);
    }

    // Xml 转 数组, 包括根键，忽略空元素和属性，尚有重大错误
    public function xml_to_array($xml) {
        $reg = "/<(\\w+)[^>]*?>([\\x00-\\xFF]*?)<\\/\\1>/";
        if (preg_match_all($reg, $xml, $matches)) {
            $count = count($matches[0]);
            $arr = array();
            for ($i = 0; $i < $count; $i++) {
                $key = $matches[1][$i];
                $val = $this->xml_to_array($matches[2][$i]);  // 递归
                if (array_key_exists($key, $arr)) {
                    if (is_array($arr[$key])) {
                        if (!array_key_exists(0, $arr[$key])) {
                            $arr[$key] = array($arr[$key]);
                        }
                    } else {
                        $arr[$key] = array($arr[$key]);
                    }
                    $arr[$key][] = $val;
                } else {
                    $arr[$key] = $val;
                }
            }
            return $arr;
        } else {
            return $xml;
        }
    }

    // Xml 转 数组, 不包括根键
    public function xmltoarray($xml) {
        $arr = $this->xml_to_array($xml);
        $key = array_keys($arr);
        return $arr[$key[0]];
    }

}
