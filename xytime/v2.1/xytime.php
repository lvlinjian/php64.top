<?php
/**
 * Class Name:响应时间 - 最终单例类/精简/纯净版
 * Version: 2.1
 * By: php64.top - 油果
 * @date    2017-12-29 19:00:00
 * 描述:1、简单实用！巧妙运用类的构造、析构方法
 * 		2、实现自动结算执行脚本所消耗的时间。
 * 使用方法：加载当前文件到任意PHP文件的头部即可生效！
 * 页面布局需求：$xytime =xytime::newtime(传入任意参数即可);
 * 温馨提示：位置越靠前，取值越精确！(require or include)
**/
header("content-type:text/html;charset=UTF-8");
$xytime =xytime::newtime();// 响应时间开关，注释本行就能禁用
final class xytime{
	private $stime =null;// 记录脚本开始执行的当前时间(精确到微秒)
	private static $f1 = null;// 私有 +1
	private function __clone(){}// 私有 +1
## 任务1：动态获取脚本开始执行的时间！
	private function __construct()// 私有 +1
	{//构造方法：一但实例化，则初始化
		$stime=microtime(true);// 获取当前时间戳+微秒值
		$this->stime =$stime;
	}
## 任务2：动态获取脚本执行结束的时间！
	function __destruct()// 析构方法不能传参数
	{//析构方法：脚本执行结束后，自动触发
			$etime =microtime(true);
			$res ="<br />效率：".round(($etime - $this->stime), 3)."秒";// 浮点四舍五入
			echo $res;// 不能用 return
	}
## 公有的 静态的 New Obj 
	public static function newtime($f2=null)//公有 +1
	{
		if(empty($f2)){
			if(static::$f1 instanceof static == false)
				{
					static::$f1 =new static();
				}
			}else{
				static::$f1 =NULL;// 如果$f2传入任意参数则表示销毁对象,并立即触发析构函数，输出计算结果
			}
		return static::$f1;
	}
}
