<?php
$fileCount = count($_FILES['my_file']['name']);
$size=round($_FILES['my_file']['size']/1024000,3);
$type=$_FILES['my_file']['type'];
	
for ($i = 0; $i < $fileCount; $i++) {
  # 檢查檔案是否上傳成功
  if ($_FILES['my_file']['error'][$i] === UPLOAD_ERR_OK){
    echo '檔案名稱: ' . $_FILES['my_file']['name'][$i] . '<br/>';
    echo '檔案類型: ' . $type . '<br/>';
    echo '檔案大小: ' . $size . ' MB<br/>';
    echo '暫存名稱: ' . $_FILES['my_file']['tmp_name'][$i] . '<br/>';

    # 檢查檔案是否已經存在
    if (file_exists('../picture/' . $_FILES['my_file']['name'][$i])){
      echo '檔案已存在。<br/>';
    } else {
      $file = $_FILES['my_file']['tmp_name'][$i];
      $dest = '../picture/' . $_FILES['my_file']['name'][$i];

      # 將檔案移至指定位置
      move_uploaded_file($file, $dest);
    }
  } else {
    echo '錯誤代碼：' . $_FILES['my_file']['error'] . '<br/>';
  }
}
?>