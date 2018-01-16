<?php 
## TOP 
echo "<a href='?'>首页</a> - 天气预报<br>";
echo <<<HTML
<title>天气预报</title>
<form action='?a=show'  method='POST'>
城市：<input type='text' name='citynm' />
<input type='submit' value='查询'>
</form>
HTML;
?>
<?php
/**
 * Class Name:天气预报
 * Version: 1.0
 * By: php64.top - 油果
 * @date    2018-01-02 22:50:37
**/
header("content-type:text/html;charset=UTF-8");
define('TEMPTIME', 3600);// 缓存周期(秒)
define('MODEL', xml);// API返回的数据类型(json/xml)
## 控制器
run();
function run(){
	## 核心文件检查
	if(!file_exists('mysqlconf.php')){// 检查 MySQL 配置信息是否存在
		tq_insall();die;
	}elseif(!file_exists('table.txt')){// 检查数据表是否存在
		tq_insall();die;
	}
	// ---- end --------
	$a=empty($_GET['a'])?'index':$_GET['a'];// 可以通过地址栏接收函数指令
	$citynm=empty($_POST['citynm'])?'':$_POST['citynm'];// 这里有个缺陷。不算BUG，如果你改成 $_GET就能看到现象。因为我找不到原因所以，不懂修复！！！
	$obj=$a($citynm);// 可变函数
}
// ----- end --------

function index(){
## 简单的HTML展示
	echo "象征性的告诉你这是<b>首页</b>！";
	echo "<p>By:php64.top</p>";
}

function tq_insall(){
	## 检查配置文件
	if(!file_exists('mysqlconf.php')){
	$data=<<<INSTALL
<?php
\$host='localhost';
\$port='3306';
\$user='root';
\$pass='root';
\$charset='utf8';
\$dbname='tlip_db';
?>
INSTALL;
		if(file_put_contents('mysqlconf.php',$data) == true){
			$aaa =__FILE__;
			$bbb ='mysqlconf.php';
			echo " MySQL 配置文件--------------------写入成功！<a href='?'>下一步</a><br>请您自己手动修改当前文件（{$aaa}）目录下的 {$bbb} 配置文件！<br>";
			die;
		}else{
			echo " MySQL 配置文件--------------------写入失败！<br>或许是您没有权限新建文件！ <a href='?'>再来一次？</a><br>";
			die;
		}
	}
	// ---------- end -------------
	## 检查数据表
	if(!file_exists('table.txt')){
		$db1=new mypdo();
		$sql="DROP TABLE IF EXISTS `tianqi`";
		$res=$db1->exec($sql);//初始化1：删除同名数据表
		if($res===false){
			echo "语法错误 or 您没有权限初始化数据表！";
		}
		## 数据表结构
		$sql="CREATE TABLE `tianqi` (
		  `days` DATE NOT NULL comment '日期',  
		  `week` CHAR(10) COMMENT '星期',
		  `cityId` CHAR(10) NOT NULL COMMENT '城市ID',
		  `cityno` VARCHAR(20) NOT NULL COMMENT '城市拼音',
		  `citynm` VARCHAR(6) NOT NULL COMMENT '城市名称',
		  `temperature` CHAR(10) COMMENT '今日气温',
		  `temperature_curr` CHAR(6) COMMENT '动态气温',
		  `humidity` CHAR(6) COMMENT '湿度',
		  `weather` VARCHAR(6) COMMENT '今日天气',
		  `weather_curr` VARCHAR(6) COMMENT '动态天气',
		  `weather_icon` VARCHAR(45) COMMENT '天气图标',
		  `wind` CHAR(12) COMMENT '风向',
		  `winp` CHAR(5) COMMENT '风力',
		  `temp_high` TINYINT(1) COMMENT '最高温度',
		  `temp_low` TINYINT(1) COMMENT '最低温度',
		  `temp_curr` TINYINT(1) COMMENT '当前温度',
		  `tq_stime` CHAR(10) DEFAULT NULL comment '更新time戳',  
		  PRIMARY KEY (`cityId`),UNIQUE KEY `citynm` (`citynm`)
		) ENGINE=InnoDB DEFAULT CHARSET=utf8;
		";
		$res=$db1->exec($sql);// 创建数据表
		if($res===false){
			echo "写入 数据表----------------------失败！<a href='?'>首页</a><br>";
			echo "<br>####################################<br>请您自行检查，错误原因！<br>友情提示：或许是您的 MySQL 配置文件信息不对，以至于造成 MySQL链接失败！";
			die;
		}else{
			echo "写入 数据表----------------------成功！<a href='?'>首页</a><br>";
			file_put_contents('table.txt','如需初始化数据表，请删除我！');
		}
	}	
	// ---------- end ------------
}

