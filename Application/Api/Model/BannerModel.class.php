<?php
namespace Api\Model;
use Think\Model;
use Api\Model\FileModel;

/**
 * 广告模型
 * 
 * @author xiaoyutab<xiaoyutab@qq.com>
 * @version v1.0.0
 * @copyright (c) 2017, xiaoyutab
 * @adtime 2017-09-10 23:05:30
 */
class BannerModel extends Model{
    /**
     * 获取Banner详情
     * 
     * @param intval $p0 开始条数
     * @param intval $p1 查询条数
     * @param intval $type 获取类型,1手机
     * @param string $order 排序类型,根据Order字段大小进行排序,允许值:ASC、DESC
     * @return boolean|array 查询结果
     *      id (主键)	广告编号
     *      title	Banner标题
     *      file_id	图片文件编号
     *      href	跳转链接
     *      type	类型，1手机
     *      order	排序字段
     *      is_target	是否新窗口打开，0否1是
     *      ad_time	添加时间,时间戳
     *      file_info	图片详情
     *      file_info.id	文件编号
     *      file_info.ext	文件后缀名称
     *      file_info.key	文件类型关键字，如果是图片则为image
     *      file_info.md5	文件的md5值，防止重复文件上传
     *      file_info.sha1	文件的sha1值，防止文件重复上传
     *      file_info.size	文件大小，单位：B
     *      file_info.type	文件类型，图片为：image/jpeg
     *      file_info.file_path	文件在服务器上的存储路径，相对路径。前面需要追加网站上传文件根目录
     *      file_info.thumb_100	100x100像素的缩略图位置
     *      file_info.thumb_200	200x200像素的缩略图位置
     * @author xiaoyutab<xiaoyutab@qq.com>
     * @version v1.0.0
     * @copyright (c) 2017, xiaoyutab
     * @adtime 2017-09-10 23:26:50
     */
    public function get_banner_list($p0 = 0,$p1 = 20,$type = 1,$order = 'DESC'){
        if(!in_array($order, array('DESC','desc','ASC','asc'))){
            return FALSE;
        }
        if(!in_array($type, array(1))){
            return FALSE;
        }
        $file = new FileModel();
        $list = $this
                ->where(array('type'=> intval($type),'status'=>array('neq','98')))
                ->order('`order` '.$order)
                ->limit(intval($p0), intval($p1))
                ->select();
        foreach ($list as $k => $v){
            $list[$k]['file_info'] = $file->get_file_info($v['file_id']);
            unset($list[$k]['del_time']);
            unset($list[$k]['status']);
        }
        return $list;
    }
}