<?php
# 取得上傳檔案數量
include("connect_to_sql.php");
$fileCount = count($_FILES['my_file']['name']);
$type=$_FILES['my_file']['type'];

for ($i = 0; $i < $fileCount; $i++) {
  # 檢查檔案是否上傳成功
  if ($_FILES['my_file']['error'][$i] === UPLOAD_ERR_OK){
    echo '檔案名稱: ' . $_FILES['my_file']['name'][$i] . '<br/>';
    echo '檔案類型: ' . $_FILES['my_file']['type'][$i] . '<br/>';
    echo '檔案大小: ' . ($_FILES['my_file']['size'][$i] / 1024000) . ' MB<br/>';
    echo '暫存名稱: ' . $_FILES['my_file']['tmp_name'][$i] . '<br/>';

    # 檢查檔案是否已經存在
    if($type=="image/jpeg" || $type=="image/jpg" || $type=="image/png" || $type=="image/gif"){
      if($type == "image/jpeg")
        $pic_src = '../picture/'.$_FILES['my_file']['name'][$i].'.jpeg';
      if($type == "image/jpg")
        $pic_src = '../picture/'.$_FILES['my_file']['name'][$i].'.jpg';
      if($type == "image/png")
        $pic_src = '../picture/'.$_FILES['my_file']['name'][$i].'.png';
      if($type == "image/gif")
        $pic_src = '../picture/'.$_FILES['my_file']['name'][$i].'.gif';
		  if (file_exists($pic_src)){
			  echo '檔案已存在。<br/>';
		  }
		  else {
			  $file = $_FILES['my_file']['tmp_name'][$i];
			  $dest = '../picture/' . $_FILES['my_file']['name'][$i];
			  # 將檔案移至指定位置
		    move_uploaded_file($file, $dest);
		  }
	}
	else if($type=="audio/mp3" || $type=="audio/wav"){
    if($type=="audio/mp3")
      $sound_src = '../sound/'.$_FILES['my_file']['name'][$i].'.mp3';
    if($type=="audio/mp3")
      $sound_src = '../sound/'.$_FILES['my_file']['name'][$i].'.wav';
		if (file_exists($sound_src)){
			echo '檔案已存在。<br/>';
		}
		else {
			$file = $_FILES['my_file']['tmp_name'][$i];
			$dest = '../sound/' . $_FILES['my_file']['name'][$i];

			# 將檔案移至指定位置
			move_uploaded_file($file, $dest);
		}
	}
	else {
		echo 'error';
	}
  echo $pic_src.' '.$sound_pic;
	$res = $con->query("INSERT INTO `data` (`pic_src`,`sound_src`,`tag`,`name`,`frequency`,`waveform`,`created_time`,`audio_id`) VALUES('$pic_src','$sound_pic','','$_POST[ChineseName]','','',CURRENT_TIMESTAMP,'$_POST[EnglishName]')");
	if (!$res) {
	die('Invalid query: ' . mysqli_error($con));
	}

}
}
?>
