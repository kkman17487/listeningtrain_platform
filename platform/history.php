<!DOCTYPE html>
<html lang="zh-Hant-TW">
<?php 
	include('backendheader.php');
	include('backendsidebar.php');
?>
<?php	
	include('connect_to_sql.php');
	
	$sql ="select * from `history`";
	$dbdata = mysqli_query($con, $sql);
?>

<head>	
<script>
window.onload = function () {

var chart = new CanvasJS.Chart("chartContainer", {
	title: {
		text: "Time"
	},
	axisY: {
		title: "Correct Rate"
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
		text: "Kind"
	},
	axisY: {
		title: "Correct Rate"
	},
	data: [{
		type: "column",
		yValueFormatString: "#,##0.## tonnes",
		dataPoints: <?php echo json_encode($dataPoints2, JSON_NUMERIC_CHECK); ?>
	}]
});
chart2.render();

var chart3 = new CanvasJS.Chart("chartContainer3", {
	animationEnabled: true,
	title:{
		text: "What?"
	},
	axisX: {
		title:"Server Load (in TPS)"
	},
	axisY:{
		title: "Response Time (in ms)"
	},
	legend:{
		cursor: "pointer",
		itemclick: toggleDataSeries
	},
	data: [
	{
		type: "scatter",
		toolTipContent: "<span style=\"color:#4F81BC \"><b>{name}</b></span><br/><b> Load:</b> {x} TPS<br/><b> Response Time:</b></span> {y} ms",
		name: "Server Jupiter",
		markerType: "square",
		showInLegend: true,
		dataPoints: <?php echo json_encode($dataPoints3); ?>
	},
	{
		type: "scatter",
		name: "Server Neptune",
		markerType: "triangle",
		showInLegend: true, 
		toolTipContent: "<span style=\"color:#C0504E \"><b>{name}</b></span><br/><b> Load:</b> {x} TPS<br/><b> Response Time:</b></span> {y} ms",
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
}
}
</script>
</head>
<?php
	for($i=0;$i<mysqli_num_rows($dbdata);$i++)
	{ 
		$labelrs=mysqli_fetch_row($dbdata);
	}	
	
	$dataPoints1 = array("y" => $labelrs[0], "label" => $labelrs[3]);
	print_r ($dataPoints1);	
	
	$dataPoints2 = array( 
	array("y" => 3373.64, "label" => "Germany" ),
	array("y" => 2435.94, "label" => "France" ),
	array("y" => 1039.99, "label" => "Switzerland" ),
	);

	$dataPoints3 = array(
	array("x" => 23, "y" => 340),
	array("x" => 28, "y" => 390),
	array("x" => 24, "y" => 321)
	);
 
	$dataPoints4 = array(
	array("x" => 19, "y" => 192),
	array("x" => 27, "y" => 250),
	array("x" => 22, "y" => 160)
	);
?>
<body>
<div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
<h1 class="sub-header">作答紀錄</h1>
    <div class="table-responsive">
        <table class="table table-striped">
            <thead>
                <tr>
                  <th>#</th>
                  <th>姓名</th>
                  <th>作答情況</th>
                  <th>答對率</th>
                  <th>作答時間</th>
                </tr>
            </thead>
            <tbody>
			<?php
				for($i=0;$i<mysqli_num_rows($dbdata);$i++){
				$rs=mysqli_fetch_assoc($dbdata);
			?>
			<tr>
				<td><?php echo $rs['id']?></td>
				<td><?php echo $rs['name']?></td>
				<td><?php echo $rs['data']?></td>
				<td><?php echo $rs['time']?></td>
			</tr>
			<?php
			}
			?>
            </tbody>
        </table>
    </div>
<h1 class="page-header">折線圖</h1>
    <div class="row placeholders">
		<div id="chartContainer" style="height: 370px; width: 100%;"></div>
		<script src="https://canvasjs.com/assets/script/canvasjs.min.js"></script>
    </div>

<h1 class="page-header">柱狀圖</h1>
    <div class="row placeholders">
		<div id="chartContainer2" style="height: 370px; width: 100%;"></div>
		<script src="https://canvasjs.com/assets/script/canvasjs.min.js"></script>
    </div>

<h1 class="page-header">散佈圖</h1>
    <div class="row placeholders">
		<div id="chartContainer3" style="height: 370px; width: 100%;"></div>
		<script src="https://canvasjs.com/assets/script/canvasjs.min.js"></script>
    </div>	
	
</div>
</body>
</html>