## 查天气预报(缓存优先，然后才是实时)
function show($citynm='北京'){
	$etime=round(microtime(true),0);
	$db1=new mypdo();
	$sql="SELECT * FROM tianqi WHERE 1=1";
	## 城市名称模糊查询
	$sql.=" AND citynm LIKE '%$citynm%'";
	$result =$db1->GetMoreRow($sql);
	if($result===[]){
		// echo "空数组";// 如果数据库里没有缓存
		add($citynm);die;// 则调用add()去API获取数据
	}
	elseif(($etime - ($result[0]['tq_stime'])) > TEMPTIME){
		updata($citynm);die;// 这是修改而不是添加新记录
	}
	## 展示 MySQL 缓存 - 查询结果
echo '日期：'.$result[0]['days'].'<br>';
echo '星期：'.$result[0]['week'].'<br>';
echo '城市ID：'.$result[0]['cityId'].'<br>';
echo '城市拼音：'.$result[0]['cityno'].'<br>';
echo '城市名称：'.$result[0]['citynm'].'<br>';
echo '今日气温：'.$result[0]['temperature'].'<br>';
echo '动态气温：'.$result[0]['temperature_curr'].'<br>';
echo '湿度：'.$result[0]['humidity'].'<br>';
echo '今日天气：'.$result[0]['weather'].'<br>';
echo '动态天气：'.$result[0]['weather_curr'].'<br>';
echo "天气图标：<img src='{$result[0]['weather_icon']}' alt='加载图片失败'><br>";
echo '风向：'.$result[0]['wind'].'<br>';
echo '风力：'.$result[0]['winp'].'<br>';
echo '最高温度：'.$result[0]['temp_high'].'<br>';
echo '最低温度：'.$result[0]['temp_low'].'<br>';
echo '当前温度：'.$result[0]['temp_curr'].'<br>';
}

function add($citynm='北京'){
// ------ XML ------------
$stime=round(microtime(true),0);
$url = "http://api.k780.com:88/?app=weather.today&weaid={$citynm}&appkey=10003&sign=b59bc3ef6191eb9f747dd4e83c99f2a4&format=xml";
$a = file_get_contents($url);
$xml = simplexml_load_string($a);
	if($xml->success == 0){// 不存在的错误城市编号
		echo $xml->msgid,'<br />',$xml->msg,'<br />';
		die;
	}
$arr=array('days','week','cityid','cityno','citynm','temperature','temperature_curr','humidity','weather','weather_curr','weather_icon','wind','winp','temp_high','temp_low','temp_curr');// 
$sql="INSERT INTO `tianqi` SET ";
foreach ($arr as $v) {
$sql.=" `$v` = '{$xml->result->$v}',";// 遍历数组拼接SQL语句
}
$sql.=" `tq_stime` ={$stime}";// 追加写入的时间戳
	$db1=new mypdo();
	$res =$db1->exec($sql);
		if($res===false){
			echo "查询 数据----------------------失败！<a href='?'>首页</a><br>";
			die;
		}else{
			echo '日期：'.$xml->result->days.'<br>';
			echo '星期：'.$xml->result->week.'<br>';
			echo '城市ID：'.$xml->result->cityid.'<br>';
			echo '城市拼音：'.$xml->result->cityno.'<br>';
			echo '城市名称：'.$xml->result->citynm.'<br>';
			echo '今日气温：'.$xml->result->temperature.'<br>';
			echo '动态气温：'.$xml->result->temperature_curr.'<br>';
			echo '湿度：'.$xml->result->humidity.'<br>';
			echo '今日天气：'.$xml->result->weather.'<br>';
			echo '动态天气：'.$xml->result->weather_curr.'<br>';
			echo '天气图标：<img src=\''.$xml->result->weather_icon.'\' alt=\'天气图标\'><br>';
			echo '风向：'.$xml->result->wind.'<br>';
			echo '风力：'.$xml->result->winp.'<br>';
			echo '最高温度：'.$xml->result->temp_high.'<br>';
			echo '最低温度：'.$xml->result->temp_low.'<br>';
			echo '当前温度：'.$xml->result->temp_curr.'<br>';
		}
// ------ XML end ------------
}

