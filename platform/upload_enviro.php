<?php
# 取得上傳檔案數量
include("connect_to_sql.php");
$fileCount = count($_FILES["my_file"]["name"]);

for ($i = 0; $i < $fileCount; $i++) {
  # 檢查檔案是否上傳成功
  $type=$_FILES["my_file"]["type"][$i];
  echo $_FILES["my_file"]["error"][$i];
  if ($_FILES["my_file"]["error"][$i] === UPLOAD_ERR_OK){
    echo '檔案名稱: ' . $_FILES["my_file"]["name"][$i] . '<br/>';
    echo '檔案類型: ' . $_FILES["my_file"]["type"][$i] . '<br/>';
    echo '檔案大小: ' . ($_FILES["my_file"]["size"][$i] / 1024000) . ' MB<br/>';
    echo '暫存名稱: ' . $_FILES["my_file"]["tmp_name"][$i] . '<br/>';

    # 檢查檔案是否已經存在
    if($type=="image/jpeg" || $type=="image/jpg" || $type=="image/png" || $type=="image/gif"){
		  if (file_exists('../enviro/background/'.$_FILES["my_file"]["name"][$i])){
			  echo '檔案已存在。<br/>';
		  }
		  else {
			  $file = $_FILES["my_file"]["tmp_name"][$i];
			  $dest = '../enviro/background/' . $_FILES["my_file"]["name"][$i];
			  # 將檔案移至指定位置
			  echo $file.$dest;
		    if(move_uploaded_file($file, $dest))
			{
				echo "<script>alert('上傳成功');</script>";
			}
			else
			{
				echo $file.$dest;
			}
        $pic_src = '../enviro/background/' . $_FILES["my_file"]["name"][$i];
		  }
	}
	else {
		echo 'error<br>';
	}

}
}
$category = "";
foreach ($_POST['formcategory'] as $value)
{
  $category .= $value.";";
}
if(isset($_POST['category']))
{
  $category .= $_POST['category'];
  if(substr($category,-1) == ";")
    $category = substr($category,0,-1);
}
$res = $con->query("INSERT INTO `enviro` (`background_src`,`category`,`name`,`created_time`) VALUES('$pic_src','$category','$_POST[ChineseName]',CURRENT_TIMESTAMP)");
if (!$res) {
die('Invalid query: ' . mysqli_error($con));
}
else {
$data = $con->query("select id from enviro where name ='$_POST[ChineseName]'");
$rs_obje = mysqli_fetch_assoc($data);
  echo "<script>window.location.replace('addenviro_object.php?id={$rs_obje['id']}');</script>";
}
?>