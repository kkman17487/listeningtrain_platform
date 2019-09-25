<?php
session_start();
include("connect_to_sql.php");
if(isset($_GET['number']) && (isset($_GET['no']) && $_GET['no'] == 0))
{
  $_SESSION['star'] = array();
  unset($_SESSION['data']);
  unset($_SESSION['read']);
  $_SESSION['data'] = $con->query("SELECT * FROM data ORDER BY RAND() LIMIT $_GET[number]");
  for($i = 0;$i < mysqli_num_rows($_SESSION['data']);$i++)
  {
    $_SESSION['read'][$i] = mysqli_fetch_assoc($_SESSION['data']);
  }
}
elseif(isset($_GET['ID']) && (isset($_GET['no']) && $_GET['no'] == 0))
{
  $_SESSION['star'] = array();
  unset($_SESSION['data']);
  unset($_SESSION['read']);
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
  for($i = 0;$i < mysqli_num_rows($_SESSION['data']);$i++)
  {
    $_SESSION['read'][$i] = mysqli_fetch_assoc($_SESSION['data']);
  }
}
elseif(isset($_GET['no']) && $_GET['no'] > 0)
{
  array_push($_SESSION['select_answer'],array($_POST['answer'],$_POST['time']));
}
elseif(isset($_GET['checkanswer']))
{
  array_push($_SESSION['select_answer'],array($_POST['answer'],$_POST['time']));
}
?>

<html>

