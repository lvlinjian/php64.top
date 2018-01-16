<?php
/**
 * Class Name:array_Mysql_Tool
 * Version: 1.0
 * By: php64.top - 油果
 * @date    2017-12-30 17:22:41
 * 描述：拆分数组成员，挨个写入 MySQL数据库,通过第三方接口快速查手册
 * 第三方接口：http://www.php.net/{$name}
**/
echo <<<MYCSS
<style type="text/css">
*{
margin: 0;padding: 0;
}
table{
	width: 60%;
	color: #111;
	background: #dcffff;
    border-left: 2px solid rgba(200, 200, 200, 0.66);
    border-bottom: 2px solid rgba(200, 200, 200, 0.66);
    border-right: 2px solid rgba(200, 200, 200, 0.66);
    margin: 20px;
}
td,th{
	padding: 3px 5px 3px 5px;
	text-align: center;
    border: 2px solid rgba(200, 200, 200, 0.66);
}
caption{
	margin-bottom: 10px;
}
</style>
MYCSS;
header("content-type:text/html;charset=UTF-8");
require_once "Page.class.php";// 分页类
##开启SESSION服务
session_start();
##初始化参数
run();
###### MyPDO #######
class mypdo extends pdo{
	function __construct(){
		require_once "mysqlconf.php";
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
		$data = $result->fetchColumn();	//可变方法
		return $data;
	}
}
###### MyPDO END #######
function run(){	
	$a=empty($_GET['a'])?'index':$_GET['a'];
	$id=empty($_GET['id'])?'':$_GET['id'];
	$name=empty($_GET['name'])?'':$_GET['name'];
	$zhuShi=empty($_GET['zhuShi'])?'':$_GET['zhuShi'];
	$obj= $a($id,$name,$zhuShi);
	## 记录本次查询的时间戳，加查询频率判断防小人
	$_SESSION['array_time'] =microtime(true);// 在服务器上缓存 time
}

function index($id='',$name='',$zhuShi=''){
	if(!file_exists('mysqlconf.php')){
		insall();die;
	}
	### 分页类的使用 ###
	$str = "<table rules='all'>";
	$str .= '<caption><form action="?a=index&"><h2>【 PHP函数大全 】</h2><br />';
	$str .= "编号:<input type='text' name='id' value='$id' />";
	$str .= "名称:<input type='text' name='name' value='$name' />";
	$str .= "&ensp;注释:<input type='text' name='zhuShi' value='$zhuShi' />";
	$str .= '<input type="submit" value="模糊查询"></form></caption>';
	$str .= "<tr><th>ID</th><th>名称</th><th>注释</th><th>手册</th></tr>";
		if(empty($_GET)){
			$str .= "<tr><td>NULL</td><td>NULL</td><td>NULL</td><td>NULL</td></tr>";
			die($str);
		}
		elseif((microtime(true) - @$_SESSION['array_time']) < 3){
			$str .= "<tr><td>404</td><td>请减速</td><td>1条/3秒</td><td>查询语句</td></tr>";
			die($str);
		}
	$db1=new mypdo();
	$sql1="SELECT count(*) FROM PHPCD WHERE 1=1";// 统计查询结果总行数
	if(!empty($_GET['id'])){
		$sql1.=" AND id LIKE '%$id%'";
	}
	if(!empty($_GET['name'])){
		$sql1.=" AND name LIKE '%$name%'";
	}
	if(!empty($_GET['zhuShi'])){
		$sql1.=" AND zhuShi LIKE '%$zhuShi%'";
	}
	$total = $db1->getOneData($sql1);
	$pagesize = 15;
	$current = isset($_REQUEST['page']) ? $_REQUEST['page'] : 1;
	$offset = ($current - 1) * $pagesize;
	$sql="SELECT id,name,zhuShi FROM PHPCD WHERE 1=1";
	if(!empty($_GET['id'])){
		$sql.=" AND id LIKE '%$id%'";// ID模糊查询
	}
	if(!empty($_GET['name'])){
		$sql.=" AND name LIKE '%$name%'";// 名称模糊查询
	}
	if(!empty($_GET['zhuShi'])){
		$sql.=" AND zhuShi LIKE '%$zhuShi%'";// 注释模糊查询
	}
	$sql.=" limit $offset,$pagesize";// 偏移量，查询的行数
	$rows =$db1->GetMoreRow($sql);
	$page = new page($total,$pagesize,$current,'',array('id'=>"$id",'name'=>"$name",'zhuShi'=>"$zhuShi",));
	foreach ($rows as $v) {
		$str .= '<tr>';
		$str .= "<td>{$v['id']}</td>";
		$str .= "<td>{$v['name']}</td>";
		$str .= "<td>{$v['zhuShi']}</td>";
		$str .= "<td><a href='http://www.php.net/{$v['name']}' target='_blank'>详情</a></td>";
		$str .= '</tr>';
	}
	$str .= "</table>";
	echo $str;
	echo $page->showPage();
	### 分页类 end  ###
}

