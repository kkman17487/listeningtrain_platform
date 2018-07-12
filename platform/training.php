<!DOCTYPE html>
<?php
mysql_connect("localhost","root","az135790");
mysql_select_db("listeningtrain_platform");
mysql_query("set names utf8");
$data = mysql_query("select * from data");
$this_type = $_GET['sound_type'];
include('sidebar.php');
?>
<html>
  <!-- Top header -->
  <header class="w3-container w3-xlarge">
    <p class="w3-left">訓練模式</p>
    <!--<p class="w3-right">
      <i class="fa fa-shopping-cart w3-margin-right"></i>
      <i class="fa fa-search"></i>
    </p>-->
  </header>

  <div class="w3-container w3-text-grey" id="sounds">
    <form>
      <select onChange="location = 'training.php?sound_type=' + this.options[this.selectedIndex].value;">
        <option value="#">分類</option>
        <option value="森林" <?php if($this_type == "森林") echo 'selected'?>>森林</option>
        <option value="城市" <?php if($this_type == "城市") echo 'selected'?>>城市</option>
      </select>
    </form>
    <p>一共有<?php echo mysql_num_rows($data)?>筆資料</p>
  </div>

  <!-- Product grid -->
  <div class="w3-row w3-grayscale">

    <div class="w3-col l3 s6">
      <?php
      for($i = 1;$i <= mysql_num_rows($data);$i++){
        $rs = mysql_fetch_assoc($data)
        ?>
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
    <?php }?>
  </div>

  <!-- End page content -->
</div>
</body>
</html>
