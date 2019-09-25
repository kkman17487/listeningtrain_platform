<?php
/*
這是用來作情境測驗模式的檔案目前最上方是用來做題目的初始化和紀錄作答
*/
session_start();                                                                                       //開始使用Session紀錄
include("connect_to_sql.php");                                                                         //連上資料庫
if(isset($_GET['ID']) && (isset($_GET['no']) && $_GET['no'] == 0) && isset($_GET['number']))           //ID確認是哪個情境no確認目前題數number確認總題數
{
  $_SESSION['star'] = array();                                                                         //紀錄星星
  unset($_SESSION['data']);                                                                            //初始化題目
  unset($_SESSION['read']);                                                                            //初始化各題的答案
  $ID = $_GET['ID'];                                                                                   //紀錄傳來的情境
  $question = $con->query("SELECT * FROM enviro WHERE id = '$ID'");                                    //讀出此情境全部的資料
  $rs_question = mysqli_fetch_assoc($question);                                                        //取出一行情境
  $tmp_question = explode(',',$rs_question['object']);                                                 //分割情境中的物件
  $sql = "SELECT * FROM data WHERE ";                                                                  //sql為字串紀錄準備讀出物件對應的聲音
  foreach($tmp_question as $key => $value)                                                             //用FOR讀每個物件內的sound_src
  {
	  $sdq=$con->query("SELECT * FROM object WHERE id ='$value'");                                     //選出對應物件
	  $rs_sdq=mysqli_fetch_assoc($sdq);                                                                //取出此行物建
  $sql .= "id = '{$rs_sdq['sound_src']}' OR ";                                                         //將sql串接上對應的sound_src
  }
  $sql = substr($sql,0,-4);                                                                            //將最後的OR剪掉
  $sql .= " ORDER BY RAND() LIMIT {$_GET['number']}";                                                 //題數限制在選擇範圍內
  $_SESSION['data'] = $con->query($sql);                                                               //儲存題目
  for($i = 0;$i < mysqli_num_rows($_SESSION['data']);$i++)
  {
    $_SESSION['read'][$i] = mysqli_fetch_assoc($_SESSION['data']);                                     //將每行題目個別拿出
  }
}
elseif(isset($_GET['no']) && $_GET['no'] > 0)                                                         //當no大於0就不是要初始化要紀錄答案和時間
{
	$ID = $_GET['ID'];   
	$question = $con->query("SELECT * FROM enviro WHERE id = '$ID'");                                    //讀出此情境全部的資料
  $rs_question = mysqli_fetch_assoc($question);
  array_push($_SESSION['select_answer'],array($_POST['answer'],$_POST['time']));                      //將選擇的答案和作答時間紀錄
}
elseif(isset($_GET['checkanswer']))
{
  array_push($_SESSION['select_answer'],array($_POST['answer'],$_POST['time']));
}
/*
初始化設定結束
*/
?>

<html>
<style>
.object{
  position: absolute;
  background-color: transparent;
  color: black;
  font-size: 10px;
  padding: 8px 15px;
  border: none;
  cursor: pointer;
  border-radius: 5px;
  text-align: center;
	}
	#Canvasobject
	{
		z-index: 2;
		position: absolute;
	}
	#_2DCanvas
	{
		z-index: 1;
		position: absolute;
	}
	#env
	{
		position: relative;
	}
	
