<!DOCTYPE html>
<html lang="zh-Hant-TW">
<?php 
	include('backendheader.php');
	include('backendsidebar.php');
?>
<header>

<header>	
<script type="text/javascript" src="history.js"></script>
</header>
<body>
<?php	
	include('connect_to_sql.php');
	
	$sql ="select * from `history`";
	$dbdata = mysqli_query($con, $sql);
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