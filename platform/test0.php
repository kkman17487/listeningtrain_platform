<!DOCTYPE html>
<?php
include('connect_to_sql.php');
$data = $con->query("select * from data LIMIT 1");
$rs = mysqli_fetch_assoc($data);
include('sidebar.php');
 ?>
<html>
<head>
<style>

.test {
    width: 700px;
    height: 700px;
    border: 1px solid black;
}
</style>
</head>
<body>

<div class="test" onmousemove="myFunction(event)" onmouseout="clearCoor()">

	<div class="w3-col">
		<div class="w3-container">
			<div class="w3-display-container">
				<audio id='<?php echo $rs[audio_id]?>'>
					<source src="<?php echo $rs[sound_src]?>" type="audio/mp3" />
					<embed height="100" width="100" src="<?php echo $rs[sound_src]?>" />
				</audio>
				<img src="<?php echo $rs[pic_src]?>" height="100%" width="100%" />
				<?php
				//if()
					//echo '<span class="w3-tag w3-display-topleft">New</span>'?>
				<div class="w3-display-middle w3-display-hover">
					<button class="w3-button w3-black " onclick="document.getElementById('<?php echo $rs[audio_id]?>').play(); return false;">Play</button>
				</div>
			</div>
			<p align = 'center'><?php echo $rs[name]?></p>
		</div>
</div>

</div>
<p id="demo"></p>

<script>
function myFunction(e) {
    var x = e.clientX;
    var y = e.clientY;
    var coor = "Coordinates: (" + x + "," + y + ")";
    document.getElementById("demo").innerHTML = coor;
}

function clearCoor() {
    document.getElementById("demo").innerHTML = "";
}
</script>

</body>
</html>
