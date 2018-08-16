<!DOCTYPE html>
<html lang="zh-Hant-TW">
<?php include('backendheader.php'); ?>

<body>
<?php include('backendsidebar.php'); ?>
<div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
<h1 class="sub-header">修改聲音、圖片</h1>
    <div class="table-responsive">
	
        <u><strong>新增</strong></u>
			<form action="upload.php" method="post" enctype="multipart/form-data">
　			選擇檔案:<input type="file" name="file" id="file" /><br />
　			<input type="submit" name="submit" value="上傳檔案" />
　			</form>
		<br><u><strong>刪除</strong></u>
    </div>
</div>
</body>

<?php
if ($_FILES["file"]["error"] > 0){
　echo "Error: " . $_FILES["file"]["error"];
}else{
　echo "檔案名稱: " . $_FILES["file"]["name"]."<br/>";
　echo "檔案類型: " . $_FILES["file"]["type"]."<br/>";
　echo "檔案大小: " . ($_FILES["file"]["size"] / 1024)." Kb<br />";
　
　if (file_exists("upload/" . $_FILES["file"]["name"])){
　　echo "檔案已經存在，請勿重覆上傳相同檔案";
　}else{
　　move_uploaded_file($_FILES["file"]["tmp_name"],"upload/".$_FILES["file"]["name"]);
　}

if (($_FILES["file"]["type"] == "image/gif")
|| ($_FILES["file"]["type"] == "image/jpeg")
|| ($_FILES["file"]["type"] == "image/jpg")
|| ($_FILES["file"]["type"] == "image/png"))

}
?>

</html>	