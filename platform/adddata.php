<!DOCTYPE html>
<html lang="zh-Hant-TW">
<?php 
session_start();
include('backendheader.php');
include('connect_to_sql.php');

if(isset($_GET['add'])){
	$con->query("INSERT INTO `data` (`pic_src`,`sound_src`,`tag`,`name`,`created_time`,`audio_id`) VALUES(,,,ChineseName,,EnglishName);");
}

?>

<body>
<?php include('backendsidebar.php'); ?>

<div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
<h1 class="sub-header">修改聲音、圖片</h1>
    <div class="table-responsive">
	
    <u><a href="adddata.php?add=true">新增</a></u>
		<form action="test_post.php" method="post">
		中文名稱 <input type="text" name="ChineseName" /><br>
　		英文名稱 <input type="text" name="EnglishName" /><br>
		選擇類別 <br>
		<form action="upload.php" method="post" enctype="multipart/form-data">
　		選擇聲音 <input type="file" name="my_file[]" multiple><br>
		選擇圖片 <input type="file" name="my_file[]" multiple><br>
　		<input type="submit" value="送出" />
　		</form>

	<br>
		<u><strong>刪除</strong></u>
		
    </div>
</div>
</body>

</html>	