<?php
# 取得上傳檔案數量
include("connect_to_sql.php");
$ID=explode(":",$_POST["updataId"]);
$coordinate=explode(":",$_POST["updataCoordinate"]);
$size=explode(":",$_POST["updataSize"]);
for($i=0;$i<count($ID);$i++)
{
	$res = $con->query("UPDATE object SET coordinate='$coordinate[$i]' WHERE id = '$ID[$i]'");
	$ress = $con->query("UPDATE object SET size='$size[$i]' WHERE id = '$ID[$i]'");
}
if (!$res) {
die('Invalid query: ' . mysqli_error($con));
}
else {
  echo "<script>alert('更新成功');window.location.replace('edit_enviro.php');</script>";
}
?>
