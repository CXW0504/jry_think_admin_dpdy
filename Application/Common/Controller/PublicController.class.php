<?php

namespace Common\Controller;

use Think\Controller;
use Think\Upload;
use Org\Office\Excel\PHPExcel;

class PublicController extends Controller {

    protected $title = '';
    protected $css = array();
    protected $css_footer = array();
    protected $js = array();
    protected $js_footer = array();
    protected $author = '';
    protected $version = '';
    protected $key = '';
    protected $describe = '';
    protected $icon = '';
    protected $wget = array();

    public function __construct() {
        parent::__construct();
        $this->title = C('DEFAULT_TITLE', null, '欢迎使用'); // 配置默认标题，如果空就返回欢迎使用
        $this->icon = 'icon.png';
        $this->author = C('WEB_AUTHOR', null, '于茂敬'); // 初始化应用的作者
        $this->version = C('WEB_EDITION', null, '1.0.0'); // 初始化应用的版本
        $this->key = C('DEFAULT_KEY', null, 'xiaoyu,xiaoyutab,小鱼入水'); // 初始化页面关键词
        $this->describe = C('DEFAULT_DESCRIBE', null, '本页面由作者 xiaoyutab 修改的框架自动生成，联系方式为xiaoyutab@qq.com'); // 初始化页面描述
    }

    /**
     * 导出Excel表格操作
     * @param  string $expTitle     居中显示的标题名字
     * @param  array  $expCellName  显示的表格标题
     *                              eg：array(array('id','账号序列'),array('name','账号名字'));
     * @param  array  $expTableData 表格显示的内容数据
     *                              eg：array(array('id'=>1,'name'=>'张三'),array('id'=>2,'name'=>'李四'));
     * @return void                 excel表格，.xls格式的
     */
    public function exportExcel($expTitle, $expCellName, $expTableData, $fileName = 'MyExcel') {
        $xlsTitle = iconv('utf-8', 'gb2312', $expTitle); //文件名称
        $fileName = $fileName . '_' . date('YmdHis') . rand(100, 999); //or $xlsTitle 文件名称可根据自己情况设定
        $cellNum = count($expCellName);
        $dataNum = count($expTableData);

        vendor("PHPExcel.PHPExcel");

        $objPHPExcel = new PHPExcel();
        $cellName = array('A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z', 'AA', 'AB', 'AC', 'AD', 'AE', 'AF', 'AG', 'AH', 'AI', 'AJ', 'AK', 'AL', 'AM', 'AN', 'AO', 'AP', 'AQ', 'AR', 'AS', 'AT', 'AU', 'AV', 'AW', 'AX', 'AY', 'AZ');

        //合并单元格并居中
        $objPHPExcel->getActiveSheet(0)->mergeCells('A1:' . $cellName[$cellNum - 1] . '1')->getStyle('A1')->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A1', $expTitle);

        for ($i = 0; $i < $cellNum; $i++) {
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue($cellName[$i] . '2', $expCellName[$i][1]);
        }
        // Miscellaneous glyphs, UTF-8
        for ($i = 0; $i < $dataNum; $i++) {
            for ($j = 0; $j < $cellNum; $j++) {
                $objPHPExcel->getActiveSheet(0)->setCellValue($cellName[$j] . ($i + 3), $expTableData[$i][$expCellName[$j][0]]);
            }
        }
        header('pragma:public');
        header('Content-type:application/vnd.ms-excel;charset=utf-8;name="' . $xlsTitle . '.xls"');
        header("Content-Disposition:attachment;filename={$fileName}.xls"); //attachment新窗口打印inline本窗口打印
        $objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
        $objWriter->save('php://output');
        exit;
    }

    /**
     * 设置网站的UI需要的插件
     * @param  string $name 插件名称
     * @return object       当前对象
     * 备注：相应插件需要配置在Common/Conf/config.php文件中
     */
    private function _wget() {
        $cconfig = C('SHOW_WGET_LIST');
        foreach($this->wget as $name){
            if (isset($cconfig[$name])) {
                isset($cconfig[$name]['css']) && $this->css($cconfig[$name]['css']);
                isset($cconfig[$name]['js']) && $this->js($cconfig[$name]['js']);
            }
        }
        return $this;
    }
    
    /**
     * 设置页面的WGET挂件列表，即页面中需要哪些插件
     * @param string $value 插件名称
     * @return object 当前对象
     */
    public function wget($value = '') {
        if (!empty($value)) {
            is_string($value) && $this->wget[] = trim($value);
            is_array($value) && $this->wget = array_merge($this->wget, $value);
        }
        return $this;
    }

    /**
     * 上传图片操作
     * @param string $value 表单域名称，$_FILES['avatar']就填写avatar
     * @param string $path  保存文件夹名称。在IMAGE_SAVE_PATH配置项文件夹名后再建立一个文件夹
     * @return array 上传文件的信息
     */
    public function UploadOne($value = '', $path = 'image') {
        if (empty($_FILES[$value])) {
            return false;
        }
        $upload = new Upload(array(
            'rootPath' => C('IMAGE_SAVE_PATH', null, './Public/Upload/'), // 设置上传的ROOT目录所在，默认在Public下的Upload目录
        ));
        $upload->maxSize = intval(C('IMG_maxSize', null, 2) * 1024 * 1024); //设置上传附件大小
        $upload->exts = C('IMG_exts', null, array('jpg', 'gif', 'jpeg', 'png')); //设置上传附件类型
        $upload->savePath = $path . '/' . date('Y-m-d') . '/';
        //上传时指定一个要上传的图片的名称,否则会把表中所有的图片都处理,
        $info = $upload->upload(array($value => $_FILES[$value]));
        if (empty($info)) {
            return $upload->getError();
        }
        return $info;
    }

