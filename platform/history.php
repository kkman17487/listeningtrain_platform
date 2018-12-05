<?php
	include('connect_to_sql.php');
if(isset($_GET['name'])){
	$dbdata = $con->query("select * from history where name = '$_GET[name]'");
	$inner = array();
	for($i=0;$i<mysqli_num_rows($dbdata);$i++)
	{
		$labelrs=mysqli_fetch_row($dbdata);
		array_push($inner,array(label => $i+1, "y" => $labelrs[4]));
	}
	$dataPoints1 = $inner;

	$dbdata = $con->query("select * from history where name = '$_GET[name]'");
	$inner = array();
	for($i=0;$i<mysqli_num_rows($dbdata);$i++)
	{
		$labelrs=mysqli_fetch_row($dbdata);
		array_push($inner,array(label => $i+1, "y" => $labelrs[3]));
	}
	$dataPoints2 = $inner;

	/*$dbdata = $con->query("select * from history");
	$inner = array();
	for($i=0;$i<mysqli_num_rows($dbdata);$i++)
	{
		$labelrs=mysqli_fetch_row($dbdata);
		array_push($inner,array("x" => $labelrs[0], "y" => $labelrs[4]));
	}
	$dataPoints3 = $inner;

	$dbdata = $con->query("select * from history");
	$inner = array();
	for($i=0;$i<mysqli_num_rows($dbdata);$i++)
	{
		$labelrs=mysqli_fetch_row($dbdata);
		array_push($inner,array("x" => $labelrs[0], "y" => $labelrs[4]));
	}
	$dataPoints4 = $inner;*/
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
	$dbdata = $con->query("select * from history where name = '$_GET[name]'");
?>
<div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
<h1 class="sub-header">作答紀錄</h1>
    <div class="table-responsive">
        <table class="table table-striped">
            <thead>
                <tr>
                  <th>#</th>
                  <th>姓名</th>
                  <th>作答情況(正確答案/選擇答案/作答時間)</th>
                  <th>答對率</th>
                  <th>平均作答反應時間</th>
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
				<td width="50%"><?php echo $rs['data']?></td>
				<td width="15%"><?php echo $rs['correct']?>%</td>
				<td width="20%"><?php echo $rs['time']?></td>
			</tr>
			<?php
			}
			?>
            </tbody>
        </table>
    </div>
<br></br>
<h1 class="page-header">折線圖</h1>
    <div class="row placeholders">
		<div id="chartContainer" style="height: 370px; width: 100%;"></div>
		<script src="../dist/js/phpchart.js"></script>
    </div>
<br></br>
<h1 class="page-header">柱狀圖</h1>
    <div class="row placeholders">
		<div id="chartContainer2" style="height: 370px; width: 100%;"></div>
		<script src="../dist/js/phpchart.js"></script>
    </div>
<!--<br></br>
<h1 class="page-header">散佈圖</h1>
    <div class="row placeholders">
		<div id="chartContainer3" style="height: 370px; width: 100%;"></div>
		<script src="../dist/js/phpchart.js"></script>
    </div>-->
</div>
<?php }
else {
	$dbdata = $con->query("select * from history");
?>
	<div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
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
					<td width="10%"><a href="history.php?name=<?php echo $value?>"><?php echo $value?></a></td>
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

/*var chart3 = new CanvasJS.Chart("chartContainer3", {
	animationEnabled: true,
	title:{
		text: "What?"
	},
	axisX: {
		title:"Correct rate (in %)"
	},
	axisY:{
		title: "Time (in ms)"
	},
	legend:{
		cursor: "pointer",
		itemclick: toggleDataSeries
	},
	data: [
	{
		type: "scatter",
		toolTipContent: "<span style=\"color:#4F81BC \"><b>{name}</b></span><br/><b> Load:</b> {x} TPS<br/><b> Correct rate:</b></span> {y} %",
		name: "Time",
		markerType: "square",
		showInLegend: true,
		dataPoints: <?php echo json_encode($dataPoints3); ?>
	},
	{
		type: "scatter",
		name: "Reaction Time",
		markerType: "triangle",
		showInLegend: true,
		toolTipContent: "<span style=\"color:#C0504E \"><b>{name}</b></span><br/><b> Load:</b> {x} TPS<br/><b> Correct rate:</b></span> {y} %",
		dataPoints: <?php echo json_encode($dataPoints4); ?>
	}
	]
});

chart3.render();
function toggleDataSeries(e){
	if (typeof(e.dataSeries.visible) === "undefined" || e.dataSeries.visible) {
		e.dataSeries.visible = false;
	}
	else{
		e.dataSeries.visible = true;
	}
	chart3.render();
}*/
}
</script>
</body>
</html>