</style>
<?php include('sidebar.php'); ?>
  <!-- Top header -->
  <header class="w3-container w3-xlarge"><!-- header的設定 -->
    <p class="w3-left">測驗</p>
  </header>
  <!-- Image header -->
  <?php
  $numberOFdata = $con->query("SELECT * FROM enviro WHERE id = '{$_GET['ID']}'");                   //準備用來記錄此情境有多少物件
  $tmpnumberOFobject=mysqli_fetch_assoc($numberOFdata);                                             //取出此列情境
  $numberOFdata = count(explode(',',$tmpnumberOFobject['object']));                                 //計算物件數量
  /*設定題數的部分*/
  if(!isset($_GET['number']) && isset($_GET['ID'])){                                                //是否設定題數沒有就是顯示選擇題數的畫面
    echo "<div class=\"w3-display-container w3-container\">                                          <!--用div分出顯示區域-->
    <img src=\"../picture/test2.jpg\" alt=\"Photo\" style=\"width:100%\">                            <!--背景圖-->
    <div class=\"w3-display-topleft w3-text-white\" style=\"padding:24px 48px\">                     <!--用div分出顯示區域-->
      <h1 class=\"w3-hide-small\">選擇題數</h1>                                                      <!--標題-->
      <form action=\"examenvirobysystem.php\" method=\"get\" id=\"question_number\">                 <!--表單傳輸-->
	    <input type=\"hidden\" name=\"ID\" value=\"{$_GET['ID']}\"></input>                          <!--紀錄情境-->
  題數: <input type=\"text\" maxlength=\"2\" size=\"2\" name=\"number\" id=\"number\">有效範圍：1~{$numberOFdata}<br>   <!--紀錄題數-->
        <input name=\"no\" value=\"0\" hidden>                                                       <!--從第一題開始-->
        <input type=\"submit\" value=\"提交\">                                                       <!--提交表單-->
      </form>
	  <br></br>
	  </div>
	  </div>
      ";
    }
	/*設定題數的部分結束*/
	/*設定每題顯示畫面，播放的聲音*/
  elseif((isset($_GET['number']) && isset($_GET['ID'])) && !isset($_GET['checkanswer'])){           //是否設定題數和情境ID以及是否作答完畢
    if(isset($_GET['no']) && $_GET['no'] == 0)
    {
      unset($_SESSION['correct_answer']);
      unset($_SESSION['select_answer']);
      $_SESSION['correct_answer'] = array();
      $_SESSION['select_answer'] = array(array());
    }
    //echo '<div class="w3-row">';
	$one='1';
    echo $_GET['no']+$one."/".sizeof($_SESSION['read']);
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
        echo '<form name="answer" method="post" action="exambysystem.php?ID='.$_GET['ID'].'&number='.$_GET['number'].'&checkanswer=true">';
    }
    elseif(isset($_GET['number'])&&isset($_GET['ID']))
      echo '
      <form name="answer_sheet" id="answer_sheet" method="post" action="examenvirobysystem.php?ID='.$_GET['ID'].'&number='.$_GET['number'].'&no='.$next.'">';
    echo '<input type="text" name="time" id="time" hidden>';
	echo "<input type=\"hidden\" id=\"sendanswer\" name=\"answer\" value=\"\" >";
    $rs = $_SESSION['read'][$_GET['no']];
    echo '
            <audio id= "'.$rs['audio_id'].'">
              <source src="'.$rs['sound_src'].'" type="audio/mp3" />
              <embed height="100" width="100" src="'.$rs['sound_src'].'" />
            </audio>
            <button class="w3-button w3-black " type="button" onclick="document.getElementById(\''.$rs['audio_id'].'\').play(); set_timer(); enable_radio(); return false;">Play</button><br>';
			array_push($_SESSION['correct_answer'],$rs['name']);
     echo"   
	    <div class=\"container\" id=\"env\">
        <canvas id=\"_2DCanvas\" >
		瀏覽器如果不支援 canvas 元素，就顯示這行文字
        </canvas>
		<canvas id=\"Canvasobject\" >
		瀏覽器如果不支援 canvas 元素，就顯示這行文字
        </canvas>  
		</div>";
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

