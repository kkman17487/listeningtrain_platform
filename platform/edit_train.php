<!DOCTYPE html>
<html lang="zh-Hant-TW">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css">
<!--<style>
.top{
 margin:auto;
 width:60vw;
 text-align:right;
 padding:15vh 0 0 0;
 font-family:微軟正黑體;
}
/*.nav{
 background-color:#339;
 padding: 10px 0px;
 }*/
.nav a {
  color: #5a5a5a;
  font-size: 11px;
  font-weight: bold;
  text-transform: uppercase;
}

.nav li {
  display: inline;
}
 .CSSTableGenerator {
 margin:auto;
 padding:0px;
 width:60vw;
 }
 .CSSTableGenerator table{
    border-collapse: collapse;
    border-spacing: 0;
 width:100%;
 height:100%;
 margin:0px;padding:0px;
}.CSSTableGenerator tr:last-child td:last-child {
 -moz-border-radius-bottomright:9px;
 -webkit-border-bottom-right-radius:9px;
 border-bottom-right-radius:9px;
}
.CSSTableGenerator table tr:first-child td:first-child {
 -moz-border-radius-topleft:9px;
 -webkit-border-top-left-radius:9px;
 border-top-left-radius:9px;
}
.CSSTableGenerator table tr:first-child td:last-child {
 -moz-border-radius-topright:9px;
 -webkit-border-top-right-radius:9px;
 border-top-right-radius:9px;

}.CSSTableGenerator tr:last-child td:first-child{
 -moz-border-radius-bottomleft:9px;
 -webkit-border-bottom-left-radius:9px;
 border-bottom-left-radius:9px;

}/*.CSSTableGenerator tr:hover td{
 background-color:#005fbf;
 color:white;
}*/
.CSSTableGenerator td{
 vertical-align:middle;
 background-color:#e5e5e5;
 border:1px solid #999999;
 border-width:0px 1px 1px 0px;
 text-align:left;
 padding:8px;
 font-size:16px;
 font-family:Arial,微軟正黑體;
 font-weight:normal;
 color:#000000;
}.CSSTableGenerator tr:last-child td{
 border-width:0px 1px 0px 0px;
}.CSSTableGenerator tr td:last-child{
 border-width:0px 0px 1px 0px;
}.CSSTableGenerator tr:last-child td:last-child{
 border-width:0px 0px 0px 0px;
}
.CSSTableGenerator tr:first-child td{
  background:-o-linear-gradient(bottom, #005fbf 5%, #005fbf 100%);
  background:-webkit-gradient( linear, left top, left bottom, color-stop(0.05, #005fbf), color-stop(1, #005fbf) );
  background:-moz-linear-gradient( center top, #005fbf 5%, #005fbf 100% );
  filter:progid:DXImageTransform.Microsoft.gradient(startColorstr="#005fbf", endColorstr="#005fbf");
  background: -o-linear-gradient(top,#005fbf,005fbf);
  background-color:#005fbf;
  text-align:center;
  font-size:20px;
  font-family:Arial, 微軟正黑體;
  font-weight:bold;
  color:#ffffff;
}
.CSSTableGenerator tr:first-child:hover td{
  background:-o-linear-gradient(bottom, #005fbf 5%, #005fbf 100%);
  background:-webkit-gradient( linear, left top, left bottom, color-stop(0.05, #005fbf), color-stop(1, #005fbf) );
  background:-moz-linear-gradient( center top, #005fbf 5%, #005fbf 100% );
  filter:progid:DXImageTransform.Microsoft.gradient(startColorstr="#005fbf", endColorstr="#005fbf");
  background: -o-linear-gradient(top,#005fbf,005fbf);
  background-color:#005fbf;
}
</style>-->
</head>

<?php
include("connect_to_sql.php");
if(isset($_POST['delete']))
{
  foreach ($_POST['delete'] as $key => $value) {
    $con->query("DELETE FROM train WHERE id = '$value'");
  }
  header("Location: edit_train.php");
}
if(isset($_GET['addtrain']))
{
  $question = "";
  foreach($_POST['question'] as $key => $value)
  {
    $question .= $value.",";
  }
  $question = substr($question, 0, -1);
  $date = date("Y-m-d H:i:s",time());
  $con->query("INSERT INTO `train` (`id`, `name`, `question`, `creator`, `create_time`, `recent_edit_time`) VALUES (NULL,'$_POST[name]','$question','$_POST[creator]','$date','$date')");
  header("Location: edit_train.php");
  die();
}
if(isset($_POST['name']) && isset($_POST['question']))
{
  $question = "";
  foreach($_POST['question'] as $key => $value)
  {
    $question .= $value.",";
  }
  $question = substr($question, 0, -1);
  $date = date("Y-m-d H:i:s",time());
  $con->query("UPDATE train SET question='$question',name='$_POST[name]',creator='$_POST[creator]',recent_edit_time='$date' WHERE id='$_GET[ID]'");
  header("Location: edit_train.php");
  die();
}
include('backendheader.php'); ?>
<body>
<?php include('backendsidebar.php'); ?>
<div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
<h1 class="sub-header">編輯訓練教材</h1>
題庫選擇
<?php
if(!isset($_GET['ID'])&&!isset($_GET['add'])){//顯示全部
$data = $con->query("select * from train");
$sound = $con->query("select * from data");
//讓資料由最新呈現到最舊
?>

  <div class="container">
    <div class="CSSTableGenerator">
      <a href="edit_train.php?add=true">新增</a>
      <form method="post" action="">
        <table align="center">
              <tr>
                <td width="1%"></td>
                <td width="4%">ID</td>
                <td width="10%">名稱</td>
                <td width="35%">題目</td>
                <td width="10%">作者</td>
                <td width="20%">創造時間</td>
                <td width="20%">最近修改時間</td>
              </tr>
<?php
for($i=1;$i<=mysqli_num_rows($data);$i++){
 $rs=mysqli_fetch_assoc($data);
?>

            <tr>
              <td width="1%"><input type="checkbox" name="delete[]" value="<?php echo $rs['id'];?>"></td>
              <td width="4%"><a href="edit_train.php?ID=<?php echo $rs['id'];?>"><?php echo $rs['id'];?></a></td>
              <td width="10%"><?php echo $rs['name'];?></td>
              <td width="35%">
                <?php
                  $question = explode(",",$rs['question']);
                  foreach($question as $key => $value)
                  {
                    $sound = $con->query("select * from data where id = '$value'");
                    $rs_sound = mysqli_fetch_assoc($sound);
                    echo $value;
                    echo ':';
                    echo $rs_sound['name'];
                  }
                ?>
              </td>
              <td width="10%"><?php echo $rs['creator'];?></td>
              <td width="20%"><?php echo $rs['create_time'];?></td>
              <td width="20%"><?php echo $rs['recent_edit_time'];?></td>
            </tr>
<?php } ?>
</table>
<input type="submit" value="刪除">
</form>
</div>
</div>
<?php
}
elseif(isset($_GET['ID']) && !isset($_GET['add'])){//修改個別
  $ID = $_GET['ID'];
  $data = $con->query("select * from train where id = '$ID'");
  $sound = $con->query("select * from data");
  //讓資料由最新呈現到最舊
?>
  <div class="container">
    <div class="CSSTableGenerator">
      <form method="post" name="train" action="edit_train.php?ID=<?php echo $ID;?>">
        <table align="center">
              <tr>
                <td width="5%">ID</td>
                <td width="10%">名稱</td>
                <td width="35%">題目</td>
                <td width="10%">作者</td>
                <td width="20%">創造時間</td>
                <td width="20%">最近修改時間</td>
              </tr>
<?php
 $rs=mysqli_fetch_assoc($data);
?>

            <tr>
              <td width="5%"><?php echo $rs['id'];?></td>
              <td width="10%"><input type="text" name="name" value="<?php echo $rs['name'];?>"></td>
              <td width="35%">
                <?php
                  $question = explode(",",$rs['question']);
                  $j = 0;
                  for($i = 1; $i <= mysqli_num_rows($sound); $i++)
                  {
                    $rs_sound = mysqli_fetch_assoc($sound);
                    echo "<input type=checkbox name=question[] value=".$rs_sound['id'];
                    if($j < sizeof($question) && $question[$j] == $rs_sound['id'])
                    {
                      echo " checked";
                      $j++;
                    }
                    echo ">".$rs_sound['id'].":".$rs_sound['name'];
                  }
                ?>
              </td>
              <td width="10%"><input type="text" name="creator" value="<?php echo $rs['creator'];?>"></td>
              <td width="20%"><?php echo $rs['create_time'];?></td>
              <td width="20%"><?php echo $rs['recent_edit_time'];?></td>
            </tr>
</table>
<input type="submit" value="修改">
<input type="button" value="取消" onclick="document.location.href='edit_train.php'" />
</form>
</div>
</div>
<?php }
elseif(!isset($_GET['ID']) && isset($_GET['add'])){
  $sound = $con->query("select * from data");
?>
<div class="container">
  <div class="CSSTableGenerator">
    <form method="post" name="train" action="edit_train.php?addtrain=true">
      <table align="center">
            <tr>
              <td width="10%">名稱</td>
              <td width="35%">題目</td>
              <td width="10%">作者</td>
            </tr>

          <tr>
            <td width="10%"><input type="text" name="name"></td>
            <td width="35%">
              <?php
                for($i = 1; $i <= mysqli_num_rows($sound); $i++)
                {
                  $rs_sound = mysqli_fetch_assoc($sound);
                  echo "<input type=checkbox name=question[] value=".$rs_sound['id'];
                  echo ">".$rs_sound['id'].":".$rs_sound['name'];
                }
              ?>
            </td>
            <td width="10%"><input type="text" name="creator"></td>
          </tr>
</table>
<input type="submit" value="新增">
<input type="button" value="取消" onclick="document.location.href='edit_train.php'" />
</form>
</div>
</div>
<?php }?>
</div>
</body>
</html>
