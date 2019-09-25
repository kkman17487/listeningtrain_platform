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
		  if (file_exists('../enviro/object/'.$_FILES['my_file']['name'][$i])){
			  echo '檔案已存在。<br/>';
		  }
		  else {
			  $file = $_FILES['my_file']['tmp_name'][$i];
			  $dest = '../enviro/object/' . $_FILES['my_file']['name'][$i];
			  # 將檔案移至指定位置
		    move_uploaded_file($file, $dest);
        $pic_src = '../enviro/object/' . $_FILES['my_file']['name'][$i];
		  }
	}
	else {
		echo 'error<br>';
	}

}
}
$res = $con->query("INSERT INTO `object` (`pic_src`,`name`,`sound_src`,`coordinate`,`size`) VALUES('$pic_src','$_POST[ChineseName]','$_POST[SoundSrc]','100,100','42')");

if (!$res) {
die('Invalid query: ' . mysqli_error($con));
}
else {
	$add_ID=$con->query("SELECT id FROM object WHERE name ='$_POST[ChineseName]'");
	$enviro_OB=$con->query("SELECT object FROM enviro WHERE id ='$_POST[enviro_ID]'");
	$enrs=mysqli_fetch_assoc($enviro_OB);
	$rs = mysqli_fetch_assoc($add_ID);
	$concatid=$enrs['object'].",".$rs['id'];
	echo $concatid;
$a=$con->query("UPDATE enviro SET object='$concatid' WHERE id='$_POST[enviro_ID]'");
  #echo "<script>alert('上傳成功');window.location.replace('addenviro_object.php?id={$_POST[enviro_ID]}');</script>";
}
?>