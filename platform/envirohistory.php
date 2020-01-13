<?php
	include('connect_to_sql.php');
if(isset($_GET['name'])){
	$dbdata = $con->query("select * from envirohistory where name = '$_GET[name]' and mode = 1");
	$inner = array();
	$inner2 = array();
	for($i=0;$i<mysqli_num_rows($dbdata);$i++)
	{
		$labelrs=mysqli_fetch_row($dbdata);
		array_push($inner,@array(label => $i+1, "y" => $labelrs[4]));
		array_push($inner2,@array(label => $i+1, "y" => $labelrs[3]));
	}
	$dataPoints1 = $inner;
	$dataPoints2 = $inner2;
	$dbdata2 = $con->query("select * from trainhistory where name = '$_GET[name]'");
	
}?>

<!DOCTYPE html>
<html lang="zh-Hant-TW">
<?php
	include('backendheader.php');
	include('backendsidebar.php');
?>
<body>
<?php
if(isset($_GET['name']))
{
	$dbdata = $con->query("select * from envirohistory where name = '$_GET[name]'");
?>
<div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
<h1 class="sub-header">作答紀錄</h1>
<input value="下載作答紀錄" type="button" class="w3-button w3-black " onclick="location.href='download_excel.php?name=<?php echo $_GET['name']?>&mode=0'"></input>
    <ul class="nav nav-tabs">
    <li class="active"><a data-toggle="tab" href="#exam">測驗與練習</a></li>
    <li><a data-toggle="tab" href="#train">訓練紀錄</a></li>
	</ul>
	<div class="tab-content">
    <div id="exam" class="tab-pane fade in active">
	<div class="table-responsive">
        <table class="table table-striped">
            <thead>
                <tr>
                  <th>#</th>
                  <th>姓名</th>
                  <th>作答情況(正確答案/選擇答案/作答時間)</th>
                  <th>答對率</th>
                  <th>平均作答反應時間</th>
				  <th>情境</th>
				  <th>模式</th>
                </tr>
            </thead>
            <tbody>
			<?php
				$max = 0;
				$min = 100;
				for($i=0;$i<mysqli_num_rows($dbdata);$i++){
				$rs=mysqli_fetch_assoc($dbdata);
				if($rs['time'] > $max)
					$max = $rs['time'];
				if($rs['time'] < $min)
					$min = $rs['time'];
			?>
			<tr>
				<td width="5%"><?php echo $i+1?></td>
				<td width="10%"><?php echo $rs['name']?></td>
				<td width="45%"><?php echo $rs['data']?></td>
				<td width="10%"><?php echo $rs['correct']?>%</td>
				<td width="20%"><?php echo $rs['time']?></td>
				<td width="5%"><?php echo $rs['enviroid']?></td>
				<td width="5%"><?php if($rs['mode']==1) echo "測驗";
									 else if($rs['mode']==2)echo "練習";?></td>
			</tr>
			<?php
			}
			?>
            </tbody>
        </table>
    </div>
	</div>
	<div id="train" class="tab-pane fade">
	<div class="table-responsive">
        <table class="table table-striped">
            <thead>
                <tr>
                  <th>#</th>
                  <th>姓名</th>
                  <th>訓練情況(物件名稱/點擊次數)</th>
                  <th>訓練日期</th>
                </tr>
            </thead>
            <tbody>
			<?php
				for($i=0;$i<mysqli_num_rows($dbdata2);$i++){
				$trs=mysqli_fetch_assoc($dbdata2);
			?>
			<tr>
				<td width="5%"><?php echo $i+1?></td>
				<td width="10%"><?php echo $trs['name']?></td>
				<td width="50%"><?php echo $trs['data']?></td>
				<td width="35%"><?php echo $trs['time']?></td>
			</tr>
			<?php
			}
			?>
            </tbody>
        </table>
    </div>
	</div>
	</div>
<br></br>
<h1 class="page-header">測驗反應時間折線圖</h1>
    <div class="row placeholders">
		<div id="chartContainer" style="height: 370px; width: 100%;"></div>
		<script src="../dist/js/phpchart.js"></script>
    </div>
<br></br>
<h1 class="page-header">測驗正確率柱狀圖</h1>
    <div class="row placeholders">
		<div id="chartContainer2" style="height: 370px; width: 100%;"></div>
		<script src="../dist/js/phpchart.js"></script>
    </div>
</div>
<?php }
else {
	$dbdata = $con->query("select * from envirohistory");
?>
	<div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
	<input value="下載作答紀錄" type="button" class="w3-button w3-black " onclick="location.href='download_excel.php?name=all&mode=0'"></input>
	<h1 class="sub-header">作答人</h1>
	    <div class="table-responsive">
	        <table class="table table-striped">
	            <thead>
	                <tr>
	                  <th>姓名</th>
	                </tr>
	            </thead>
	            <tbody>
				<?php
					$name = array();
	        for($j = 1;$j <= mysqli_num_rows($dbdata);$j++){
	          $rs_dbdata = mysqli_fetch_assoc($dbdata);
						//echo $rs_dbdata['name'];
	          array_push($name, $rs_dbdata['name']);
	        }
	        $name = array_unique($name);
					//print_r($name);
	        foreach ($name as $value){
				?>
				<tr>
					<td width="10%"><a href="envirohistory.php?name=<?php echo $value?>"><?php echo $value?></a></td>
				</tr>
				<?php
				}
				?>
	            </tbody>
	        </table>
	    </div>
	</div>
<?php } ?>
<script>
window.onload = function () {
var chart = new CanvasJS.Chart("chartContainer", {
	title: {
		text: "平均作答時間"
	},
	axisY: {
		title: "秒",
		minimum: <?php echo $min-1;?>,
		maximum: <?php echo $max+1;?>,
		interval: <?php if(($max-$min)/10 < 0.1) echo 0.1;else echo ($max-$min)/10;?>,
    intervalType: "month"
	},
	data: [{
		type: "line",
		dataPoints: <?php echo json_encode($dataPoints1, JSON_NUMERIC_CHECK); ?>

	}]
});
chart.render();

var chart2 = new CanvasJS.Chart("chartContainer2", {
	animationEnabled: true,
	theme: "light2",
	title:{
		text: "次數"
	},
	axisY: {
		title: "Correct Rate (%)",
		minimum: 0,
		maximum: 100
	},
	data: [{
		type: "column",
		yValueFormatString: "#,##0.## ",
		dataPoints: <?php echo json_encode($dataPoints2, JSON_NUMERIC_CHECK); ?>
	}]
});
chart2.render();
}
</script>
</body>
</html>