function insall(){
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
			// header('refresh:2; url=?');
			$aaa =__FILE__;
			$bbb ='mysqlconf.php';
			echo " MySQL 配置文件--------------------写入成功！<a href='?a=insall'>创建数据表</a><br>请您自己手动修改当前文件（{$aaa}）目录下的 {$bbb} 配置文件！<br>";
			die;
		}else{
			// header('refresh:2; url=?');
			echo " MySQL 配置文件--------------------写入失败！<br>或许是您没有权限新建文件！ <a href='?a=insall'>再来一次？</a><br>";
			die;
		}
	}
	elseif(file_exists('table.timp')){
		die("为了保障数据安全性，禁止您重复创建数据表！如果您执意如此，请删除根目录下的 table.timp文件再来刷新当前页面");
	}
$db1=new mypdo();
$reset ="DROP TABLE IF EXISTS `PHPCD`";// 清除数据库
$res=$db1->exec($reset);//初始化1：删除同名数据表
	if($res===false){
		echo "语法错误 or 您没有权限初始化数据表！";
	}
$table_sql="CREATE TABLE `PHPCD` (`id` SMALLINT(2) unsigned NOT NULL AUTO_INCREMENT,`name` char(49) NOT NULL,`zhuShi` char(49) comment '中文注释',addTime TIMESTAMP comment '添加时间',PRIMARY KEY (`id`),UNIQUE KEY `name` (`name`)) ENGINE=InnoDB AUTO_INCREMENT=1000 DEFAULT CHARSET=utf8";
$res =$db1->exec($table_sql);//初始化2：创建数据表
	if($res===false){
		echo "写入 数据表----------------------失败！<a href='?'>首页</a><br>";
		echo "<br>####################################<br>请您自行检查，错误原因！<br>友情提示：或许是您的 MySQL 配置文件信息不对，以至于造成 MySQL链接失败！";
		die;
	}else{
		echo "写入 数据表----------------------成功！<a href='?a=add'>导入函数名</a><br>";
		file_put_contents('table.timp','A');
	}
}

function add(){
if(!file_exists('array.php')) die('预处理数组不存在，请准备好数组！<br>');
require_once "array.php";
	$count=count($arr);
	$db1=new mypdo();
		$i=0;
	foreach ($arr as $value) {
		++$i;
		$add_sql ="INSERT INTO `PHPCD` VALUES (NULL,'{$value}','',now())";
		$res =$db1->exec($add_sql);//插入数据
			if($res===false){
				$str= "编号：$i<br>数组成员：$value<br>";
				$str.= "写入 测试数据 ---------------------- 失败！<a href='?'>首页</a><br>";
				$str.= "<br>####################################<br>请您自行检查，错误原因！<br>友情提示：或许是您的 MySQL 配置文件信息不对，以至于造成 MySQL链接失败！";
				die($str);
			}else{
				echo "编号：$i<br>数组成员：$value<br>";
				echo "写入 ---------------------- 成功！<br>";
			}
	}
	echo "<br>####################################<br>写入完成！合计：$count 条数据。<a href='?a=edit'>导入注释</a><br>";

}

function edit(){
if(!file_exists('zhushi.php')) die('预处理数组不存在，请准备好数组！<br>');
require_once "zhushi.php";
	$count=count($arr);
	$db1=new mypdo();
		$id=999;
		$j=0;
	foreach ($arr as $value) {
		++$id;
		++$j;
		$edit_sql ="UPDATE PHPCD SET zhuShi='{$value}' WHERE id={$id}";
		$res =$db1->exec($edit_sql);//插入数据
			if(!$res){
				$str= "编号：$j<br>数组成员：$value<br>";
				$str.= "写入 测试数据 ---------------------- 失败！<a href='?'>首页</a><br>";
				$str.= "<br>####################################<br>请您自行检查，错误原因！<br>友情提示：或许是您的 MySQL 配置文件信息不对，以至于造成 MySQL链接失败！";
				die($str);
			}else{
				echo "编号：$j<br>数组成员：$value<br>";
				echo "写入 ---------------------- 成功！<br>";
			}
	}
	echo "<br>####################################<br>写入完成！合计：$count 条数据。<a href='?'>首页</a> - <a href='?a=insall'>重置</a><br>";
}