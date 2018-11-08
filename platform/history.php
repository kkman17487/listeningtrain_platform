<!DOCTYPE html>
<html lang="zh-Hant-TW">
<?php include('backendheader.php'); ?>
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
		dataPoints: <?php echo json_encode($dataPoints, JSON_NUMERIC_CHECK); ?>
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

<body>

<?php
	include('backendsidebar.php');
	include('connect_to_sql.php');

	$sql ="select * from `history`";
	$data = mysqli_query($con, $sql);

	for($i=1;$i<mysqli_num_rows($data);$i++)
	{ 
		$labelrs=mysqli_fetch_row($data);
	}
?>

<?php
	$dataPoints = array(
	array("y" => 'echo $labelrs[0]', "label" => echo '$labelrs[3]')
	/*array("y" => 90, "label" => "2018/10/24 02:45")
	array("y" => 15, "label" => "Monday"),
	array("y" => 25, "label" => "Tuesday"),
	array("y" => 5, "label" => "Wednesday"),
	array("y" => 10, "label" => "Thursday"),
	array("y" => 0, "label" => "Friday"),
	array("y" => 20, "label" => "Saturday")
	array("y" => correct rate, "label" => "Reaction Time")*/
	);

	$dataPoints2 = array( 
	array("y" => 3373.64, "label" => "Germany" ),
	array("y" => 2435.94, "label" => "France" ),
	array("y" => 1039.99, "label" => "Switzerland" ),
	array("y" => 765.215, "label" => "Japan" ),
	array("y" => 612.453, "label" => "Netherlands" )
	);

	$dataPoints3 = array(
	array("x" => 23, "y" => 340),
	array("x" => 28, "y" => 390),
	array("x" => 39, "y" => 400),
	array("x" => 34, "y" => 430),
	array("x" => 24, "y" => 321),
	/*array("x" => 29, "y" => 250),
	array("x" => 29, "y" => 400),
	array("x" => 23, "y" => 290),
	array("x" => 27, "y" => 250),
	array("x" => 34, "y" => 380),
	array("x" => 36, "y" => 350),
	array("x" => 33, "y" => 405),
	array("x" => 32, "y" => 453),
	array("x" => 21, "y" => 292)*/
	);
 
	$dataPoints4 = array(
	array("x" => 19, "y" => 192),
	array("x" => 27, "y" => 250),
	array("x" => 35, "y" => 330),
	array("x" => 32, "y" => 190),
	array("x" => 29, "y" => 189),
	array("x" => 22, "y" => 160),
	/*array("x" => 27, "y" => 200),
	array("x" => 26, "y" => 192),
	array("x" => 24, "y" => 225),
	array("x" => 33, "y" => 330),
	array("x" => 34, "y" => 250),
	array("x" => 30, "y" => 120),
	array("x" => 37, "y" => 160),
	array("x" => 24, "y" => 196)*/
	);

?>

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
			/*$Arr=array('<?php ?>','','','');
			foreach(&Arr as $key => $value)
				echo '
				<tr>
					<td>'.$key.'</td>
					<td>Lorem</td>
					<td>ipsum</td>
					<td>dolor</td>
					<td>sit</td>
				</tr>
				'
			}*/
			?>
			<?php
				for($i=0;$i<mysqli_num_rows($data);$i++){
				$rs=mysqli_fetch_assoc($data);
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
