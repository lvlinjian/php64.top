<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<link rel="stylesheet" type="text/css" href="css/nr.css">
	<title>进制 - 转换</title>
<?php 
$zhi = $jieguo ="";
if($_POST){
	$zhi = $_POST['zhi'];
	$jz = $_POST['jz'];
	if($jz=='1002')//10>>>02
	{
		$jieguo = decbin((int)$zhi);
	}else if($jz=='1008')//10>>>08
	{
		$jieguo = decoct((int)$zhi);
	}else if($jz=='1016')//10>>>16
	{
		$jieguo = dechex((int)$zhi);
	}else if($jz=='0210')//02>>>10
	{
		$jieguo = bindec($zhi);
	}else if($jz=='0810')//08>>>10
	{
		$jieguo = octdec($zhi);
	}else if($jz=='1610')//16>>>10
	{
		$jieguo = hexdec($zhi);
	}
	//----------------------------
	else if($jz=='0208')//02>>>08
	{
		$jieguo = bindec($zhi);//02>>>10
		$jieguo = decoct($zhi);//10>>>08
	}else if($jz=='0802')//08>>>02
	{
		$jieguo = octdec($zhi);//08>>>10
		$jieguo = decbin($zhi);//10>>>02
	}else if($jz=='0216')//02>>>16
	{
		$jieguo = bindec($zhi);//02>>>10
		$jieguo = dechex($zhi);//10>>>16
	}else if($jz=='1602')//16>>>02
	{
		$jieguo = hexdec($zhi);//16>>>10
		$jieguo = decbin($zhi);//10>>>02
	}else if($jz=='0816')//08>>>16
	{
		$jieguo = octdec($zhi);//08>>>10
		$jieguo = dechex($zhi);//10>>>16
	}else if($jz=='1608')//16>>>08
	{
		$jieguo = hexdec($zhi);//16>>>10
		$jieguo = decoct($zhi);//10>>>08
	}
}
?>
</head>
<body>
	<table>
		<tr>
		<th>进制 - 转换</th>
		</tr>
		<tr>
		<td>
		<form action="?act=jzzh" method="post">
			<input type="text" name="zhi" value="<?=$zhi?>">
			<select name="jz">
				<option value="1002">十 to 二</option>
				<option value="1008">十 to 八</option>
				<option value="1016">十 to 16</option>
				<option value="0210">二 to 十</option>
				<option value="0810">八 to 十</option>
				<option value="1610">16 to 十</option>
				<option value="0208">二 to 八</option>
				<option value="0802">八 to 二</option>
				<option value="0216">二 to 16</option>
				<option value="1602">16 to 二</option>
				<option value="0816">八 to 16</option>
				<option value="1608">16 to 八</option>
			</select>
			<input type="submit" value="进制转换">
			<input type="text" name="jieguo" value="<?=$jieguo?>">
		</form>
		</td>
		</tr>
	</table>
</body>
</html>