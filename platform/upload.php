<?php
# 取得上傳檔案數量
include("connect_to_sql.php");
$fileCount = count($_FILES['my_file']['name']);

for ($i = 0; $i < $fileCount; $i++) {
  # 檢查檔案是否上傳成功
  $type=$_FILES['my_file']['type'][$i];
  if ($_FILES['my_file']['error'][$i] === UPLOAD_ERR_OK){
    echo '檔案名稱: ' . $_FILES['my_file']['name'][$i] . '<br/>';
    echo '檔案類型: ' . $_FILES['my_file']['type'][$i] . '<br/>';
    echo '檔案大小: ' . ($_FILES['my_file']['size'][$i] / 1024000) . ' MB<br/>';
    echo '暫存名稱: ' . $_FILES['my_file']['tmp_name'][$i] . '<br/>';

    # 檢查檔案是否已經存在
    if($type=="image/jpeg" || $type=="image/jpg" || $type=="image/png" || $type=="image/gif"){
		  if (file_exists('../picture/'.$_FILES['my_file']['name'][$i])){
			  echo '檔案已存在。<br/>';
		  }
		  else {
			  $file = $_FILES['my_file']['tmp_name'][$i];
			  $dest = '../picture/' . $_FILES['my_file']['name'][$i];
			  # 將檔案移至指定位置
		    move_uploaded_file($file, $dest);
        $pic_src = '../picture/' . $_FILES['my_file']['name'][$i];
		  }
	}
	else if($type=="audio/mp3" || $type=="audio/wav"){
		if (file_exists('../sound/'.$_FILES['my_file']['name'][$i])){
			echo '檔案已存在。<br/>';
		}
		else {
			$file = $_FILES['my_file']['tmp_name'][$i];
			$dest = '../sound/' . $_FILES['my_file']['name'][$i];

			# 將檔案移至指定位置
			move_uploaded_file($file, $dest);
      $sound_src = '../sound/' . $_FILES['my_file']['name'][$i];
		}
	}
	else {
		echo 'error<br>';
	}

}
}
$frequency = "";
foreach ($_POST['formfrequency'] as $value)
{
  $frequency .= $value.";";
}
$frequency = substr($frequency,0,-1);
$waveform = "";
foreach ($_POST['formwaveform'] as $value)
{
  $waveform .= $value.";";
}
$waveform = substr($waveform,0,-1);
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
$res = $con->query("INSERT INTO `data` (`pic_src`,`sound_src`,`category`,`name`,`frequency`,`waveform`,`created_time`,`audio_id`) VALUES('$pic_src','$sound_src','$category','$_POST[ChineseName]','$frequency','$waveform',CURRENT_TIMESTAMP,'$_POST[EnglishName]')");
if (!$res) {
die('Invalid query: ' . mysqli_error($con));
}
else {
  echo "<script>alert('上傳成功');window.location.replace('adddata.php');</script>";
}
?>
