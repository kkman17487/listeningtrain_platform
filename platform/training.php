<!DOCTYPE html>
<?php
session_start();
include('connect_to_sql.php');
$name;
if(!isset($_GET['ID']))
{
unset($_SESSION['question']);
$data = $con->query("select * from train");
}
else {
  $data = $con->query("select * from train WHERE ID = '$_GET[ID]'");
  $rs = mysqli_fetch_assoc($data);
  $name = $rs['name'];
  $_SESSION['question'] = explode(',',$rs['question']);
}
include('sidebar.php');
?>
<html>
  <!-- Top header -->
  <header class="w3-container w3-xlarge">
    <?php
      if(!isset($_GET['ID']))
        echo '<p class="w3-left">訓練模式</p>';
      else
        echo '<p class="w3-left">教材'.$_GET['ID'].':'.$name.'</p>';
    ?>
  </header>
  <body>
  <?php
  if(!isset($_GET['ID'])){
    echo '<div class="w3-display-container w3-container">
    <img src="../picture/test2.jpg" alt="Photo" style="width:100%">
    <div class="w3-display-topleft w3-text-white" style="padding:24px 48px">
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
    for($i=1;$i<=mysqli_num_rows($data);$i++){
     $rs=mysqli_fetch_assoc($data);
     $rs_question = explode(',',$rs['question']);
                echo '<tr>
                  <td width="5%"><a href="training.php?ID='.$rs['id'].'&no=0">'.$rs['id'].'</a></td>
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
      $no = $_GET['no'];
      $ID = $_SESSION['question'][$no];
      $data = $con->query("select * from data where ID = '$ID'");
      $rs = mysqli_fetch_assoc($data);
      $previous = $_GET['no'] - 1;
      $next = $_GET['no'] + 1;
    ?>
    <div class="w3-col l12 s12">
      <div class="w3-container">
        <div class="w3-display-container">
            <?php echo $_GET['no']+1.0.'/'.sizeof($_SESSION['question']);?>
			<br>
          <audio id='<?php echo $rs['audio_id']?>'>
            <source src="<?php echo $rs['sound_src']?>" type="audio/mp3" />
            <embed height="100" width="100" src="<?php echo $rs['sound_src']?>" />
          </audio>
          <img src="<?php echo $rs['pic_src']?>" height="65%" width="85%" align="center"/>
        <?php 
          //if()
            //echo '<span class="w3-tag w3-display-topleft">New</span>'?>
          <div class="w3-display-middle w3-display-hover">
			<button class="w3-button w3-black" onclick="document.getElementById('<?php echo $rs['audio_id']?>').play(); return false;">Play</button>
          </div>
        </div>
        <p align="left"><strong><?php echo $rs['name']?></strong></p>
        <button onclick="location.href='training.php?ID=<?php echo $_GET['ID']?>&no=<?php echo $previous?>'" type="button" <?php if($previous<0) echo disabled?>>上一題</button>
        <button onclick="location.href='training.php?ID=<?php echo $_GET['ID']?>&no=<?php echo $next?>'" type="button" <?php if($next>=sizeof($_SESSION['question'])) echo disabled?>>下一題</button>
      </div>
  </div>
<?php
}
  ?>
  <!-- End page content -->

</body>
</html>