    /**
     * 生成缩略图
     *       备注：生成缩略图所在目录为$file文件所在目录
     * @param string $file 文件所在位置，配置项IMAGE_SAVE_PATH所配置文件夹后的文件
     * @param array $date 缩略图大小数组。格式为[[100,100],[50,50]]
     * @param booler $thumbRemoveOrigin 是否删除原图，默认不删除false
     * @return array 缩略图文件数组，格式为[['file_path'=>'xxxxx','status'=>true],[...]]
     */
    public function thumb($file = '', $date = array(array(100, 100), array(120, 120)), $thumbRemoveOrigin = false) {
        if (empty($file)) {
            return false;
        }
        $image = new Image();
        $returns = array();
        foreach ($date as $k => $v) {
            $file_path = C('IMAGE_SAVE_PATH', null, './Public/Upload/') . dirname($file) . '/thumb_' . $v[0] . 'x' . $v[1] . '_' . basename($file);
            //打开要处理的图片
            $image->open(C('IMAGE_SAVE_PATH', null, './Public/Upload/') . $file);
            $image->thumb($v[0], $v[1], Image::IMAGE_THUMB_FIXED)->save($file_path);
            $returns[] = array(
                'file_path' => $file_path,
                'status' => is_file($file_path)
            );
        }
        if ($thumbRemoveOrigin) {
            unlink(C('IMAGE_SAVE_PATH', null, './Public/Upload/') . $file);
        }
        return $returns;
    }

    /**
     * 设置页面的JS文件位置，Public文件夹后面的样式表链接，不带.js
     * @param string $value 链接文件的位置
     * @return object 当前对象
     */
    public function js($value = '') {
        if (!empty($value)) {
            if (is_string($value))
                $this->js[] = trim($value);
            if (is_array($value))
                $this->js = array_merge($this->js, $value);
        }
        return $this;
    }

    /**
     * 设置页面的JS文件位置，Public文件夹后面的样式表链接，不带.js
     * @param string $value 链接文件的位置
     * @return object 当前对象
     */
    public function js_footer($value = '') {
        if (!empty($value)) {
            if (is_string($value))
                $this->js_footer[] = trim($value);
            if (is_array($value))
                $this->js_footer = array_merge($this->js_footer, $value);
        }
        return $this;
    }

    /**
     * 设置页面的CSS样式表位置，Public文件夹后面的样式表链接，不带.css
     * @param string $value 链接文件的位置
     * @return object 当前对象
     */
    public function css($value = '') {
        if (!empty($value)) {
            if (is_string($value))
                $this->css[] = trim($value);
            if (is_array($value))
                $this->css = array_merge($this->css, $value);
        }
        return $this;
    }

    /**
     * 设置页面的CSS样式表位置，Public文件夹后面的样式表链接，不带.css
     * @param string $value 链接文件的位置
     * @return object 当前对象
     */
    public function css_footer($value = '') {
        if (!empty($value)) {
            if (is_string($value))
                $this->css_footer[] = trim($value);
            if (is_array($value))
                $this->css_footer = array_merge($this->css, $value);
        }
        return $this;
    }

    /**
     * 设置网站网页的页面描述
     * @param string $value 要设置的页面描述
     * @return object 当前对象
     */
    public function describe($value = '') {
        if (!empty($value)) {
            $this->describe = trim($value);
        }
        return $this;
    }

    /**
     * 设置网站网页的关键词
     * @param string $value 要设置的关键词
     * @return object 当前对象
     */
    public function key($value = '') {
        if (!empty($value)) {
            $this->key = trim($value);
        }
        return $this;
    }

    /**
     * 设置网站应用版本号
     * @param string $value 要设置的版本号，格式：1.0.0 或者V 1.0.0
     * @return object 当前对象
     */
    public function version($value = '') {
        if (!empty($value)) {
            $this->version = trim($value);
        }
        return $this;
    }

    /**
     * 设置页面的标题
     * @param string $value 要设置的标题
     * @return object 当前对象
     */
    public function title($value = '') {
        if (!empty($value)) {
            $this->title = trim($value);
        }
        return $this;
    }

    /**
     * 设置页面作者，如果不设置就使用默认作者
     * @param string $value 要设置的作者
     * @return object 当前对象
     */
    public function author($value = '') {
        if (!empty($value)) {
            $this->author = trim($value);
        }
        return $this;
    }

    /**
     * 输出内容文本可以包括Html 并支持内容解析
     * @param string $content 输出内容
     * @param string $charset 模板输出字符集
     * @param string $contentType 输出类型
     * @param string $prefix 模板缓存前缀
     * @return mixed
     */
    public function display($templateFile = '', $charset = '', $contentType = '', $content = '', $prefix = '') {
        $this->_wget()->assign(array(
            '__a_title__' => $this->title,
            '__a__css__' => $this->css,
            '__a_js__' => $this->js,
            '__a__css_footer__' => $this->css_footer,
            '__a_js_footer__' => $this->js_footer,
            '__a_author__' => $this->author,
            '__a_version__' => $this->version,
            '__a_key__' => $this->key,
            '__a_describe__' => $this->describe,
            '__a_icon__' => $this->icon,
        ));
        parent::display($templateFile, $charset, $contentType, $content, $prefix);
    }

}