<?php include('sidebar.php'); ?>
  <!-- Top header -->
  <header class="w3-container w3-xlarge">
    <p class="w3-left">測驗</p>
  </header>
  <!-- Image header -->
  <?php
  $numberOFdata = $con->query("select * from data");
  $numberOFdata = mysqli_num_rows($numberOFdata);
  if(!isset($_GET['number']) && !isset($_GET['ID'])){
    echo '<div class="w3-display-container w3-container">
    <img src="../picture/test2.jpg" alt="Photo" style="width:100%">
    <div class="w3-display-topleft w3-text-white" style="padding:24px 48px">
      <h1 class="w3-hide-small">系統出題</h1>
      <form action="exambysystem.php" method="get" id="question_number">
        題數: <input type="text" maxlength="2" size="2" name="number" id="number">有效範圍：1~'.$numberOFdata.'<br>
        <input name="no" value="0" hidden>
        <input type="submit" value="提交">
      </form>
	  <br></br>
      <h1 class="w3-hide-small">題庫選擇</h1>
	  <div class="CSSTableGenerator">
            <table align="center" style="color:white;">
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
                  <td width="5%"><a href="exambysystem.php?ID='.$rs['id'].'&no=0">'.$rs['id'].'</a></td>
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
  <!-- End page content -->';
  }
  elseif((isset($_GET['number']) || isset($_GET['ID'])) && !isset($_GET['checkanswer'])){
    if(isset($_GET['no']) && $_GET['no'] == 0)
    {
      unset($_SESSION['correct_answer']);
      unset($_SESSION['select_answer']);
      $_SESSION['correct_answer'] = array();
      $_SESSION['select_answer'] = array(array());
    }
    //echo '<div class="w3-row">';
    echo $_GET['no']+1.0.'/'.sizeof($_SESSION['read']);
    $next = $_GET['no'] + 1;
    //print_r($_SESSION['correct_answer']);
    if($_GET['no'] > 0 && $_SESSION['correct_answer'][$_GET['no']-1] != $_POST['answer'])
    {
      //echo 'incorrect'.$_SESSION['correct_answer'][$_GET['no']-1].$_POST['answer'];
      $_SESSION['star'][$_GET['no']-1] = "紅色星星.png";
    }
    elseif($_GET['no'] > 0 && $_SESSION['correct_answer'][$_GET['no']-1] == $_POST['answer'])
    {
      //echo 'correct'.$_SESSION['correct_answer'][$_GET['no']-1].$_POST['answer'];
      $_SESSION['star'][$_GET['no']-1] = "綠色星星.png";
    }
    for($i = 0;$i < count($_SESSION['star']);$i++)
    {
      echo '<img height=50 width=50 src="../picture/'.$_SESSION['star'][$i].'">';
    }
    for($i = 0;$i < sizeof($_SESSION['read']) - count($_SESSION['star']);$i++)
    {
      echo '<img height=50 width=50 src="../picture/透明星星.png">';
    }
    if($_GET['no'] == (sizeof($_SESSION['read'])-1))
    {
      if(isset($_GET['ID']))
        echo '<form name="answer" method="post" action="exambysystem.php?ID='.$_GET['ID'].'&checkanswer=true">';
      elseif(isset($_GET['number']))
        echo '<form name="answer" method="post" action="exambysystem.php?number='.$_GET['number'].'&checkanswer=true">';
    }
    elseif(isset($_GET['number']))
      echo '
      <form name="answer_sheet" id="answer_sheet" method="post" action="exambysystem.php?number='.$_GET['number'].'&no='.$next.'">';
    elseif(isset($_GET['ID']))
      echo '<form name="answer_sheet" id="answer_sheet" method="post" action="exambysystem.php?ID='.$_GET['ID'].'&no='.$next.'">';
    echo '<input type="text" name="time" id="time" hidden>';
    $rs = $_SESSION['read'][$_GET['no']];
    echo '
            <audio id= "'.$rs['audio_id'].'">
              <source src="'.$rs['sound_src'].'" type="audio/mp3" />
              <embed height="100" width="100" src="'.$rs['sound_src'].'" />
            </audio>
            <button class="w3-button w3-black " onclick="document.getElementById(\''.$rs['audio_id'].'\').play(); set_timer(); enable_radio(); return false;">Play</button><br>';
          $randanswer = $con->query("select * from data WHERE id != $rs[id] ORDER BY RAND() LIMIT 3");
          $answer = array(array());
          for($k = 1;$k <= mysqli_num_rows($randanswer);$k++)
          {
            $rr = mysqli_fetch_assoc($randanswer);
            array_push($answer,array($rr['name'],$rr['pic_src']));
          }
          array_push($_SESSION['correct_answer'],$rs['name']);
          array_push($answer,array($rs['name'],$rs['pic_src']));
          //print_r($answer);
          $answer = array_filter($answer);
          //print_r($answer);
          shuffle($answer);
          //print_r($answer);
              for($j = 0;$j < 4;$j++){
                if($j%2==0)echo '<div class="w3-row">';
                echo '<div class="w3-col l6 s6">
                  <div class="w3-container">
                    <div class="w3-display-container">';
              echo '<input onclick="enable_submit();" type="radio" id="answer'.$j.'" name="answer" value="'.$answer[$j][0].'" disabled>'.$answer[$j][0].'<br><img height="30%" width="68%" src="'.$answer[$j][1].'"></div></div></div>';
              //print_r($rs);
              if($j%2==1)echo '</div>';
            }
    if($_GET['no'] == (sizeof($_SESSION['read'])-1))
      echo '<br><input type="submit" onclick="<script>btn=0;</script>" name="submit" id="submit" value="提交" align="center" disabled></form></div>';
    else
      echo '<br><input type="submit" onclick="<script>btn=0;</script>" name="submit" id="submit" value="下一題" align="center" disabled></form></div>';
  }
  elseif((isset($_GET['number']) || isset($_GET['ID'])) && isset($_GET['checkanswer'])){
    $correct_answer = $_SESSION['correct_answer'];
    $_SESSION['select_answer'] = array_filter($_SESSION['select_answer']);
    $data_string = '';
    $correct_number = 0;
    for($i = 0;$i < sizeof($correct_answer);$i++){
      $tmp = $con->query("select * from data where name = '$correct_answer[$i]'");
      $rs = mysqli_fetch_assoc($tmp);
      $data_string .= $correct_answer[$i].'/'.$_SESSION['select_answer'][$i+1][0].'/'.strval($_SESSION['select_answer'][$i+1][1]).';';
      if($i % 4 == 0)echo '<div class="w3-row">';
      echo '<div class="w3-col l3 s6">
        <div class="w3-container">
          <div class="w3-display-container">';
      echo '<audio id= "'.$rs['audio_id'].'">
        <source src="'.$rs['sound_src'].'" type="audio/mp3" />
        <embed height="100" width="100" src="'.$rs['sound_src'].'" />
      </audio>
      <button class="w3-button w3-black" onclick="document.getElementById(\''.$rs['audio_id'].'\').play(); return false;">再聽一次</button>';
      echo '<p>您第'.($i+1);
      echo '題的答案:'.$_SESSION['select_answer'][$i+1][0].'<br>時間:'.$_SESSION['select_answer'][$i+1][1].'</p>';
      if($correct_answer[$i] != $_SESSION['select_answer'][$i+1][0])
        echo '<p style="color:red;">錯誤！</p>正確答案:'.$correct_answer[$i].'<img height="150" width="200" src="'.$rs['pic_src'].'"><br>';
      else
      {
        echo '<p style="color:green;">正確！！！</p><img height="150" width="200" src="'.$rs['pic_src'].'"><br>';
        $correct_number++;
      }
      echo '</div></div></div>';
      if($i % 4 == 3 || $i == sizeof($correct_answer)-1)echo '</div><br>';
    }
    $data_string = substr($data_string,0,strlen($data_string)-1);
    if(isset($_SESSION['name']))
      $name = $_SESSION['name'];
    else
      $name = "訪客";
    $time = 0;
    for($i = 1;$i <= sizeof($_SESSION['select_answer']);$i++)
    {
      $time += $_SESSION['select_answer'][$i][1];
      //echo $time.' ';
    }
    $time /= sizeof($_SESSION['select_answer']);
    //echo $time.' '.sizeof($_SESSION['select_answer']);
    $correct_rate = $correct_number * 100 / sizeof($correct_answer);
    //echo $correct_rate.' '.$correct_number.' '.sizeof($correct_answer);
    $res = $con->query("INSERT INTO `history` (`id`, `name`, `data`, `correct`, `time`) VALUES (NULL, '$name', '$data_string', round($correct_rate,0), round($time,3))");
    if (!$res) {
    die('Invalid query: ' . mysqli_error($con));
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
<script>
var btn = 0;
var time;
function set_timer()
{
  if(!btn)
  {
    time = new Date();
    timer();
  }
  btn = 1;
}
function timer()
{
  var timeDiff = new Date() - time;
  var timestr = new Date(timeDiff).getSeconds();
  timestr += ".";
  timestr += new Date(timeDiff).getMilliseconds();
  document.getElementById('time').value = timestr;
  var t=setTimeout("timer()",1);
}
function enable_radio()
{
  document.getElementById("answer0").disabled = false;
  document.getElementById("answer1").disabled = false;
  document.getElementById("answer2").disabled = false;
  document.getElementById("answer3").disabled = false;
}
function enable_submit()
{
  document.getElementById("submit").disabled = false;
}
document.getElementById('question_number').addEventListener('submit', function(event){
  var number = parseInt(document.getElementById('number').value);
  var numberOFdata = <?php echo $numberOFdata; ?>;
  if(!Number.isInteger(number) || number <= 0 || number > numberOFdata)
  {
    event.preventDefault();
    alert("您輸入："+ number +"有效範圍為：" + 1 + "~" + numberOFdata);
    return false;
  }
});
</script>
</body>
</html>
