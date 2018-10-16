<?php 
	$dataPoints = array(
	array("y" => 25, "label" => "Sunday"),
	array("y" => 15, "label" => "Monday"),
	array("y" => 25, "label" => "Tuesday"),
	array("y" => 5, "label" => "Wednesday"),
	array("y" => 10, "label" => "Thursday"),
	array("y" => 0, "label" => "Friday"),
	array("y" => 20, "label" => "Saturday")
);
?>

<!DOCTYPE html>
<html lang="zh-Hant-TW">
<?php include('backendheader.php'); ?>
<head>
<script>
window.onload = function () {
 
var chart = new CanvasJS.Chart("chartContainer", {
	title: {
		text: "Answer Time"
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
 
}
</script>
</head>

<body>
<?php include('backendsidebar.php'); ?>
<div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
<h1 class="sub-header">作答情況</h1>
    <div class="table-responsive">
        <table class="table table-striped">
            <thead>
                <tr>
                  <th>#</th>
                  <th>姓名</th>
                  <th>作答情況</th>
                  <th>答對率</th>
                  <th>時間</th>
                </tr>
            </thead>
            <tbody>
			<?php
			$Arr=array('','','');
			$i=count($Arr);
			for($j=1;$j<$i;$j++){
				echo '
				<tr>
					<td>'.$j.'</td>
					<td>Lorem</td>
					<td>ipsum</td>
					<td>dolor</td>
					<td>sit</td>
				</tr>
					'
			}
			?>
            </tbody>
        </table>
		
    </div>
	要有的項目?:
	人名
	作答(可點進看結果)
	答對率
	時間
		
<h1 class="page-header">圖表</h1>
    <div class="row placeholders">
		<br>參考php圖表:https://canvasjs.com/php-charts/ <br>
		<div id="chartContainer" style="height: 370px; width: 100%;"></div>
		<script src="https://canvasjs.com/assets/script/canvasjs.min.js"></script>
    </div>
</div>
</body>
</html>	