<!DOCTYPE html>
<?php
include('connect_to_sql.php');
@$this_type = $_GET['id'];
$data = $con->query("SELECT * FROM enviro WHERE id =$this_type");
include('sidebar.php');
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
  <!-- Top header -->
  <header class="w3-container w3-xlarge">
    <p class="w3-left">情境訓練模式</p>
    <!--<p class="w3-right">
      <i class="fa fa-shopping-cart w3-margin-right"></i>
      <i class="fa fa-search"></i>
    </p>-->
  </header>
  <body> 
  <?php
  $rs = mysqli_fetch_assoc($data);
  $object = explode(",",$rs['object']);
		foreach($object as $key => $value)
			{
			$obje = $con->query("select * from object where id = '$value'");
			$rs_obje = mysqli_fetch_assoc($obje);
			$sound = $con->query("SELECT * FROM data WHERE id ={$rs_obje['sound_src']}");
			$rs_sound = mysqli_fetch_assoc($sound);
			?>
            <audio id='<?php echo $rs_sound['audio_id']?>'>
            <source src="<?php echo $rs_sound['sound_src']?>" type="audio/mp3" />
            <embed height="100" width="100" src="<?php echo $rs[sound_src]?>" />
            </audio>
			  <?php }?>
  <!-- Product grid -->
      <div class="w3-container" id="env">
       
		<canvas id="_2DCanvas" >
		瀏覽器如果不支援 canvas 元素，就顯示這行文字
        </canvas>
		<canvas id="Canvasobject" >
		瀏覽器如果不支援 canvas 元素，就顯示這行文字
        </canvas>	
        
        </div>
        <p align = 'center'><?php echo $rs['name']?></p>
	  <article class="w3-container"> 
       <ul id="soundList"><h2>聲音列表</h2>
        
       </ul>
      </article>
  <!-- End page content -->
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
  backimg.src=<?php echo '\'';?><?php echo $rs['background_src']?><?php echo '\'';?>;
  var ul =document.getElementById("soundList");
  var displayList = [];
  var displayX = [];
  var displayY = [];
  var displaySound = [];
	<?php
		       $object = explode(",",$rs['object']);
		       foreach($object as $key => $value)
			   {
				   $obje = $con->query("select * from object where id = '$value'");
				   $rs_obje = mysqli_fetch_assoc($obje);
				   $coordinate = explode(",",$rs_obje['coordinate']);
				   $sound = $con->query("SELECT * FROM data WHERE id ={$rs_obje['sound_src']}");
				   $rs_sound = mysqli_fetch_assoc($sound);
				   ?>
			var li = document.createElement("li");
			li.appendChild(document.createTextNode(<?php echo "\"{$rs_obje['name']}\""?>));
			ul.appendChild(li);
            var img=new Image();
            img.src=<?php echo "'{$rs_obje['pic_src']} '"?>;
			img.height=(img.height*<?php if($rs_obje['size']!='') echo "{$rs_obje['size']}";else echo "0"?>)/img.width;
			img.width =<?php echo "{$rs_obje['size']} "?>;
			displayList.push(img);
			displayX.push(<?php echo "{$coordinate[0]} "?>);
			displayY.push(<?php echo "{$coordinate[1]} "?>);
			displaySound.push(<?php echo "'{$rs_sound['audio_id']}'"?>);
			  <?php }?>
  
  
		//網頁載入完成
		window.onload = function(){
        drawScene();			
            var WIDTH = canvasObject.width, HEIGHT = canvasObject.height;

            //定義當被滑鼠選取的狀態
            var SELECTED;
			var SELECTEDID;
            var MouseOriginX, MouseOriginY;
			var radra = ctxObject.createRadialGradient(0,0,0,0,0,0);
			radra.addColorStop(0,'rgba(255,255,255,1)');
			radra.addColorStop(1,'rgba(255,255,255,0)');

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
					if(displayList[i].width>displayList[i].height)
					radra=ctxObject.createRadialGradient(displayX[i]+displayList[i].width/2,displayY[i]+displayList[i].height/2,displayList[i].width/4,
					                                        displayX[i]+displayList[i].width/2,displayY[i]+displayList[i].height/2,displayList[i].width*0.75);
					else
					radra=ctxObject.createRadialGradient(displayX[i]+displayList[i].width/2,displayY[i]+displayList[i].height/2,displayList[i].height/4,
					                                        displayX[i]+displayList[i].width/2,displayY[i]+displayList[i].height/2,displayList[i].height*0.75);
					radra.addColorStop(0,'rgba(255,255,255,1)');
					radra.addColorStop(1,'rgba(0,0,0,0)');
					ctxObject.beginPath();
					ctxObject.rect(displayX[i],displayY[i],displayList[i].width,displayList[i].height);
					ctxObject.closePath();
					ctxObject.rect(displayX[i]-displayList[i].width/4,displayY[i]-displayList[i].height/4,displayList[i].width*1.5,displayList[i].height*1.5);
					ctxObject.fillStyle=radra;
					ctxObject.fill();
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
					sound(displaySound[SELECTEDID]);
                
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
var previous_play;
function sound(next_play)
{
  //alert(next_play);
  if(typeof previous_play !== 'undefined')
  {
    document.getElementById(previous_play).pause();
    document.getElementById(previous_play).currentTime = 0;
  }
  document.getElementById(next_play).play();
  previous_play = next_play;
}
</script>
  
</body>
</html>
