<!DOCTYPE html>
<html lang="zh-Hant-TW">
<body>
<?php
session_start();
include('backendheader.php');
?>

<?php 
include('backendsidebar.php');
include('connect_to_sql.php');

if(isset($_GET['add'])){
	//$con->query("INSERT INTO `data` (`pic_src`,`sound_src`,`tag`,`name`,`created_time`,`audio_id`) VALUES(NULL,NULL,'',ChineseName,CURRENT_TIMESTAMP,EnglishName);");
	$res = $con->query("INSERT INTO `data` (`pic_src`,`sound_src`,`tag`,`name`,`created_time`,`audio_id`) VALUES(NULL,NULL,'',$_POST[ChineseName],CURRENT_TIMESTAMP,$_POST[EnglishName]);");
	if (!$res) {
	die('Invalid query: ' . mysqli_error($con));
	}
}

if(isset($_POST['formSubmit']))
{
  $acategory = $_POST['formcategory'];
	
	$frequency = $_POST['formfrequency'];
	
	$waveform = $_POST['formwaveform'];
	
  if(!isset($acategory))
  {
    echo("<p>You didn't select any category!</p>\n");
  }
  else
  {
    $ncategory = count($acategory);

    echo("<p>You selected $ncategory categorys: ");
    for($i=0; $i < $ncategory; $i++)
    {
      echo($acategory[$i] . " ");
    }
    echo("</p>");
  }
}
?>
<div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
<h1 class="sub-header">修改聲音、圖片</h1>
    <div class="table-responsive">

    <u><strong>新增</strong></u>
		<form action="upload.php" method="post" enctype="multipart/form-data"><input type="file" name="my_file[]" multiple>
		<!--檔名須為mp3,wav；檔名須為jpg,jpeg,png,gif-->
		<br></br>

		<label>中文名稱</label>
		<input type="text" name="ChineseName" placeholder="中文"/>

		<label>英文名稱</label>
		<input type="text" name="EnglishName" placeholder="English"/>
		<br></br>

		<label for='formcategory[]'>選擇類別</label>
		<br></br>
		<select multiple="multiple" name="formcategory[]">
			<option value="City">城市、房子</option>
			<option value="Street">街道</option>
			<option value="Nature">自然、動物</option>
			<option value="Kitchen">廚房</option>
			<option value="Instrument">樂器</option>
			<option value="Restaurant">餐廳</option>
			<option value="Home">家</option>
			<option value="School">學校</option>
			<option value="Event">活動</option>
			<option value="Dailylife">日常生活</option>
			<option value="Others">其他</option>
		</select>
		
		<br></br>
		<label>選擇頻率</label>
		<select name="formfrequency">
			<option value="f0"><100</option>
			<option value="f1">100~300</option>
			<option value="f2">300~500</option>
			<option value="f3">500~700</option>
		</select>

		<br></br>		
		<label>選擇波型</label>
		<select name="formwaveform">
			<option value="w0">平緩</option>
			<option value="w1">低頻高</option>
			<option value="w2">高頻高</option>
			<option value="w3">中間高，兩邊低</option>
			<option value="w4">中間低，兩邊高</option>		
		</select>
		
		<br></br>
		<input type="submit" value="送出" />
		</form>

	<br><br>
		<u><strong>刪除</strong></u>

    </div>
</div>
</body>

</html>