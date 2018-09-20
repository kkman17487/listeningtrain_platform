<!DOCTYPE html>
<?php
session_start();
if(isset($_GET['number']) && !isset($_GET['checkanswer']))
{
  session_unset();
  include('connect_to_sql.php');
  $_SESSION['data'] = $con->query("select * from data ORDER BY RAND() LIMIT $_GET[number]");
}
elseif(isset($_GET['ID']) && !isset($_GET['checkanswer']))
{
  session_unset();
  include('connect_to_sql.php');
  $ID = $_GET['ID'];
  $question = $con->query("SELECT * FROM exam WHERE id = '$ID'");
  $rs_question = mysqli_fetch_assoc($question);
  $tmp_question = explode(',',$rs_question['question']);
  $sql = "SELECT * FROM data WHERE ";
  foreach($tmp_question as $key => $value)
  {
  $sql .= "id = '$value' OR ";
  }
  $sql = substr($sql,0,-4);
  $sql .= " ORDER BY RAND()";
  $_SESSION['data'] = $con->query($sql);
}
?>
<html>
<?php include('sidebar.php'); ?>

  <!-- Top header -->
  <header class="w3-container w3-xlarge">
    <p class="w3-left">系統出題</p>
  </header>

  <!-- Image header -->
  <?php
  include("connect_to_sql.php");
  if(!isset($_GET['number']) && !isset($_GET['ID'])){
    echo '<div class="w3-display-container w3-container">
    <img src="../picture/test2.jpg" alt="Photo" style="width:100%">
    <div class="w3-display-topleft w3-text-white" style="padding:24px 48px">
      <h1 class="w3-jumbo w3-hide-small">系統出題</h1>
      <h1 class="w3-hide-small">系統出題</h1>
      <form action="exambysystem.php" method="get">
        題數: <input type="text" maxlength="2" size="2" name="number"><br>
        <input type="submit">
      </form>
      <h1 class="w3-hide-small">題庫選擇</h1>
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
    $data = $con->query("SELECT * FROM exam");
    for($i=1;$i<=mysqli_num_rows($data);$i++){
     $rs=mysqli_fetch_assoc($data);
     $rs_question = explode(',',$rs['question']);
                echo '<tr>
                  <td width="5%"><a href="exambysystem.php?ID='.$rs['id'].'">'.$rs['id'].'</a></td>
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
  elseif((isset($_GET['number']) || isset($_GET['ID'])) && !isset($_GET['checkanswer'])){
    $_SESSION['correct_answer'] = array();
    echo '<div class="w3-row w3-grayscale">';
    if(isset($_GET['number']))
    echo '
    <form name="answer" method="post" action="exambysystem.php?number='.$_GET['number'].'&checkanswer=true">';
    elseif(isset($_GET['ID']))
    echo '<form name="answer" method="post" action="exambysystem.php?ID='.$_GET['ID'].'&checkanswer=true">';
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
          $randanswer = $con->query("select * from data WHERE id != $rs[id] ORDER BY RAND() LIMIT 3");
          $answer = array();
          for($k = 1;$k <= mysqli_num_rows($randanswer);$k++)
          {
            $rr = mysqli_fetch_assoc($randanswer);
            array_push($answer,$rr['name']);
            array_push($img,$rr['img_src']);
          }
          array_push($_SESSION['correct_answer'],$rs['name']);
          array_push($answer,$rs['name']);
          shuffle($answer);
              for($j = 0;$j < 4;$j++){

              echo '<td><input type="radio" id="answer'.$i.'" name="answer'.$i.'" value="'.$answer[$j].'">'.$answer[$j].'</td><img src="$img[$j]">';
              //print_r($rs);
            }
          echo '
        </tr>
        </table>';


    }
    echo '<br><input type="submit" name="submit" align="center"></form></div>';
  }
  elseif((isset($_GET['number']) || isset($_GET['ID'])) && isset($_GET['checkanswer'])){
    $correct_answer = $_SESSION['correct_answer'];
    for($i = 0;$i < sizeof($correct_answer);$i++){
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
