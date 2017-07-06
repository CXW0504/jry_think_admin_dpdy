<?php
namespace Org\User;
/**
 * 根据用户的HTTP_USER_AGENT值判断用户的系统、浏览器信息
 * @adtime 2017-07-06 15:11:22
 * @copyright (c) 2017, xiaoyutab
 * @author 于茂敬<xiaoyutab@qq.com>
 */
class System {
    /**
     * 获取访问用户的浏览器的信息 
     * @param string $Agent $_SERVER['HTTP_USER_AGENT']
     * @param string $link 浏览器和版本之间的分隔符
     * @return string 获取到的信息
     */
    function get_browser($Agent = '',$link = ' ') {
        empty($Agent) && $Agent = $_SERVER['HTTP_USER_AGENT'];
        $browseragent = ""; //浏览器 
        $browserversion = ""; //浏览器的版本 
        if (ereg('MSIE ([0-9].[0-9]{1,2})', $Agent, $version)) {
            $browserversion = $version[1];
            $browseragent = "Internet Explorer";
        } else if (ereg('Opera/([0-9]{1,2}.[0-9]{1,2})', $Agent, $version)) {
            $browserversion = $version[1];
            $browseragent = "Opera";
        } else if (ereg('Firefox/([0-9.]{1,5})', $Agent, $version)) {
            $browserversion = $version[1];
            $browseragent = "Firefox";
        } else if (ereg('Chrome/([0-9.]{1,5})', $Agent, $version)) {
            $browserversion = $version[1];
            $browseragent = "Chrome";
        } else if (ereg('Safari/([0-9.]{1,3})', $Agent, $version)) {
            $browseragent = "Safari";
            $browserversion = "";
        } else {
            $browserversion = "";
            $browseragent = "Unknown";
        }
        return rtrim($browseragent . $link . $browserversion,'.');
    }

    /**
     * 同理获取访问用户的系统版本信息
     * @param type $Agent
     * @return string
     */
    function get_system($Agent = '') {
        empty($Agent) && $Agent = $_SERVER['HTTP_USER_AGENT'];
        $browserplatform == '';
        if (eregi('win', $Agent) && eregi('nt 6.1', $Agent)) {
            $browserplatform = "Windows 7";
        } elseif (eregi('win', $Agent) && ereg('32', $Agent)) {
            $browserplatform = "Windows 32";
        } elseif (eregi('win', $Agent) && eregi('nt 6.0', $Agent)) {
            $browserplatform = "Windows Vista";
        } elseif (eregi('win', $Agent) && eregi('nt 5.1', $Agent)) {
            $browserplatform = "Windows XP";
        } elseif (eregi('win', $Agent) && eregi('nt 5.0', $Agent)) {
            $browserplatform = "Windows 2000";
        } elseif (eregi('win', $Agent) && eregi('nt', $Agent)) {
            $browserplatform = "Windows NT";
        } elseif (eregi('win', $Agent) && strpos($Agent, '95')) {
            $browserplatform = "Windows 95";
        } elseif (eregi('win 9x', $Agent) && strpos($Agent, '4.90')) {
            $browserplatform = "Windows ME";
        } elseif (eregi('win', $Agent) && ereg('98', $Agent)) {
            $browserplatform = "Windows 98";
        } elseif (eregi('Mac OS', $Agent)) {
            $browserplatform = "Mac OS";
        } elseif (eregi('linux', $Agent)) {
            $browserplatform = "Linux";
        } elseif (eregi('unix', $Agent)) {
            $browserplatform = "Unix";
        } elseif (eregi('sun', $Agent) && eregi('os', $Agent)) {
            $browserplatform = "SunOS";
        } elseif (eregi('ibm', $Agent) && eregi('os', $Agent)) {
            $browserplatform = "IBM OS/2";
        } elseif (eregi('Mac', $Agent) && eregi('PC', $Agent)) {
            $browserplatform = "Macintosh";
        } elseif (eregi('PowerPC', $Agent)) {
            $browserplatform = "PowerPC";
        } elseif (eregi('AIX', $Agent)) {
            $browserplatform = "AIX";
        } elseif (eregi('HPUX', $Agent)) {
            $browserplatform = "HPUX";
        } elseif (eregi('NetBSD', $Agent)) {
            $browserplatform = "NetBSD";
        } elseif (eregi('BSD', $Agent)) {
            $browserplatform = "BSD";
        } elseif (ereg('OSF1', $Agent)) {
            $browserplatform = "OSF1";
        } elseif (ereg('IRIX', $Agent)) {
            $browserplatform = "IRIX";
        } elseif (eregi('FreeBSD', $Agent)) {
            $browserplatform = "FreeBSD";
        }
        if ($browserplatform == '') {
            $browserplatform = "Unknown";
        }
        return $browserplatform;
    }

}
