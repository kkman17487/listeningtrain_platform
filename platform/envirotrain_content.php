<!DOCTYPE html>
<?php
include('connect_to_sql.php');
@$this_type = $_GET['id'];
$data = $con->query("SELECT * FROM enviro WHERE id =$this_type");
include('sidebar.php');
?>
<html>
<style>
.object{
  position: absolute;
  background-color: transparent;
  color: black;
  font-size: 10px;
  padding: 8px 15px;
  border: none;
  cursor: pointer;
  border-radius: 5px;
  text-align: center;
	}
</style>
  <!-- Top header -->
  <header class="w3-container w3-xlarge">
    <p class="w3-left">情境訓練模式</p>
    <!--<p class="w3-right">
      <i class="fa fa-shopping-cart w3-margin-right"></i>
      <i class="fa fa-search"></i>
    </p>-->
  </header>
  <body> 
  <?php
  $rs = mysqli_fetch_assoc($data);
  ?>
  <!-- Product grid -->
      <div class="w3-container">
        <audio id='2'>
            <source src="../sound/cook/cutting_a_onion3.mp3" type="audio/mp3" />
            <embed height="100" width="100" src="../sound/cook/cutting_a_onion3.mp3" />
          </audio>
          <img src="<?php echo $rs['background_src']?>" height="100%" width="100%" style="position: relative;">
            <input id='1' type="image" style=" top: 37%; left: 50.5%;"width="40" src="../enviro/object/knife_4x.png" onmouseover="mouseOver()"
			onmouseout="mouseOut()" class="object" onclick="sound('2')" />
			</img>
        
        </div>
        <p align = 'center'><?php echo $rs['name']?></p>
	  <article class="w3-container"> 
       <ul><h2>聲音列表</h2>
        <li>刀子</a></li>
        <li></a></li>
        <li></a></li>
       </ul>
      </article>
  <!-- End page content -->
  <script>
function mouseOver()
{
document.getElementById('1').width ="50"
}
function mouseOut()
{
document.getElementById('1').width ="40"
}

var previous_play;
function sound(next_play)
{
  //alert(next_play);
  if(typeof previous_play !== 'undefined')
  {
    document.getElementById(previous_play).pause();
    document.getElementById(previous_play).currentTime = 0;
  }
  document.getElementById(next_play).play();
  previous_play = next_play;
}
</script>
  
</body>
</html>
