<?php
namespace Home\Controller;

use Org\Office\Word\PHPWord;
use Org\Office\Excel\PHPExcel;
use Think\Controller;
/**
 * 网站首页信息
 * 
 * @author xiaoyutab<xiaoyutab@qq.com>
 * @version v1.0.2
 * @copyright (c) 2017, xiaoyutab
 * @adtime 2017-06-30 10:33:57
 */
class IndexController extends \Common\Controller\PublicController{
    /**
     * 构造函数类
     * 
     * @author xiaoyutab<xiaoyutab@qq.com>
     * @copyright (c) 2017, xiaoyutab
     * @adtime 2017-06-30 10:34:08
     */
    public function __construct() {
        $this->wget(array('jquery'));
        parent::__construct();
    }
    
    /**
     * 网站首页信息
     * 
     * @return void
     * @author xiaoyutab<xiaoyutab@qq.com>
     * @copyright (c) 2017, xiaoyutab
     * @adtime 2017-06-30 10:35:03
     */
    public function indexAction(){
        // 测试极光推送消息
        // 帐号 actine@foxmail.com
        $jp = new \Org\SDK\JPush\Client('2988ee3f7318357e9d916434', '2a3989136b283974d16f7f0a',RUNTIME_PATH . 'SDK/Jplus.log');
//        $result = $jp->push()
//        ->setPlatform('ios')
////        ->addRegistrationId('190e35f7e072448b2ef') // android设备虚拟机
//        ->addRegistrationId('191e35f7e0724023b6a')
//        ->setNotificationAlert("这是测试的苹果推送")
//        ->send();
//        dump($result);
//array(3) {
//  ["body"] => array(2) {
//    ["sendno"] => string(5) "91759"
//    ["msg_id"] => string(10) "1923758674"
//  }
//  ["http_code"] => int(200)
//  ["headers"] => array(10) {
//    [0] => string(15) "HTTP/1.1 200 OK"
//    ["Server"] => string(5) "nginx"
//    ["Date"] => string(29) "Tue, 18 Jul 2017 03:56:22 GMT"
//    ["Content-Type"] => string(16) "application/json"
//    ["Transfer-Encoding"] => string(7) "chunked"
//    ["Connection"] => string(10) "keep-alive"
//    ["X-Rate-Limit-Limit"] => string(3) "600"
//    ["X-Rate-Limit-Remaining"] => string(3) "598"
//    ["X-Rate-Limit-Reset"] => string(2) "49"
//    ["X-JPush-MsgId"] => string(10) "1923758674"
//  }
//}
    	return $this->display();
    }

    /**
     * 测试生成接口文档[程序直接生成]
     * @return void
     */
    public function get_docAction(){
        // 创建doc对象
        $PHPWord = new PHPWord();
        // 获取文档属性
        $properties = $PHPWord->getProperties();
        // 设置文档的作者
        $properties->setCreator('于茂敬');
        // 所属公司
        $properties->setCompany('个人');
        // 文档标题
        $properties->setTitle('API接口文档');
        // 描述
        $properties->setDescription('第一次用程序写的Word文档，而且还是毕竟复杂的接口文档'); 
        // 分类
        $properties->setCategory('接口文档');
        $properties->setLastModifiedBy('于茂敬');
        // 创建时间
        $properties->setCreated( strtotime('2017-07-13 23:35:33') );
        // 最后修改时间
        $properties->setModified( strtotime('2017-07-13 23:35:33') );
        // 主题
        $properties->setSubject('API接口文档');
        // 关键词
        $properties->setKeywords('API,接口文档,于茂敬');
        // 设置文档默认字体
        $PHPWord->setDefaultFontName('微软雅黑');
        // 设置文档默认字体大小，单位px
        $PHPWord->setDefaultFontSize(12);
        // 添加一个页面并设置相关属性
        $section = $PHPWord->createSection();
        // 获取页脚属性，可以使用$footer->xxx来进行设置相关信息
        $footer = $section->createFooter();
        $footer->addPreserveText('{PAGE} / {NUMPAGES}',array(
            'italic' => true,// 使用斜体
            'color' => 'c4c4c4',// 灰色字体
        ),array(
            'align' => 'center',
        ));
        $section->addText('Hello world!');
        $section->addText('Hello world! I am formatted.世界你好', array(
            'name'=>'Tahoma', 
            'size'=>16, 
            'bold'=>true,
            'align' => 'right',
        ));
        $PHPWord->addFontStyle('myOwnStyle', array('name'=>'Verdana', 'size'=>14, 'color'=>'1B2232'));
        $section->addText('Hello world! I am formatted by a user defined style');
        $section->addImage( trim('./icon.png'),array(
            '_align' => 'center',
        ));
        $myTextElement = $section->addText('Hello World!' ,array('name'=>'Tahoma', 'size'=>110, 'bold'=>true));

        $objWriter = \PHPWord_IOFactory::createWriter($PHPWord, 'Word2007');
        $temp_runtime = 'Public/Runtime/temp_run.doc';
        $objWriter->save($temp_runtime);
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename="helloWorld.docx"');  
        header('Content-Transfer-Encoding: binary');  
        // load the file to send:  
        readfile($temp_runtime);
    }
    
