<!DOCTYPE html>
<?php
session_start();
if(isset($_GET['number']) && !isset($_GET['checkanswer']))
{
  session_unset();
  include('connect_to_sql.php');
  $_SESSION['data'] = mysql_query("select * from data ORDER BY RAND() LIMIT $_GET[number]");
}?>
<html>
<?php include('sidebar.php'); ?>

  <!-- Top header -->
  <header class="w3-container w3-xlarge">
    <p class="w3-left">系統出題</p>
  </header>

  <!-- Image header -->
  <?php
  if(!isset($_GET['number']))
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
  else if(isset($_GET['number']) && !isset($_GET['checkanswer'])){
    echo '<div class="w3-row w3-grayscale">
    <form name="answer" method="post" action="exambysystem.php?number='.$_GET['number'].'&checkanswer=true">';
    for($i = 1;$i <= mysql_num_rows($_SESSION['data']);$i++){
      $rs = mysql_fetch_assoc($_SESSION['data']);
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
          $randanswer = mysql_query("select * from data WHERE id != $rs[id] ORDER BY RAND() LIMIT 2");
          $answer = array();
          for($k = 1;$k <= mysql_num_rows($randanswer);$k++)
          {
            $rr = mysql_fetch_assoc($randanswer);
            array_push($answer,$rr['name']);
          }
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
  elseif(isset($_GET['number']) && isset($_GET['checkanswer'])){
    for($i = 1;$i <= $_GET['number'];$i++)
      print_r($_POST['answer'.$i]);

    /*for($i = 1;$i <= mysql_num_rows($_SESSION['data']);$i++){
      $rs = mysql_fetch_assoc($_SESSION['data']);
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
              $randanswer = mysql_query("select * from data ORDER BY RAND() LIMIT 2");
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
