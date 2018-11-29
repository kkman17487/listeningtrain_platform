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
    $con->query("DELETE FROM data WHERE id = '$value'");
  }
  header("Location: edit_data.php");
}
if(isset($_POST['name']) && isset($_POST['question']))
{
  $ID = $_GET['ID'];
  $name = $_POST['name'];
  $question = "";
  foreach($_POST['question'] as $key => $value)
  {
    $question .= $value.",";
  }
  $question = substr($question, 0, -1);
  $date = date("Y-m-d H:i:s",time());
  $con->query("UPDATE data SET question='$question',name='$name',recent_edit_time='$date' WHERE id='$ID'");
  header("Location: edit_train.php");
  die();
}
include('backendheader.php'); ?>
<body>
<?php include('backendsidebar.php'); ?>
<div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
<h1 class="sub-header">編輯資料</h1>
資料選擇
<?php
if(!isset($_GET['ID'])){
$data = $con->query("select * from data");
//讓資料由最新呈現到最舊
?>

  <div class="container">
    <div class="CSSTableGenerator">
      <a href="adddata.php">新增</a>
      <form method="post" action="">
        <table align="center">
              <tr>
                <td width="1%"></td>
                <td width="4%">ID</td>
                <td width="10%">名稱</td>
                <td width="20%">頻率</td>
                <td width="15%">波形</td>
                <td width="15%">標籤</td>
                <!--<td width="20%">創造時間</td>-->
                <td width="15%">最近修改時間</td>
              </tr>
<?php
for($i=1;$i<=mysqli_num_rows($data);$i++){
 $rs=mysqli_fetch_assoc($data);
?>

            <tr>
              <td width="1%"><input type="checkbox" name="delete[]" value="<?php echo $rs['id'];?>"></td>
              <td width="4%"><a href="edit_data.php?ID=<?php echo $rs['id'];?>"><?php echo $rs['id'];?></a></td>
              <td width="10%"><?php echo $rs['name'];?></td>
              <td width="20%"><?php echo $rs['frequency'];?></td>
              <td width="15%"><?php echo $rs['waveform'];?></td>
              <td width="15%"><?php echo $rs['tag'];?></td>
              <td width="20%"><?php echo $rs['created_time'];?></td>
              <td width="15%"><?php echo $rs['recent_edit_time'];?></td>
            </tr>
<?php } ?>
</table>
<input type="submit" value="刪除">
</form>
</div>
</div>
<?php
}
else{
  $ID = $_GET['ID'];
  $data = $con->query("select * from data where id = '$ID'");
  $rs = mysqli_fetch_assoc($data);
  //讓資料由最新呈現到最舊
?>
<div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
<div class="table-responsive">
  <form action="upload_edit.php?ID=<?php echo $_GET['ID']?>" method="post" enctype="multipart/form-data">
    圖片<input type="file" name="my_file[]"><img src="<?php echo $rs['pic_src']?>" width="100px" height="100px"><br>
    音檔<input type="file" name="my_file[]">
    <audio id= "<?php echo $rs['audio_id']?>">
      <source src="<?php echo $rs['sound_src']?>" type="audio/mp3" />
      <embed height="100" width="100" src="<?php echo $rs['sound_src']?>" />
    </audio>
    <button class="w3-button w3-black " onclick="document.getElementById('<?php echo $rs['audio_id']?>').play(); return false;">Play</button><br>
  <br></br>

  <label>中文名稱</label>
  <input type="text" name="ChineseName" placeholder="中文" value="<?php echo $rs['name'];?>"/>

  <label>英文名稱</label>
  <input type="text" name="EnglishName" placeholder="English" value="<?php echo $rs['audio_id'];?>"/>

  <br></br>
  <label for='formcategory[]'>選擇類別</label>
  <select multiple="multiple" name="formcategory">
    <option value="城市、房子" <?php if($rs['tag'] == "城市、房子") echo "selected=selected"?>>城市、房子</option>
    <option value="街道" <?php if($rs['tag'] == "街道") echo "selected=selected"?>>街道</option>
    <option value="自然、動物" <?php if($rs['tag'] == "自然、動物") echo "selected=selected"?>>自然、動物</option>
    <option value="廚房" <?php if($rs['tag'] == "廚房") echo "selected=selected"?>>廚房</option>
    <option value="樂器" <?php if($rs['tag'] == "樂器") echo "selected=selected"?>>樂器</option>
    <option value="餐廳" <?php if($rs['tag'] == "餐廳") echo "selected=selected"?>>餐廳</option>
    <option value="家" <?php if($rs['tag'] == "家") echo "selected=selected"?>>家</option>
    <option value="學校" <?php if($rs['tag'] == "學校") echo "selected=selected"?>>學校</option>
    <option value="活動" <?php if($rs['tag'] == "活動") echo "selected=selected"?>>活動</option>
    <option value="日常生活" <?php if($rs['tag'] == "日常生活") echo "selected=selected"?>>日常生活</option>
    <option value="其他" <?php if($rs['tag'] == "其他") echo "selected=selected"?>>其他</option>
  </select>

  <br></br>
  <label for='formcategory[]'>選擇頻率</label>
  <select multiple="multiple" name="formfrequency">
    <option value="100" <?php if($rs['frequency'] == "100") echo "selected=selected"?>>100</option>
    <option value="100~1000" <?php if($rs['frequency'] == "100~1000") echo "selected=selected"?>>100~1000</option>
    <option value="1000~2000" <?php if($rs['frequency'] == "1000~2000") echo "selected=selected"?>>1000~2000</option>
    <option value="2000~4000" <?php if($rs['frequency'] == "2000~4000") echo "selected=selected"?>>2000~4000</option>
    <option value="4000~7000" <?php if($rs['frequency'] == "4000~7000") echo "selected=selected"?>>4000~7000</option>
    <option value="7000" <?php if($rs['frequency'] == "7000") echo "selected=selected"?>>7000</option>
    <option value="全部" <?php if($rs['frequency'] == "全部") echo "selected=selected"?>>全部</option>
  </select>

  <br></br>
  <label>選擇波型</label>
  <select multiple="multiple" name="formwaveform">
    <option value="平緩" <?php if($rs['waveform'] == "平緩") echo "selected=selected"?>>平緩</option>
    <option value="低頻高" <?php if($rs['waveform'] == "低頻高") echo "selected=selected"?>>低頻高</option>
    <option value="高頻高" <?php if($rs['waveform'] == "高頻高") echo "selected=selected"?>>高頻高</option>
    <option value="中間高，兩邊低" <?php if($rs['waveform'] == "中間高，兩邊低") echo "selected=selected"?>>中間高，兩邊低</option>
    <option value="中間低，兩邊高" <?php if($rs['waveform'] == "中間低，兩邊高") echo "selected=selected"?>>中間低，兩邊高</option>
    <option value="全部" <?php if($rs['waveform'] == "全部") echo "selected=selected"?>>全部</option>
  </select>

  <br></br>
  <input type="submit" value="送出" />
  </form>
</div>
</div>
<?php }?>
</div>
</body>
</html>
