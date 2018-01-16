<?php
/**
 * 计算器v2.0
 * @authors 油果
 * @date    2017-11-23 19:19:30
 * 
 */
 header("content-type:text/html;charset=UTF-8");
if(!empty($_POST['js']))
{
//调试分隔符
/*echo "<pre>";
print_r($_POST);
echo "</pre>";*/
//调试分隔符
$zhi1 =(float)$_POST['zhi1'];//接收并转换参与运算的值的数据类型为浮点型
$op = $_POST['op'];
$zhi2 =(float)$_POST['zhi2'];
$res ='';
/*echo $zhi1."<br />".$op."<br />".$zhi2;
var_dump($op);*/
	switch ($op) 
	{
		case '+':
			$res = $zhi1 + $zhi2;
			break;
		case '-':
			$res = $zhi1 - $zhi2;
			break;
		case '*':
			$res = $zhi1 * $zhi2;
			break;
		case '/'://判断除数不为零
			if(empty($zhi2))
			{
				$res='除数不能为0';break;
			}
			$res = $zhi1 / $zhi2;
			break;
		case '%'://判断两个值不为零
			if($zhi1 <1 or $zhi2 <1 )
			{
				$res='不能输入<1的数';break;
			}
			$res = $zhi1 % $zhi2;
			break;
		default:
			$res='参数非法';
	}
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Document</title>
</head>
<body>
	<!-- 计算器v2.0 -->
	<form action="" method="POST">
		<input type="text" name="zhi1" value="<?php echo isset($zhi1) ? $zhi1 : "" ?>">
		<select name="op">
			<option value="+" <?php echo isset($op) && ($op =='+') ? 'selected' : '' ?>>加法</option>
			<option value="-" <?php echo isset($op) && ($op =='-') ? 'selected' : '' ?>>减法</option>
			<option value="*" <?php echo isset($op) && ($op =='*') ? 'selected' : '' ?>>乘法</option>
			<option value="/" <?php echo isset($op) && ($op =='/') ? 'selected' : '' ?>>除法</option>
			<option value="%" <?php echo isset($op) && ($op =='%') ? 'selected' : '' ?>>取余</option>
		</select>
		<input type="text" name="zhi2" value="<?php echo isset($zhi2) ? $zhi2 : "" ?>">
		<input type="submit" name="js" value="计算">
		<input type="text" value="<?php echo isset($res) ? $res : "" ?>">
	</form>
</body>
</html>
