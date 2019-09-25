<!DOCTYPE html>
<html lang="zh-Hant-TW">
<body>
<?php
session_start();
include('backendheader.php');
include('backendsidebar.php');
include('connect_to_sql.php');
?>

<?php
if(isset($_POST['submit']))
{
  $acategory = $_POST['formcategory'];

  if(!isset($acategory))
  {
    echo("<p>You didn't select any category!</p>\n");
  }
  else
  {
    $ncategory = count($acategory);

    echo("<p>You selected $ncategory categorys: ");
    for($i=0; $i < $ncategory; $i++)
    {
      echo($acategory[$i] . " ");
    }
    echo("</p>");
  }
}
?>

<div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
<h1 class="sub-header">上傳背景</h1>
    <div class="table-responsive">

	<form action="upload_enviro.php" method="post" enctype="multipart/form-data">
    <br>圖片<input type="file" name="my_file[]">
    </br>

	<label>名稱</label>
	<input type="text" name="ChineseName" placeholder="中文"/>
	<br></br>
	<label for='formcategory[]'>選擇類別</label>
	<select multiple="multiple" name="formcategory[]">
      <?php
      $all_category = array();
      $data = $con->query("SELECT * FROM `data`");
      for($j = 1;$j <= mysqli_num_rows($data);$j++){
        $rs_data = mysqli_fetch_assoc($data);
        $tmp = explode(";",$rs_data['category']);
          $all_category = array_merge($all_category, $tmp);
      }
      $all_category = array_unique($all_category);
      foreach ($all_category as $key => $value){
        echo '<option value="'.$value.'"';
		if (is_array(@$category) || is_object(@$category))
        {
        foreach (@$category as $m_key => $m_value)
          if($m_value == "$value")
            echo 'selected=selected';
        }
        echo '>'.$value.'</option>';
      }?>
		<!--<option value="房子">房子</option>
		<option value="廚房、餐廳">廚房、餐廳</option>
		<option value="日常生活">日常生活</option>
		<option value="街道">街道</option>
		<option value="動物">動物</option>
		<option value="自然">自然</option>
		<option value="樂器">樂器</option>
		<option value="學校">學校</option>
		<option value="活動">活動</option>
		<option value="軍事">軍事</option>
		<option value="其他">其他</option>-->
	</select>
  <br>
  找不到想要的類別?<input type="text" id="category" name="category" size="20" maxlength="20">(請用;區隔不同類別)
	<br></br>
	<input type="submit" value="送出" />
	</form>
    </div>
</div>
</body>

</html>
