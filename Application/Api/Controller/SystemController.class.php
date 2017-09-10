<?php
namespace Api\Controller;
use Api\Model\AppModel;
use Api\Model\AppKeyModel;
use Api\Model\BannerModel;

/**
 * 网站系统相关接口
 * 
 * @author xiaoyutab<xiaoyutab@qq.com>
 * @version v1.0.0
 * @copyright (c) 2017, xiaoyutab
 * @adtime 2017-3-6 16:59:21
 */
class SystemController extends ApiController {
    
    /**
     * 获取网站广告列表【首页Banner切换条】
     */
    /**
     * 获取广告接口
     * @return json
     * @author xiaoyutab<xiaoyutab@qq.com>
     * @version v1.0.0
     * @copyright (c) 2017, xiaoyutab
     * @adtime 2017-09-10 23:36:47
     */
    public function get_banner_listAction(){
        $banner = new BannerModel();
        $dat = I('get.');
        // 设置一系列的默认值
        $dat['type'] || $dat['type'] = 1;
        $dat['p0'] || $dat['p0'] = 0;
        $dat['p1'] || $dat['p1'] = 20;
        if(!in_array($dat['type'], array(1))){
            // 如果获取的type编号不在允许列表中,返回数据错误
            return $this->returnCode('F0001');
        }
        $list = $banner->get_banner_list($dat['p0'], $dat['p1'], $dat['type']);
        if(empty($list)){
            return $this->returnCode('R0000');// 如果空数组也返回成功信息,只不过里面的值是空
        }
        $return_list = array();
        foreach($list as $v){
            $return_list[] = array(
                'id' => intval($v['id']),
                'title' => $v['title'].'',
                'href' => $v['href'].'',
                'type' => intval($v['type']),
                'order' => intval($v['order']),
                'is_target' => intval($v['is_target']),
                'ad_time' => date('Y-m-d H:i:s',$v['ad_time']),
                'file_path' => $v['file_info']['file_path'],
                'thumb_100' => $v['file_info']['thumb_100'],
                'thumb_200' => $v['file_info']['thumb_200'],
            );
        }
        return $this->returnCode(array(
            'count' => $banner->get_banner_count($dat['type']),
            'list' => $return_list
        ));
    }

    /**
     * 授权接口,待更新。现功能为任意参数均已授权
     * 
     * return.token 可以直接比较的授权码
     * @author xiaoyutab<xiaoyutab@qq.com>
     * @version v1.0.0
     * @copyright (c) 2017, xiaoyutab
     * @adtime 2017-3-6 16:58:16
     */
    public function doaminAction() {
        return $this->returnCode(array(
            'token' => md5($domain.$other),
        ));
    }

}
