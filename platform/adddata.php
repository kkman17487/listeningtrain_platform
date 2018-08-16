<!DOCTYPE html>
<html lang="zh-Hant-TW">
<?php include('backendheader.php'); ?>

<style>
.button{width:120px;height:40px;font-size:20px;}
</style>

<body>
<?php include('backendsidebar.php'); ?>
<div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
<h1 class="sub-header">修改聲音資料</h1>
    <div class="table-responsive">
	
        <u><strong>新增</strong></u>
			<form action="upload.php" method="post" enctype="multipart/form-data">選擇檔案:
　				<input type="file" name="file" id="file"/><br>
　				<input type="submit" name="submit" value="上傳檔案"/>
　			</form>
		<br><u><strong>刪除</strong></u>
    </div>
</div>
</body>
</html>	