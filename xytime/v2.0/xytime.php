<?php
/**
 * Class Name:响应时间 - 最终单例类/精简/纯净版
 * Version: 2.0
 * By: php64.top - 油果
 * @date    2017-12-21 20:00:00
 * 描述:1、简单实用！巧妙运用类的构造、析构方法
 * 		2、实现自动结算执行脚本所消耗的时间。
 * 使用方法：加载当前文件到任意PHP文件的头部即可生效！
 * 温馨提示：位置越靠前，取值越精确！(require or include)
**/
header("content-type:text/html;charset=UTF-8");
$xytime =xytime::newtime();// 响应时间开关，注释本行就能禁用
final class xytime{
	private $stime =null;// 记录脚本开始执行的当前时间(精确到微秒)
	private static $abc = null;// 私有 +1
	private function __clone(){}// 私有 +1
## 任务1：动态获取脚本开始执行的时间！
	private function __construct()// 私有 +1
	{//构造方法：一但实例化，则初始化
		$stime=microtime(true);// 获取当前时间戳+微秒值
		$this->stime =$stime;
	}
## 任务2：动态获取脚本执行结束的时间！
	function __destruct()
	{//析构方法：脚本执行结束后，自动触发
			$etime =microtime(true);
			$res ="<br />效率：".round(($etime - $this->stime), 3)."秒";// 浮点四舍五入
			echo $res;// 不能用 return
	}
## 公有的 静态的 New Obj 
	public static function newtime()//公有 +1
	{
		if(static::$abc instanceof static == false)
			{
				static::$abc =new static();
			}
			return static::$abc;
	}
}

// unset($xytime);// 经过验证，unset无效，因为是静态的new obj,下一个版本已经优化