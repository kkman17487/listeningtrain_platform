<!DOCTYPE html>
<?php
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
        <option value="forest" <?php if($this_type == "forest") echo 'selected'?>>森林</option>
        <option value="town" <?php if($this_type == "town") echo 'selected'?>>城市</option>
      </select>
    </form>
    <p>一共有幾筆資料(從資料庫抓)</p>
  </div>

  <!-- Product grid -->
  <div class="w3-row w3-grayscale">

    <div class="w3-col l3 s6">
      <div class="w3-container">
        <div class="w3-display-container">
          <audio id='iron_door'>
            <source src="../sound/knocking_an_iron_door3.mp3" type="audio/mp3" />
            <embed height="100" width="100" src="../sound/knocking_an_iron_door3.mp3" />
          </audio>
          <img src="../picture/iron_door.jpg" height="100%" width="100%" />
          <span class="w3-tag w3-display-topleft">New</span>
          <div class="w3-display-middle w3-display-hover">
            <button class="w3-button w3-black " onclick="document.getElementById('iron_door').play(); return false;">Play</button>
          </div>
        </div>
        <p align = 'center'>鐵置門</p>
      </div>
      <div class="w3-container">
        <div class="w3-display-container">
          <audio id='shrill_whistle'>
            <source src="../sound/shrill_whistle1.mp3" type="audio/mp3" />
            <embed height="100" width="100" src="../sound/shrill_whistle1.mp3" />
          </audio>
          <img src="../picture/shrill_whistle.jpg" height="100%" width="100%" />
          <span class="w3-tag w3-display-topleft">New</span>
          <div class="w3-display-middle w3-display-hover">
            <button class="w3-button w3-black " onclick="document.getElementById('shrill_whistle').play(); return false;">Play</button>
          </div>
        </div>
        <p align = 'center'>吹哨</p>
      </div>

  </div>

  <!-- End page content -->
</div>
</body>
</html>