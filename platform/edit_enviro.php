<!DOCTYPE html>
<html lang="zh-Hant-TW">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css">
</head>

<?php
include("connect_to_sql.php");
if(isset($_POST['delete']))
{
  foreach ($_POST['delete'] as $key => $value) {
	$deleteEnviro = $con->query("SELECT * FROM `enviro` WHERE id = '$value'");
	$Obrs = mysqli_fetch_assoc($deleteEnviro);
	$tmp_object = explode(',',$Obrs['object']);
	foreach($tmp_object as $key => $value1)                                                             
	{
		$object=$con->query("SELECT * FROM object WHERE id ='$value1'");                                     
		$rs_object=mysqli_fetch_assoc($object);
		unlink($rs_object['pic_src']);
		$con->query("DELETE FROM object WHERE id = '$value1'");
	}
	unlink($Obrs['background_src']);
    $con->query("DELETE FROM enviro WHERE id = '$value'");
  }
  header("Location: edit_enviro.php");
}
include('backendheader.php'); ?>
<body>
<?php include('backendsidebar.php'); ?>
<div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
<h1 class="sub-header">編輯情境</h1>
情境選擇
<?php
if(!isset($_GET['ID'])&&!isset($_GET['add'])){//顯示全部
$data = $con->query("select * from enviro");
$obje = $con->query("select * from object");
//讓資料由最新呈現到最舊
?>

  <div class="container">
    <div class="CSSTableGenerator">
      <a href="addenviro.php">新增</a>
      <form method="post" action="">
        <table align="center">
              <tr>
                <td width="1%"></td>
                <td width="4%">ID</td>
                <td width="10%">名稱</td>
                <td width="35%">物品</td>
                <td width="30%">背景圖</td>
                <td width="20%">創造時間</td>
                
              </tr>
<?php
for($i=1;$i<=mysqli_num_rows($data);$i++){
 $rs=mysqli_fetch_assoc($data);
?>

            <tr>
              <td width="1%"><input type="checkbox" name="delete[]" value="<?php echo $rs['id'];?>"></td>
              <td width="4%"><a href="addenviro_object.php?id=<?php echo $rs['id'];?>"><?php echo $rs['id'];?></a></td>
              <td width="10%"><?php echo $rs['name'];?></td>
              <td width="35%">
                <?php
                  $question = explode(",",$rs['object']);
                  foreach($question as $key => $value)
                  {
                    $obje = $con->query("select * from object where id = '$value'");
                    $rs_obje = mysqli_fetch_assoc($obje);
                    echo $value;
                    echo ':';
                    echo $rs_obje['name'];
                  }
                ?>
              </td>
              <td width="30%"><img src = "<?php echo $rs['background_src'];?>" width ="300px"></td>
              <td width="20%"><?php echo $rs['created_time'];?></td>
            </tr>
<?php } ?>
</table>
<input type="submit" value="刪除">
</form>
</div>
</div>
<?php
}?>

</form>
</div>
</div>
</div>
</body>
</html>