var canvas = document.getElementById('_2DCanvas');
  var ctx = canvas.getContext('2d');
  var canvasObject = document.getElementById('Canvasobject');
  var ctxObject = canvasObject.getContext('2d');
  var backimg = new Image();
  ctxObject.fillStyle='rgba(255, 255, 255, 0)';
  backimg.addEventListener("load", function() {
  //圖片載入完畢後，再畫圖
  canvas.setAttribute('width', 1000);       //改變寬度
  canvas.setAttribute('height', backimg.height*(1000/backimg.width));     //改變高度
  canvasObject.setAttribute('width', 1000);       //改變寬度
  canvasObject.setAttribute('height', backimg.height*(1000/backimg.width));     //改變高度
  document.getElementById('env').style.width="1000px";
  var h=backimg.height*(1000/backimg.width)+"px";
  document.getElementById('env').style.height=h;
  ctx.drawImage(backimg, 0, 0,1000,backimg.height*(1000/backimg.width));
  
}, false);
  backimg.src=<?php echo '\'';?><?php echo $rs_question['background_src']?><?php echo '\'';?>;
  var displayList = [];
  var displayX = [];
  var displayY = [];
  var displaySound=[];
	<?php
		       $object = explode(",",$rs_question['object']);
		       foreach($object as $key => $value)
			   {
				   $obje = $con->query("select * from object where id = '$value'");
				   $rs_obje = mysqli_fetch_assoc($obje);
				   $coordinate = explode(",",$rs_obje['coordinate']);
				   $sound = $con->query("SELECT * FROM data WHERE id ={$rs_obje['sound_src']}");
				   $rs_sound = mysqli_fetch_assoc($sound);
				   ?>
            var img=new Image();
            img.src=<?php echo "'{$rs_obje['pic_src']} '"?>;
			img.height=(img.height*<?php if($rs_obje['size']!='') echo "{$rs_obje['size']}";else echo "0"?>)/img.width;
			img.width =<?php echo "{$rs_obje['size']} "?>;
			displayList.push(img);
			displayX.push(<?php echo "{$coordinate[0]} "?>);
			displayY.push(<?php echo "{$coordinate[1]} "?>);
			displaySound.push(<?php echo "'{$rs_sound['name']}'"?>);
			  <?php }?>
  
  
		//網頁載入完成
		window.onload = function(){
        drawScene();			
            var WIDTH = canvasObject.width, HEIGHT = canvasObject.height;

            //定義當被滑鼠選取的狀態
            var SELECTED;
			var SELECTEDID;
            var MouseOriginX, MouseOriginY;

			if(canvasObject && canvasObject.getContext){

                //滑鼠拖曳物件 ===============================================

                //繪製矩形
                
                
				
                //定義監聽事件
                canvasObject.addEventListener('mousedown', canvasMouseDownHandler, false);
                canvasObject.addEventListener('mouseup', canvasMouseUpHandler, false);
                canvasObject.addEventListener('mouseout', canvasMouseUpHandler, false);   //特別加上 mouseout 是以防指標回來後，矩形會黏著它
            }
            //繪製顯示清單
			function drawScene(coord){
				//清空畫布
				ctxObject.clearRect(0,0,canvasObject.width,canvasObject.height);
				//檢查陣列繪製每個圖形
				for(var i=0,l=displayList.length;i<displayList.length;i++)
				{
					ctxObject.beginPath();
					ctxObject.rect(displayX[i],displayY[i],displayList[i].width,displayList[i].height);
					ctxObject.closePath();
					if(displayList[i].complete)
					{
						ctxObject.drawImage(displayList[i],displayX[i],displayY[i],displayList[i].width,displayList[i].height);
					}
					else
					{
						displayList[i].onload=function(){ctxObject.drawImage(displayList[i],displayX[i],displayY[i],displayList[i].width,displayList[i].height);}
					}
					if(coord)
					{
						if(ctxObject.isPointInPath(coord.x,coord.y))
						{
							SELECTEDID=i;
							SELECTED = true;
						}
					}
				}
			}

            //定義 mousedown 事件處理函數  ===============================================
            function canvasMouseDownHandler(evt){
                evt.preventDefault(); //取消預設行為

                //取得滑鼠點擊位置
                var coord = getMousePointerCoord(evt);		
                //檢測是否點擊在矩形內
                drawScene(coord);
                	if(SELECTED)					//為變數設定值為 true 表示選取了矩形
					enable_submit();
					document.getElementById('sendanswer').value=displaySound[SELECTEDID];
                
            }
            //定義 mouseup 事件處理函數  ===============================================
            function canvasMouseUpHandler(evt){
                evt.preventDefault(); //取消預設行為

                SELECTED = false;							//為變數設定值為 false 表示沒有選取矩形
            }


            //用來計算座標位置並傳回 ===============================================
            var getMousePointerCoord = function(e){
                var evt = e || window.event;							//取得相容性 event 物件
                var supportLayer = typeof evt.clientX == 'number';		//確定目前瀏覽器是否支援 layerX
                var target = evt.target ? evt.target : evt.srcElement;	//取得事件物件的 HTML 元素
                var rect = canvasObject.getBoundingClientRect();              //取得 canvasObject 實際在網頁上的位置和寬高

                //計算 x 座標，首先確定使用 layerX 還是 x，然後減去目前元素的邊距、邊框、留白
                var x = (supportLayer ? evt.clientX : evt.x) - rect.left;

                //計算 y 座標，首先確定使用 layerY 還是 y，然後減去目前元素的邊框、留白
                var y = (supportLayer ? evt.clientY : evt.y) - rect.top;

                //回傳一個 Object，包含座標屬性
                return {x: x, y: y};				
            };
           
		};

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
