<?php 
include("connect_to_sql.php");
header('Content-type: text/html; charset=utf-8');
header("Content-type:application/vnd.ms-excel;charset=utf-8"); 
header("Content-Disposition:filename=$_GET[name]作答紀錄.xls");
echo '<html xmlns:o="urn:schemas-microsoft-com:office:office"
xmlns:x="urn:schemas-microsoft-com:office:excel"
xmlns="http://www.w3.org/TR/REC-html40">
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
    <head>
        <meta http-equiv="Content-type" content="text/html;charset=UTF-8" />
        <style id="Classeur1_16681_Styles"></style>
    </head>
    <body>
        <div id="Classeur1_16681" align=center x:publishsource="Excel">
         <table x:str border=0 cellpadding=0 cellspacing=0 width=100% style="border-collapse: collapse">';
if($_GET['mode']==0)
{
if($_GET['name']=='all')
$result = $con->query("select * from envirohistory");
else
$result = $con->query("select * from envirohistory where name='$_GET[name]'");
echo "<tr><td>id</td><td>姓名</td><td>正確答案</td><td>選擇答案</td><td>答題時間</td><td>正確率</td><td>平均答題時間</td><td>情境id</td><td>模式(1測驗2練習)</td></tr>";
while($row=mysqli_fetch_array($result)){
echo "<tr><td>".$row[0]."</td><td>".$row[1]."</td>";
$cutanswer = explode(";",$row[2]);
	for($i=0;$i<count($cutanswer);$i++)
	{
		$cutone=explode("/",$cutanswer[$i]);
		echo "<td>".$cutone[0]."</td><td>".$cutone[1]."</td><td>".$cutone[2]."</td>";
	}
	echo "<td>".$row[3]."</td><td>".$row[4]."</td><td>".$row[5]."</td><td>".$row[6]."</td></tr>";
}
echo '</table>
        </div>
    </body>
</html>';
}
else if($_GET['mode']==1)
{
if($_GET['name']=='all')
$result = $con->query("select * from history");
else
$result = $con->query("select * from history where name='$_GET[name]'");
echo "<tr><td>id</td><td>姓名</td><td>正確答案</td><td>選擇答案</td><td>答題時間</td><td>正確率</td><td>平均答題時間</td></tr>";
while($row=mysqli_fetch_array($result)){
echo "<tr><td>".$row[0]."</td><td>".$row[1]."</td>";
$cutanswer = explode(";",$row[2]);
	for($i=0;$i<count($cutanswer);$i++)
	{
		$cutone=explode("/",$cutanswer[$i]);
		echo "<td>".$cutone[0]."</td><td>".$cutone[1]."</td><td>".$cutone[2]."</td>";
	}
	echo "<td>".$row[3]."</td><td>".$row[4]."</td></tr>";
}
echo '</table>
        </div>
    </body>
</html>';
}
?>