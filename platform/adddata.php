<!DOCTYPE html>
<html lang="zh-Hant-TW">

<?php 
session_start();
include('backendheader.php');
?>

<body>
<?php include('backendsidebar.php'); 

include('connect_to_sql.php');

if(isset($_GET['add'])){
	//$con->query("INSERT INTO `data` (`pic_src`,`sound_src`,`tag`,`name`,`created_time`,`audio_id`) VALUES(NULL,NULL,'',ChineseName,CURRENT_TIMESTAMP,EnglishName);");
	$con->query("INSERT INTO `data` (`pic_src`,`sound_src`,`tag`,`name`,`created_time`,`audio_id`) VALUES(NULL,NULL,'',$_POST[ChineseName],CURRENT_TIMESTAMP,$_POST[EnglishName]);");
}

if(isset($_POST['formSubmit'])) 
{
  $acategory = $_POST['formcategory'];
  
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
	
    <a href="adddata.php?add=true">新增</a>
		<form action="" method="post">
		<label>中文名稱</label>
		<input type="text" name="ChineseName" placeholder="中文"/>
		
		<label>英文名稱</label>
		<input type="text" name="EnglishName" placeholder="English"/>
		<br><br>
		
		<label for='formcategory[]'>選擇類別</label>
		<br>
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
		
		<br><br>
		<form action="uploads.php" method="post" enctype="multipart/form-data">選擇聲音<input type="file" name="my_file[]" multiple></form>
		<form action="uploadp.php" method="post" enctype="multipart/form-data">選擇圖片<input type="file" name="my_file[]" multiple></form><br> 
		
		<input type="submit" value="送出" />
		</form>
	
	<br><br>
		<u><strong>刪除</strong></u>
		
    </div>
</div>
</body>

</html>	