    /**
     * 下载HelloWord.doc文档
     */
    public function getWordAction(){
        $PHPWord = new PHPWord();
        $properties = $PHPWord->getProperties();
        $properties->setCreator('My name'); 
        $properties->setCompany('My factory');
        $properties->setTitle('My title');
        $properties->setDescription('My description'); 
        $properties->setCategory('My category');
        $properties->setLastModifiedBy('My name');
        $properties->setCreated( mktime(0, 0, 0, 3, 12, 2010) );
        $properties->setModified( mktime(0, 0, 0, 3, 14, 2010) );
        $properties->setSubject('My subject'); 
        $properties->setKeywords('my, key, word');

        $PHPWord->setDefaultFontName('微软雅黑');
        $PHPWord->setDefaultFontSize(16);
        $section = $PHPWord->createSection();
        $section->addText('Hello world!');
        $section->addText('Hello world! I am formatted.世界你好', array('name'=>'Tahoma', 'size'=>16, 'bold'=>true));
        $PHPWord->addFontStyle('myOwnStyle', array('name'=>'Verdana', 'size'=>14, 'color'=>'1B2232'));
        $section->addText('Hello world! I am formatted by a user defined style');
        $section->addImage( trim('./icon.png'));
        $myTextElement = $section->addText('Hello World!' ,array('name'=>'Tahoma', 'size'=>110, 'bold'=>true));

        $objWriter = \PHPWord_IOFactory::createWriter($PHPWord, 'Word2007');
        $temp_runtime = 'Public/Runtime/temp_run.doc';
        $objWriter->save($temp_runtime);
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename="helloWorld.docx"');  
        header('Content-Transfer-Encoding: binary');  
        // load the file to send:  
        readfile($temp_runtime);
    }
    
    /**
     * 导出PDF文件
     */
    public function getPdfAction(){
        define('K_PATH_IMAGES', './');
        $pdf = new \Org\Office\PDF\tcpdf('P', 'mm', 'A4', true, 'UTF-8', false);
        $pdf->SetCreator('xiaoyutab.cn');
        $pdf->SetAuthor('于茂敬');

        // 设置页眉和页脚信息 
        $pdf->SetHeaderData('icon.png', 10, 'xiaoyutab.cn', '始终相信Linux系统可以解决网络上99%的问题',  
              array(0,64,255), array(0,64,128));
        $pdf->setFooterData(array(0,64,0), array(0,64,128)); 

        // 设置页眉和页脚字体 
        $pdf->setHeaderFont(Array('cid0ct', '', '10')); 
        $pdf->setFooterFont(Array('helvetica', '', '8')); 

        // 设置默认等宽字体 
        $pdf->SetDefaultMonospacedFont('courier'); 

        // 设置间距 
        $pdf->SetMargins(15, 27, 15); 
        $pdf->SetHeaderMargin(5); 
        $pdf->SetFooterMargin(10); 
        // 设置分页 
        $pdf->SetAutoPageBreak(TRUE, 25); 
        // set image scale factor 
        $pdf->setImageScale(1.25); 
        // set default font subsetting mode 
        $pdf->setFontSubsetting(true); 
        //设置字体 
        $pdf->SetFont('stsongstdlight', '', 14); 
        $pdf->AddPage(); 
        $pdf->Write(0,'欢迎来到 xiaoyutab.cn','', false, 'L', true, 0, false, false, 0); 
        $pdf->Write(0,'欢迎来到 xiaoyutab.cn','', FALSE, 'L', true, 0, false, false, 0); 
        $pdf->Write(0,'欢迎来到 xiaoyutab.cn','', false, 'L', true, 0, false, false, 0); 
        //输出PDF
        $pdf->Output('测试.pdf', 'I');
    }
}