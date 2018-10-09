<!DOCTYPE html>
<?php
include('connect_to_sql.php');
$data = $con->query("select * from data LIMIT 1");
$rs = mysqli_fetch_assoc($data);
 ?>
<html>
<head>
<style>
div {
    width: 700px;
    height: 700px;
    border: 1px solid black;
}
</style>
</head>
<body>

<div onmousemove="myFunction(event)" onmouseout="clearCoor()">

	<!--<div class="w3-col l3 s6">
		<div class="w3-container">
			<div class="w3-display-container">-->
				<audio id='<?php echo $rs[audio_id]?>'>
					<source src="<?php echo $rs[sound_src]?>" type="audio/mp3" />
					<embed height="100" width="100" src="<?php echo $rs[sound_src]?>" />
				</audio>
				<img src="<?php echo $rs[pic_src]?>" height="100%" width="100%" />

</div>


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
