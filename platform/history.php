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

}
</script>
</head>

<body>
<?php
	include('backendsidebar.php');
	include('connect_to_sql.php');

	$sql ="select * from `history`";
	$data = mysqli_query($con, $sql);
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

<h1 class="page-header">圖表</h1>
    <div class="row placeholders">
		<div id="chartContainer" style="height: 370px; width: 100%;"></div>
		<script src="https://canvasjs.com/assets/script/canvasjs.min.js"></script>
    </div>
</div>
</body>
</html>
