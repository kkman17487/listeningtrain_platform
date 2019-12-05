<?php
include("connect_to_sql.php");
if(isset($_GET['OD'])&&isset($_GET['EID']))
{
	$newObList="";
	$deleteEnviro = $con->query("SELECT * FROM `enviro` WHERE id = '$_GET[EID]'");
	$Obrs = mysqli_fetch_assoc($deleteEnviro);
	$tmp_object = explode(',',$Obrs['object']);
	foreach($tmp_object as $key => $value1)                                                             
	{
		if($_GET['OD']==$value1)
		{
			$object=$con->query("SELECT * FROM object WHERE id ='$value1'");                                     
			$rs_object=mysqli_fetch_assoc($object);
			unlink($rs_object['pic_src']);
			$con->query("DELETE FROM object WHERE id = '$value1'");
		}
		else
		{
			$newObList=$value1.",";
		}
	}
	$newObList = substr($newObList,0,-1);
	$res = $con->query("UPDATE enviro SET object='$newObList' WHERE id='$_GET[EID]'");
	if (!$res) 
	{
		die('Invalid query: ' . mysqli_error($con));
	}
	else 
	{
		echo "<script>alert('更新成功');window.location.replace('edit_enviro.php');</script>";
	}
}
else
	echo "<script>alert('沒有得到object或enviro的id');window.location.replace('edit_enviro.php');</script>";
?>