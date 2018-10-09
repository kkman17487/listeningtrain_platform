<!DOCTYPE html>
<?php
include('connect_to_sql.php');
$data = $con->query("select * from data where name='png'");
$rs = mysqli_fetch_assoc($data);
include('sidebar.php');
 ?>
<html>
<head>
</head>
<body>
	<div class="w3-col">
		<div class="w3-container">
			<div class="w3-display-container">
				<audio id='<?php echo $rs[audio_id]?>'>
					<source src="<?php echo $rs[sound_src]?>" type="audio/mp3" />
					<embed height="100" width="100" src="<?php echo $rs[sound_src]?>" />
				</audio>
				<img src="<?php echo $rs[pic_src]?>" height="100px" width="100px" />
				<?php
				//if()
					//echo '<span class="w3-tag w3-display-topleft">New</span>'?>
				<div class="w3-display-middle w3-display-hover">
					<button class="w3-button w3-black " onclick="document.getElementById('<?php echo $rs[audio_id]?>').play(); return false;">Play</button>
				</div>

			<p align = 'center'><?php echo $rs[name]?></p>
			</div>
		</div>
	</div>

</body>
</html>
