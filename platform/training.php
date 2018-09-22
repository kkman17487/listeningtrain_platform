<!DOCTYPE html>
<?php
include('connect_to_sql.php');
$data = $con->query("select * from train");
include('sidebar.php');
?>
<html>
  <!-- Top header -->
  <header class="w3-container w3-xlarge">
    <p class="w3-left">教材選擇</p>
    <!--<p class="w3-right">
      <i class="fa fa-shopping-cart w3-margin-right"></i>
      <i class="fa fa-search"></i>
    </p>-->
  </header>
  <body>
  <?php
  if(!isset($_GET['ID'])){
    echo '<div class="w3-display-container w3-container">
    <img src="../picture/test2.jpg" alt="Photo" style="width:100%">
    <div class="w3-display-topleft w3-text-white" style="padding:24px 48px">
      <h1 class="w3-jumbo w3-hide-small">教材選擇</h1>
      <h1 class="w3-hide-small">教材選擇</h1>
      <div class="container">
        <div class="CSSTableGenerator">
            <table align="center">
                  <tr>
                    <td width="5%">ID</td>
                    <td width="10%">名稱</td>
                    <td width="15%">題數</td>
                    <td width="10%">作者</td>
                    <td width="60%">最近修改時間</td>
                  </tr>';
    $data = $con->query("SELECT * FROM train");
    for($i=1;$i<=mysqli_num_rows($data);$i++){
     $rs=mysqli_fetch_assoc($data);
     $rs_question = explode(',',$rs['question']);
                echo '<tr>
                  <td width="5%"><a href="training.php?ID='.$rs['id'].'">'.$rs['id'].'</a></td>
                  <td width="10%">'.$rs['name'].'</td>
                  <td width="15%">'.sizeof($rs_question).'</td>
                  <td width="10%">'.$rs['creator'].'</td>
                  <td width="60%">'.$rs['recent_edit_time'].'</td>
                </tr>';
    }
    echo '</table>
    </div>
    </div>
    </div>
  </div>
  <!-- End page content -->';
  }
  else{
    ?>
  <div class="w3-container w3-text-grey" id="sounds">
    <form>
      <select onChange="location = 'training.php?sound_type=' + this.options[this.selectedIndex].value;">
        <option value="#">全部</option>
        <?php
        $classfication = array();
        for($j = 1;$j <= mysqli_num_rows($tag);$j++){
          $rs_tag = mysqli_fetch_assoc($tag);
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
    <p>一共有<?php echo mysqli_num_rows($data);?>筆資料</p>
  </div>

  <!-- Product grid -->
    <?php
    for($i = 1;$i <= mysqli_num_rows($data);$i++){
      $rs = mysqli_fetch_assoc($data);
      if($i % 4 == 1)echo '<div class="w3-row">';
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
    if($i % 4 == 0 || $i == mysqli_num_rows($data))echo '</div>';
  }
}
  ?>
  <!-- End page content -->

</body>
</html>
