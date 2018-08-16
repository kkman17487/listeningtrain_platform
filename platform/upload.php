<?php
# 取得上傳檔案數量
$fileCount = count($_FILES['file']['name']);

for ($i = 0; $i < $fileCount; $i++) {
  # 檢查檔案是否上傳成功
  if ($_FILES['file']['error'][$i] === UPLOAD_ERR_OK){
    echo '檔案名稱: ' . $_FILES['file']['name'][$i] . '<br/>';
    echo '檔案類型: ' . $_FILES['file']['type'][$i] . '<br/>';
    echo '檔案大小: ' . ($_FILES['file']['size'][$i] / 1024) . ' KB<br/>';
    echo '暫存名稱: ' . $_FILES['file']['tmp_name'][$i] . '<br/>';

    # 檢查檔案是否已經存在
	if($type == "application/jpg" && $type == "application/png" && $type == "application/jpeg"&& $type == "application/gif")
	{	
		if (file_exists('../picture/' . $_FILES['file']['name'][$i])){
			echo '檔案已存在。<br/>';
		} 
		else {
			$files = $_FILES['file']['tmp_name'][$i];
			$dest = '../picture/' . $_FILES['file']['name'][$i];

		# 將檔案移至指定位置
		move_uploaded_file($files, $dest);
		}
	}
	
	if($type == "application/mp3" && $type == "application/wav")
	{	
		if (file_exists('../sound/' . $_FILES['file']['name'][$i])){
			echo '檔案已存在。<br/>';
		} 
		else {
			$files = $_FILES['file']['tmp_name'][$i];
			$dest = '../sound/' . $_FILES['file']['name'][$i];

		# 將檔案移至指定位置
		move_uploaded_file($files, $dest);
		}
	}
  } 
  
  else {
    echo '錯誤代碼：' . $_FILES['file']['error'] . '<br/>';
  }
}
?>