function updata($citynm='北京'){
// ------ XML ------------
$stime=round(microtime(true),0);
$url = "http://api.k780.com:88/?app=weather.today&weaid={$citynm}&appkey=10003&sign=b59bc3ef6191eb9f747dd4e83c99f2a4&format=xml";
$a = file_get_contents($url);
$xml = simplexml_load_string($a);
	if($xml->success == 0){// 不存在的错误城市编号
		echo $xml->msgid,'<br />',$xml->msg,'<br />';
		die;
	}
$arr=array('days','week','cityid','cityno','citynm','temperature','temperature_curr','humidity','weather','weather_curr','weather_icon','wind','winp','temp_high','temp_low','temp_curr');// 
$sql="UPDATE `tianqi` SET ";
foreach ($arr as $v) {
$sql.=" `$v` = '{$xml->result->$v}',";// 遍历数组拼接SQL语句
}
$sql.=" `tq_stime` ={$stime}";// 追加写入的时间戳
$sql.=" WHERE `citynm` ='{$citynm}'";// 修改的条件
	$db1=new mypdo();
	$res =$db1->exec($sql);
		if($res===false){
			echo "查询 数据----------------------失败！<a href='?'>首页</a><br>";
			die;
		}else{
			echo '日期：'.$xml->result->days.'<br>';
			echo '星期：'.$xml->result->week.'<br>';
			echo '城市ID：'.$xml->result->cityid.'<br>';
			echo '城市拼音：'.$xml->result->cityno.'<br>';
			echo '城市名称：'.$xml->result->citynm.'<br>';
			echo '今日气温：'.$xml->result->temperature.'<br>';
			echo '动态气温：'.$xml->result->temperature_curr.'<br>';
			echo '湿度：'.$xml->result->humidity.'<br>';
			echo '今日天气：'.$xml->result->weather.'<br>';
			echo '动态天气：'.$xml->result->weather_curr.'<br>';
			echo '天气图标：<img src=\''.$xml->result->weather_icon.'\' alt=\'天气图标\'><br>';
			echo '风向：'.$xml->result->wind.'<br>';
			echo '风力：'.$xml->result->winp.'<br>';
			echo '最高温度：'.$xml->result->temp_high.'<br>';
			echo '最低温度：'.$xml->result->temp_low.'<br>';
			echo '当前温度：'.$xml->result->temp_curr.'<br>';
		}
// ------ XML end ------------
}

###### MyPDO 数据库操作类 #######
class mypdo extends pdo{
	function __construct(){
		require "mysqlconf.php";// host/user/pass...
		$dsn = "mysql:host=$host;port=$port;dbname=$dbname";
		$opt = array(
			PDO::MYSQL_ATTR_INIT_COMMAND=>"set names $charset",
			PDO::ATTR_ERRMODE=>PDO::ERRMODE_WARNING,
			);
		parent::__construct($dsn, $user, $pass, $opt);
	}
	//返回多行数据，是一个二维数组
	function getMoreRow($sql){
		$result = $this->query($sql);//pdostatement对象或false
		if($result === false){
			return false;
		}
		$data = $result->fetchAll(PDO::FETCH_ASSOC);	//关联数组
		return $data;
	}
	//返回单个数据，是一个标量数据
	function getOneData($sql){
		$result = $this->query($sql);
		if($result === false){
			return false;
		}
		$data = $result->fetchColumn();
		return $data;
	}
}
###### MyPDO END #######
?>