<!DOCTYPE html>
<?php
session_start();
if(isset($_GET['number']) && !isset($_GET['checkanswer']))
{
  session_unset();
  include('connect_to_sql.php');
  $_SESSION['data'] = $con->query("select * from data ORDER BY RAND() LIMIT $_GET[number]");
}
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css">
<style>
.top{
 margin:auto;
 width:60vw;
 text-align:right;
 padding:15vh 0 0 0;
 font-family:微軟正黑體;
}
/*.nav{
 background-color:#339;
 padding: 10px 0px;
 }*/
.nav a {
  color: #5a5a5a;
  font-size: 11px;
  font-weight: bold;
  text-transform: uppercase;
}

.nav li {
  display: inline;
}
 .CSSTableGenerator {
 margin:auto;
 padding:0px;
 width:60vw;
 }
 .CSSTableGenerator table{
    border-collapse: collapse;
    border-spacing: 0;
 width:100%;
 height:100%;
 margin:0px;padding:0px;
}.CSSTableGenerator tr:last-child td:last-child {
 -moz-border-radius-bottomright:9px;
 -webkit-border-bottom-right-radius:9px;
 border-bottom-right-radius:9px;
}
.CSSTableGenerator table tr:first-child td:first-child {
 -moz-border-radius-topleft:9px;
 -webkit-border-top-left-radius:9px;
 border-top-left-radius:9px;
}
.CSSTableGenerator table tr:first-child td:last-child {
 -moz-border-radius-topright:9px;
 -webkit-border-top-right-radius:9px;
 border-top-right-radius:9px;

}.CSSTableGenerator tr:last-child td:first-child{
 -moz-border-radius-bottomleft:9px;
 -webkit-border-bottom-left-radius:9px;
 border-bottom-left-radius:9px;

}/*.CSSTableGenerator tr:hover td{
 background-color:#005fbf;
 color:white;
}*/
.CSSTableGenerator td{
 vertical-align:middle;
 background-color:#e5e5e5;
 border:1px solid #999999;
 border-width:0px 1px 1px 0px;
 text-align:left;
 padding:8px;
 font-size:16px;
 font-family:Arial,微軟正黑體;
 font-weight:normal;
 color:#000000;
}.CSSTableGenerator tr:last-child td{
 border-width:0px 1px 0px 0px;
}.CSSTableGenerator tr td:last-child{
 border-width:0px 0px 1px 0px;
}.CSSTableGenerator tr:last-child td:last-child{
 border-width:0px 0px 0px 0px;
}
.CSSTableGenerator tr:first-child td{
  background:-o-linear-gradient(bottom, #005fbf 5%, #005fbf 100%);
  background:-webkit-gradient( linear, left top, left bottom, color-stop(0.05, #005fbf), color-stop(1, #005fbf) );
  background:-moz-linear-gradient( center top, #005fbf 5%, #005fbf 100% );
  filter:progid:DXImageTransform.Microsoft.gradient(startColorstr="#005fbf", endColorstr="#005fbf");
  background: -o-linear-gradient(top,#005fbf,005fbf);
  background-color:#005fbf;
  text-align:center;
  font-size:20px;
  font-family:Arial, 微軟正黑體;
  font-weight:bold;
  color:#ffffff;
}
.CSSTableGenerator tr:first-child:hover td{
  background:-o-linear-gradient(bottom, #005fbf 5%, #005fbf 100%);
  background:-webkit-gradient( linear, left top, left bottom, color-stop(0.05, #005fbf), color-stop(1, #005fbf) );
  background:-moz-linear-gradient( center top, #005fbf 5%, #005fbf 100% );
  filter:progid:DXImageTransform.Microsoft.gradient(startColorstr="#005fbf", endColorstr="#005fbf");
  background: -o-linear-gradient(top,#005fbf,005fbf);
  background-color:#005fbf;
}


</style>
</head>
<?php include('sidebar.php'); ?>

  <!-- Top header -->
  <header class="w3-container w3-xlarge">
    <p class="w3-left">測驗模式</p>
  </header>

  <!-- Image header -->
  <?php
  if(!isset($_GET['number'])){
  ?>
    <div class="w3-display-container w3-container">
    <img src="../picture/test1.jpg" alt="Jeans" style="width:100%">
    <div class="w3-display-topleft w3-text-white" style="padding:24px 48px">
      <h1 class="w3-jumbo w3-hide-small">測驗模式</h1>
      <h1 class="w3-hide-small">系統出題</h1>
      <form action="exambysystem.php" method="get">
        題數: <input type="text" maxlength="2" size="2" name="number"><br>
        <input type="submit">
      </form>
      <h1 class="w3-hide-small">題庫選擇</h1>
      <?php
      include("connect_to_sql.php");
      $data = $con->query("select * from exam");
      ?>
        <div class="container">
          <div class="CSSTableGenerator">
              <table align="center">
                    <tr>
                      <td width="10%">ID</td>
                      <td width="10%">名稱</td>
                      <td width="10%">題數</td>
                      <td width="10%">作者</td>
                      <td width="60%">最近修改時間</td>
                    </tr>
      <?php
      for($i=1;$i<=mysqli_num_rows($data);$i++){
       $rs=mysqli_fetch_assoc($data);
      ?>

                  <tr>
                    <td width="10%"><a href="exambysystem.php?ID=<?php echo $rs['id'];?>"><?php echo $rs['id'];?></a></td>
                    <td width="10%"><?php echo $rs['name'];?></td>
                    <td width="10%"><?php echo sizeof(explode(',',$rs['question']))+1?></td>
                    <td width="10%"><?php echo $rs['creator'];?></td>
                    <td width="60%"><?php echo $rs['recent_edit_time'];?></td>
                  </tr>
      <?php } ?>
      </table>
      </div>
      </div>
    </div>
  </div>
  <!-- End page content -->

  <?php}
  else if(isset($_GET['number']) && !isset($_GET['checkanswer'])){
    $_SESSION['correct_answer'] = array();
    echo '<div class="w3-row w3-grayscale">
    <form name="answer" method="post" action="exambysystem.php?number='.$_GET['number'].'&checkanswer=true">';
    for($i = 1;$i <= mysqli_num_rows($_SESSION['data']);$i++){
      $rs = mysqli_fetch_assoc($_SESSION['data']);
    echo '
      <table>
      <tr>
      <td>
            <audio id= "'.$rs[audio_id].'">
              <source src="'.$rs[sound_src].'" type="audio/mp3" />
              <embed height="100" width="100" src="'.$rs[sound_src].'" />
            </audio>
            <button class="w3-button w3-black " onclick="document.getElementById(\''.$rs[audio_id].'\').play(); return false;">Play</button>
      </td>
      </tr>
          <tr>';
          $randanswer = $con->query("select * from data WHERE id != $rs[id] ORDER BY RAND() LIMIT 2");
          $answer = array();
          for($k = 1;$k <= mysqli_num_rows($randanswer);$k++)
          {
            $rr = mysqli_fetch_assoc($randanswer);
            array_push($answer,$rr['name']);
          }
          array_push($_SESSION['correct_answer'],$rs['name']);
          array_push($answer,$rs['name']);
          shuffle($answer);
              for($j = 0;$j < 3;$j++){

              echo '<td><input type="radio" id="answer'.$i.'" name="answer'.$i.'" value="'.$answer[$j].'">'.$answer[$j].'</td>';
              //print_r($rs);
            }
          echo '
        </tr>
        </table>';


    }
    echo '<br><input type="submit" name="submit" align="center"></form></div>';
  }
  else if(isset($_GET['number']) && isset($_GET['checkanswer'])){
    $correct_answer = $_SESSION['correct_answer'];
    for($i = 0;$i < $_GET['number'];$i++){
      include('connect_to_sql.php');
      $tmp = $con->query("select * from data where name = '$correct_answer[$i]'");
      $rs = mysqli_fetch_assoc($tmp);
      echo '<audio id= "'.$rs[audio_id].'">
        <source src="'.$rs[sound_src].'" type="audio/mp3" />
        <embed height="100" width="100" src="'.$rs[sound_src].'" />
      </audio>
      <button class="w3-button w3-black" onclick="document.getElementById(\''.$rs[audio_id].'\').play(); return false;">再聽一次</button>';
      echo '<p>您第'.($i+1);
      echo '題的答案:'.$_POST['answer'.($i+1)].'</p>';
      if($correct_answer[$i] != $_POST['answer'.($i+1)])
        echo '錯誤！正確答案:'.$correct_answer[$i].'<br>';
      else
        echo '正確！！！<br>';
    }
    /*for($i = 1;$i <= mysqli_num_rows($_SESSION['data']);$i++){
      $rs = mysqli_fetch_assoc($_SESSION['data']);
    echo '
      <table>
      <tr>
      <td>
            <audio id= "'.$rs[audio_id].'">
              <source src="'.$rs[sound_src].'" type="audio/mp3" />
              <embed height="100" width="100" src="'.$rs[sound_src].'" />
            </audio>
            <button class="w3-button w3-black " onclick="document.getElementById(\''.$rs[audio_id].'\').play(); return false;">Play</button>
      </td>
      </tr>
          <tr>';
              for($j = 1;$j <=3;$j++){
              $randanswer = $con->query("select * from data ORDER BY RAND() LIMIT 2");
              echo '<td><input type="radio" name="answer'.$i.'" value="'.$rs[name].'">'.$rs[name].'</td>';
            }
          echo '
        </tr>
        </table>';


    }*/
  }
  ?>

</body>
</html>
