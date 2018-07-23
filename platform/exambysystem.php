<!DOCTYPE html>
<?php ?>
<html>
<?php include('sidebar.php'); ?>

  <!-- Top header -->
  <header class="w3-container w3-xlarge">
    <p class="w3-left">系統出題</p>
  </header>

  <!-- Image header -->
  <?php
  if(!$_GET['number'])
    echo '<div class="w3-display-container w3-container">
    <img src="../picture/test1.jpg" alt="Jeans" style="width:100%">
    <div class="w3-display-topleft w3-text-white" style="padding:24px 48px">
      <h1 class="w3-jumbo w3-hide-small">系統出題</h1>
      <h1 class="w3-hide-small">Welcome</h1>
      <form action="exambysystem.php" method="get">
        題數: <input type="text" maxlength="2" size="2" name="number"><br>
        傾向: <input type="checkbox" name="email"><br>
        <input type="submit">
      </form>
    </div>
  </div>
  <!-- End page content -->';
  else{
    include('connect_to_sql.php');
    $exam = mysql_query("select * from data ORDER BY RAND() LIMIT $_GET[number]");
    for($i = 1;$i <= mysql_num_rows($exam);$i++){
      $rs = mysql_fetch_assoc($exam);
    echo '<div class="w3-row w3-grayscale">
      <div class="w3-col l6 s6">
        <div class="w3-container">
          <div class="w3-display-container">
            <audio id= "'.$rs[audio_id].'">
              <source src="'.$rs[sound_src].'" type="audio/mp3" />
              <embed height="100" width="100" src="'.$rs[sound_src].'" />
            </audio>
            <button class="w3-button w3-black " onclick="document.getElementById(\''.$rs[audio_id].'\').play(); return false;">Play</button>
          </div>
          <p align = "center">'.$rs[name].'</p>
        </div>
      </div>
    </div>';

    }
  }
  ?>

</body>
</html>
