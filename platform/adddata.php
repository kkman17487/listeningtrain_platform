<!DOCTYPE html>
<html lang="zh-Hant-TW">

<?php 
session_start();
include('backendheader.php');
include('connect_to_sql.php');

if(isset($_GET['add'])){
	$con->query("INSERT INTO `data` (`pic_src`,`sound_src`,`tag`,`name`,`created_time`,`audio_id`) VALUES(,,,ChineseName,,EnglishName);");
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

<body>
<?php include('backendsidebar.php'); ?>

<div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
<h1 class="sub-header">修改聲音、圖片</h1>
    <div class="table-responsive">
	
    <a href="adddata.php?add=true">新增</a>
		<form action="test_post.php" method="post">
		中文名稱 <input type="text" name="ChineseName"/><p>
　		英文名稱 <input type="text" name="EnglishName"/><p>
 
		<label for='formcategory[]'>選擇類別</label>
		<select multiple="multiple" name="formcategory[]">
			<option value="City">城市、房子</option>
			<option value="Nature">自然、動物</option>
			<option value="Kitchen">廚房</option>
			<option value="Restaurant">餐廳</option>
			<option value="Others">其他</option>
		</select>
		
		<p>
		<form action="uploads.php" method="post" enctype="multipart/form-data">
　		選擇聲音 <input type="file" name="my_file[]" multiple></form> <br>
		<form action="uploadp.php" method="post" enctype="multipart/form-data">
		選擇圖片 <input type="file" name="my_file[]" multiple></form> <br> 
		
　		<input type="submit" value="送出" />
		</form>
	<p>
		<u><strong>刪除</strong></u>
		
    </div>
</div>
</body>

</html>	