<?php
/**
 * Class Name:响应时间
 * Version: 1.0
 * By: php64.top - 油果
 * @date    2017-12-20
 * 描述：基本原理的实现
 * 使用方法：stime和etime中间包着脚本
**/
header("content-type:text/html;charset=UTF-8");
$stime=microtime(true);// 获取脚本开始时间戳
echo "这里是首页！<br>time:";
$etime=microtime(true);// 获取脚本执行结束时间戳
echo round(($etime-$stime),3);// 计算并输出结果





