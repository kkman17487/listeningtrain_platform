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
	$res = $con->query("INSERT INTO `data` (`pic_src`,`sound_src`,`tag`,`name`,`frequency`,`waveform`,`created_time`,`audio_id`) VALUES(NULL,NULL,'',$_POST[ChineseName],CURRENT_TIMESTAMP,$_POST[EnglishName]);");
	if (!$res) {
	die('Invalid query: ' . mysqli_error($con));
	}
	
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

	<form action="upload.php" method="post" enctype="multipart/form-data"><input type="file" name="my_file[]" multiple>
	<br></br>

	<label>中文名稱</label>
	<input type="text" name="ChineseName" placeholder="中文"/>

	<label>英文名稱</label>
	<input type="text" name="EnglishName" placeholder="English"/>
	
	<br></br>	
	<label for='formcategory[]'>選擇類別</label>
	<select multiple="multiple" name="formcategory[]">
		<option value="City">城市、房子</option>
		<option value="Street">街道</option>
		<option value="Nature">自然、動物</option>
		<option value="Kitchen">廚房</option>
		<option value="Instrument">樂器</option>
		<option value="Restaurant">餐廳</option>
		<option value="Home">家</option>
		<option value="School">學校</option>
		<option value="Event">活動</option>
		<option value="Dailylife">日常生活</option>
		<option value="Others">其他</option>
	</select>
		
	<br></br>
	<label for='formcategory[]'>選擇頻率</label>
	<select multiple="multiple" name="formfrequency[]">
		<option value="f0"><100</option>
		<option value="f1">100~1000</option>
		<option value="f2">1000~2000</option>
		<option value="f3">2000~4000</option>
		<option value="f4">4000~7000</option>
		<option value="f5">>7000</option>
		<option value="f6">全部</option>
	</select>

	<br></br>		
	<label>選擇波型</label>
	<select multiple="multiple" name="formwaveform[]">
		<option value="w0">平緩</option>
		<option value="w1">低頻高</option>
		<option value="w2">高頻高</option>
		<option value="w3">中間高，兩邊低</option>
		<option value="w4">中間低，兩邊高</option>
		<option value="w5">全部</option>
	</select>
		
	<br></br>
	<input type="submit" value="送出" />
	</form>

	<?php
	/*if (isset($_GET["formcategory"])) {
       header("Location: " . $_GET["formcategory"] . ".php");
       exit();
	} */
	?>

    </div>
</div>
</body>

</html>