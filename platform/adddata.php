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
　			選擇檔案:<input type="file" name="my_file[]" multiple><br />
　			<input type="submit" value="上傳檔案" />
　			</form>
		<br>
		<u><strong>刪除</strong></u>
		
    </div>
</div>
</body>

</html>	