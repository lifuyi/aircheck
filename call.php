<?php

/*
* 当有请求来时
* 比对时间
* 代理的抓取时间
* 请求时间和抓取时间相减
* 如果大于一定值，去抓新数据
* 如果小于一定值，代理发出数据
*/

$request_time = $_REQUEST['requesttime'];

$filecontent_string = getJsonStringFromFile("rates.json");
$arr = json_decode($filecontent_string);
$last_refresh_time = $arr->timestamp;

if (($request_time - $last_refresh_time) > 3600 )  { //代理的汇率已经是超过一小时前的了
  refreshRateAndSaveToFile(); //保存一份在代理
  $filecontent_string = getJsonStringFromFile("rates.json");
}
echo $filecontent_string;

function refreshRateAndSaveToFile(){
   // add  for file purpose
   $ch=curl_init('http://www.apilayer.net/api/live?access_key=1ecc8d0c880c87c5133478b0b43b2c93');
   $downloadPathName='rates.json';
   $downloadPath='.';
   $fp=fopen($downloadPathName,'wb') or die('open failed!'); //新建或打开文件,将curl下载的文件写入文件
   curl_setopt($ch,CURLOPT_FILE,$fp);
   curl_exec($ch);
   // close curl resource to free up system resources
   fclose($fp);
   curl_close($ch);
 }

 function getJsonStringFromFile($filename){
    $json_string = file_get_contents($filename);//读取json内容
    return $json_string;
 }

?>
