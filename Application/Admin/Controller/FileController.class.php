<?php
namespace Admin\Controller;
use Think\Image;
use Admin\Model\FileModel;

/**
 * 文件相关操作
 * 
 * @author xiaoyutab<xiaoyutab@qq.com>
 * @version v1.0.0
 * @copyright (c) 2017, xiaoyutab
 * @adtime 2017-07-13 20:18:21
 */
class FileController extends CommonController {

    /**
     * 上传头像操作
     * 需要上传参数为：post.avatar_data.x X位置
     * 需要上传参数为：post.avatar_data.y Y位置
     * 需要上传参数为：post.avatar_data.width 宽度
     * 需要上传参数为：post.avatar_data.height 高度
     * 需要上传参数为：file.avatar 文件内容
     * 
     * @author xiaoyutab<xiaoyutab@qq.com>
     * @version v1.0.0
     * @copyright (c) 2017, xiaoyutab
     * @adtime 2017-07-13 20:19:27
     */
    public function uploadAction() {
        $f_model = new FileModel();
        // 将图片上传上去
        $info = $this->UploadOne('avatar','avatar');
        $img = new Image();// 实例化图片类，准备剪裁操作
        $imgs = json_decode($_POST['avatar_data'], true);
        $file = $info['avatar']['savepath'] . $info['avatar']['savename'];// 获取图片地址
        // 剪切成200x200的图片
        $success = $img->open(C('IMAGE_SAVE_PATH') . $file)
                ->crop($imgs['width'], $imgs['height'], $imgs['x'], $imgs['y'])
                ->thumb(200, 200,Image::IMAGE_THUMB_FIXED)
                ->save(C('IMAGE_SAVE_PATH') . dirname($file) . '/crop_200x200_' . basename($file));
        // 删除原图
        unlink(C('IMAGE_SAVE_PATH') . $file );
        if($success){
            die(json_encode(array('id'=>dirname($file) . '/crop_200x200_' . basename($file),'result' => dirname($file) . '/crop_200x200_' . basename($file))));
        }
        return $this->error('剪切图片出错');
    }

}
