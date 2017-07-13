<?php
namespace Home\Controller;

use Org\Office\Word\PHPWord;
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
        $jp = new \Org\SDK\JPush\Client('46af5188a106754675cc19d5', '72bcb04b4e8d74faafae9f56',RUNTIME_PATH . 'SDK/Jplus.log');
        dump($jp);
        $result = $jp->push()
        ->setPlatform('all')
        ->addAllAudience()
        ->setNotificationAlert("这是测试的推送")
        ->send();
        dump($result);
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
        $PHPWord->setDefaultFontSize(16);
        // 添加一个页面并设置相关属性
        $section = $PHPWord->createSection();
        // 获取页脚属性，可以使用$footer->xxx来进行设置相关信息
        $footer = $section->createFooter();
        $footer->addPreserveText('{PAGE} / {NUMPAGES}',array(
            'italic' => true,// 使用斜体
            'color' => 'cccccc',// 灰色字体
            'align' => 'right', // 居中对齐
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
}