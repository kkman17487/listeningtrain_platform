<!DOCTYPE html>
<?php
include('connect_to_sql.php');
$this_type = $_GET['sound_type'];
$data = mysql_query("select * from data where tag LIKE '%$this_type%'");
$tag = mysql_query("select tag from data where tag != ''");
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
        <option value="#">全部</option>
        <?php
        $classfication = array();
        for($j = 1;$j <= mysql_num_rows($tag);$j++){
          $rs_tag = mysql_fetch_assoc($tag);
          $tmp = explode("、",$rs_tag[tag]);
            $classfication = array_merge($classfication, $tmp);
        }
        $classfication = array_unique($classfication);
        foreach ($classfication as $key => $value){
          echo '<option value="'.$value.'"';
          if($this_type == "$value")
            echo 'selected';
          echo '>'.$value.'</option>';
        }?>

      </select>
    </form>
    <p>一共有<?php echo mysql_num_rows($data)?>筆資料</p>
  </div>

  <!-- Product grid -->
    <?php
    for($i = 1;$i <= mysql_num_rows($data);$i++){
      $rs = mysql_fetch_assoc($data);
      if($i % 4 == 1)echo '<div class="w3-row w3-grayscale">';
    ?>
    <div class="w3-col l3 s6">
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
  <?php
    if($i % 4 == 0 || $i == mysql_num_rows($data))echo '</div>';
  }
  ?>
  <!-- End page content -->

</body>
</html>
