<?php

namespace Org\Office\Excel;
class Excel_csv{

//导出csv文件
  public function put_csv($list,$title){
    $file_name="CSV".date("mdHis",time()).".csv";
    header ( 'Content-Type: application/vnd.ms-excel' );
    header ( 'Content-Disposition: attachment;filename='.$file_name );
    header ( 'Cache-Control: max-age=0' );
    $file = fopen('php://output',"a");
    $limit=1000;
    $calc=0;
    foreach ($title as $v){
      $tit[]=iconv('UTF-8', 'GB2312//IGNORE',$v);
    }
    fputcsv($file,$tit);
    foreach ($list as $v){
      $calc++;
      if($limit==$calc){
        ob_flush();
        flush();
        $calc=0;
      }
      foreach ($v as $t){
        $tarr[]=iconv('UTF-8', 'GB2312//IGNORE',$t);
      }
      fputcsv($file,$tarr);
      unset($tarr);
    }
    unset($list);
    fclose($file);
    exit();
  }
}

// 使用方法
// $csv=new Csv();
// $list=M("members")->field($field)->limit(10000)->select();//查询数据，可以进行处理
// $csv_title=array('用户ID','用户名','绑定邮箱','绑定手机','注册时间','注册IP');
// $csv->put_csv($list,$csv_title);