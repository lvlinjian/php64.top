<?php
/**
 * 油果
 * Plugin Name: 响应时间
 * Version: 1.0
 * Description: 显示网页响应耗时
 * 实例
 * $stime =xytime();//记录脚本开始时间
 * usleep(5000);//以指定的微秒数延缓程序的执行。
 * $etime =xytime();//结束时间
 * $time3 =substr(($etime - $stime),0,5);//截取5位
 * echo $time3;//输出到页面上
 */
function xytime()
{
	$time1=microtime(true);//返回时间戳+微秒值
	$time2=substr($time1,8,6);//从第8个字符串开始截取6个
	return $time2;
}
function xytime_res($stime =0,$etime =0)
{
	$time3 ="&nbsp;效率:&nbsp;".substr(($etime - $stime),0,5)."&nbsp;秒";
	return $time3;
}
?> 
