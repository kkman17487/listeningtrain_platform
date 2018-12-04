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

  $frequency = $_POST['formfrequency'];

  $waveform = $_POST['formwaveform'];

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

  if(!isset($frequency))
  {
    echo("<p>You didn't select any frequency!</p>\n");
  }
  else
  {
    $nfrequency = count($frequency);

    echo("<p>You selected $nfrequency categorys: ");
    for($i=0; $i < $nfrequency; $i++)
    {
      echo($frequency[$i] . " ");
    }
    echo("</p>");
  }

  if(!isset($waveform))
  {
    echo("<p>You didn't select any waveform!</p>\n");
  }
  else
  {
    $nwaveform = count($waveform);

    echo("<p>You selected $nwaveform categorys: ");
    for($i=0; $i < $nwaveform; $i++)
    {
      echo($waveform[$i] . " ");
    }
    echo("</p>");
  }
}
?>

<div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
<h1 class="sub-header">上傳檔案</h1>
    <div class="table-responsive">

	<form action="upload.php" method="post" enctype="multipart/form-data">
    圖片<input type="file" name="my_file[]"><br>
    音檔<input type="file" name="my_file[]">
	<br></br>

	<label>中文名稱</label>
	<input type="text" name="ChineseName" placeholder="中文"/>

	<label>英文名稱</label>
	<input type="text" name="EnglishName" placeholder="English"/>

	<br></br>
	<label for='formcategory[]'>選擇類別</label>
	<select multiple="multiple" name="formcategory[]">
      <?php
      $all_category = array();
      $data = $con->query("SELECT * FROM `data`");
      for($j = 1;$j <= mysqli_num_rows($data);$j++){
        $rs_data = mysqli_fetch_assoc($data);
        $tmp = explode(";",$rs_data[category]);
          $all_category = array_merge($all_category, $tmp);
      }
      $all_category = array_unique($all_category);
      foreach ($all_category as $key => $value){
        echo '<option value="'.$value.'"';
        foreach ($category as $m_key => $m_value)
          if($m_value == "$value")
            echo 'selected=selected';
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
  找不到想要的類別?<input type="text" id="category" name="category" size="10" maxlength="10" hidden>(請用;區隔不同類別)
	<br></br>
	<label for='formcategory[]'>選擇頻率</label>
	<select multiple="multiple" name="formfrequency[]">
		<option value="<100"><100</option>
		<option value="100~1000">100~1000</option>
		<option value="1000~2000">1000~2000</option>
		<option value="2000~4000">2000~4000</option>
		<option value="4000~7000">4000~7000</option>
		<option value="7000">7000</option>
		<option value="全部">全部</option>
	</select>

	<br></br>
	<label>選擇波型</label>
	<select multiple="multiple" name="formwaveform[]">
		<option value="平緩">平緩</option>
		<option value="低頻高">低頻高</option>
		<option value="高頻高">高頻高</option>
		<option value="中間高，兩邊低">中間高，兩邊低</option>
		<option value="中間低，兩邊高">中間低，兩邊高</option>
		<option value="全部">全部</option>
	</select>

	<br></br>
	<input type="submit" value="送出" />
	</form>
    </div>
</div>
</body>

</html>
