<?php
/**
 * Class Name:响应时间 - 精简/动态/纯净/单例/最终
 * Version:2.1@By:php64.top@Date:2017-12-30
 * 描述:1、简单实用！巧妙运用类的构造、析构方法，2、实现自动结算执行脚本所消耗的时间。
 * 使用方法：加载当前文件到任意PHP文件的头部即可生效！
 * 页面排版需求：$xytime =xytime::newtime(可传任意参数);
 * 温馨提示：位置越靠前，取值越精确！(require or include)
**/
$xytime =xytime::newtime();// 响应时间开关，注释本行就能禁用
final class xytime{private $stime;private static $f1;private function __clone(){}private function __construct(){$this->stime=microtime(true);}function __destruct(){echo "time:".round(((microtime(true)-$this->stime)+0.001),3);}public static function newtime($f2=null){if(empty($f2)){if(self::$f1 instanceof self == false){self::$f1 =new self();}}else{self::$f1 =NULL;}return self::$f1;}}