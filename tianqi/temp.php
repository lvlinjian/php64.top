<?php
/**
 * Name:某天气预报数据表字段占用字节统计 - 草稿纸
 * By: php64.top - 油果
 * @date    2018-01-08 10:56:41
**/
header("content-type:text/html;charset=UTF-8");
## 以下调用官方提供的 php - json 代码段
function nowapi_call($a_parm){
    if(!is_array($a_parm)){
        return false;
    }
    //combinations
    $a_parm['format']=empty($a_parm['format'])?'json':$a_parm['format'];
    $apiurl=empty($a_parm['apiurl'])?'http://api.k780.com/?':$a_parm['apiurl'].'/?';
    unset($a_parm['apiurl']);
    foreach($a_parm as $k=>$v){
        $apiurl.=$k.'='.$v.'&';
    }
    $apiurl=substr($apiurl,0,-1);
    if(!$callapi=file_get_contents($apiurl)){
        return false;
    }
    //format
    if($a_parm['format']=='base64'){
        $a_cdata=unserialize(base64_decode($callapi));
    }elseif($a_parm['format']=='json'){
        if(!$a_cdata=json_decode($callapi,true)){
            return false;
        }
    }else{
        return false;
    }
    //array
    if($a_cdata['success']!='1'){
        echo $a_cdata['msgid'].' '.$a_cdata['msg'];
        return false;
    }
    return $a_cdata['result'];
}

$nowapi_parm['app']='weather.city';
$nowapi_parm['appkey']='30894';
$nowapi_parm['sign']='d1348e1b054ef4b44247f2d9513adb72';
$nowapi_parm['format']='json';
$result=nowapi_call($nowapi_parm);

// echo "<pre>";
// var_dump($result);
// print_r($count);

## 以下开始对字段进行字节统计
$count=array();// 临时数组，做统计用的

// # 取 日期 最大值的字节
// echo "<hr>";
// $bbb='星期日';
// echo strlen($bbb),'<hr>';// 9字节

// ## 取最长的 城市ID
// echo "<pre>";
// foreach ($result as $key => $value) {
// 	$count[]=strlen($value['cityid']);
// 	if(strlen($value['cityid']) > 9 || strlen($value['cityid']) < 9){ // null
// 		var_dump($value['cityid']);
// 	}
// }
// echo "<hr>";
// echo max($count); // 9字节：101010100
// ## -----------

// ## 取最长的 城市名称的拼音
// echo "<pre>";
// foreach ($result as $key => $value) {
// 	$count[]=strlen($value['cityno']);
// 	if(strlen($value['cityno']) > 15){
// 		var_dump($value['cityno']);
// 	}
// }
// echo "<hr>";
// echo max($count); // 19字节：chahaeryouyizhongqi
// ## -----------

// ## 取最长的 城市名称
// echo "<pre>";
// foreach ($result as $key => $value) {
// 	$count[]=strlen($value['citynm']);
// 	if(strlen($value['citynm']) > 15){
// 		var_dump($value['citynm']);
// 	}
// }
// echo "<hr>";
// echo max($count);// 21字节:鄂尔多斯鄂前旗
// ## -----------

// # 取 今日气温 最大值的字节
// echo "<hr>";
// $bbb='-14℃/-15℃';
// echo strlen($bbb),'<hr>';// 13字节

// # 取 动态气温 最大值的字节
// echo "<hr>";
// $bbb='-14℃';
// echo strlen($bbb),'<hr>';// 6字节

// # 取 相对湿度 最大值的字节
// echo "<hr>";
// $bbb='100%';
// echo strlen($bbb),'<hr>';// 4字节

// ## 取 今日天气 最大值的字节
// echo "<hr>";
// $bbb='大暴雨-特大暴雨转雷阵雨有冰雹';
// echo strlen($bbb),'<hr>';// 43字节

// ## 取 动态天气 最大值的字节
// echo "<hr>";
// $bbb='大暴雨-特大暴雨';
// echo strlen($bbb),'<hr>';// 22字节

// ## 取 url 最大值的字节
// echo "<hr>";
// $bbb='http://api.k780.com/upload/weather/d/0.gif';
// echo strlen($bbb),'<hr>';// 42字节 给varchar 55

// ## 取 风力 最大值的字节
// echo "<hr>";
// $bbb='十二级';
// echo strlen($bbb),'<hr>';// 9字节

// ## 取 时间戳 最大值的字节
// echo "<hr>";
// $bbb=round(microtime(true),0);
// echo strlen($bbb),'<hr>';// 10字节

// ## 最后一些细节
// 风向不明确，给varchar20
// （最高温度、最低温度、当前温度）都是两位数数值型，即使带上“-”号，用TINYINT(1)也够用了
