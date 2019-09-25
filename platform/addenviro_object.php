<!DOCTYPE html>
<script src="./lib/preloadjs.min.js"></script>
<?php
include('connect_to_sql.php');
include('backendheader.php');
include('backendsidebar.php');
@$this_type = $_GET['id'];
$data = $con->query("SELECT * FROM enviro WHERE id =$this_type");
$sound = $con->query("SELECT * FROM data ORDER BY category");
$category = $con->query("select category from data where category != ''");
?>
<html lang="zh-Hant-TW">
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
<body>
<p class="w3-left">添加物件</p>
  <?php
  $rs = mysqli_fetch_assoc($data);
  ?>
  <!-- Product grid -->
      <div class="container" id="env">
        <canvas id="_2DCanvas" >
		瀏覽器如果不支援 canvas 元素，就顯示這行文字
        </canvas>
		<canvas id="Canvasobject" >
		瀏覽器如果不支援 canvas 元素，就顯示這行文字
        </canvas>
        </div>
		<div class="container" id="addo">
		<h1 align="center">新增物件</h1>
		<form action="upload_object.php" method="post" enctype="multipart/form-data" style="position:relative;">
		<input type="hidden" name="enviro_ID" value=<?php echo "\"{$this_type}\""?>></input>
		<table align="center" width="1000px">
              <tr>
                <td width="20%" style="float:left;">名稱</td>
                <td width="30%">圖片</td>
				<td width="20%">聲音類別</td>
                <td width="30%">聲音</td>
              </tr>
			  <tr>
			  <td width="20%" style="float:left;"><input type="text" name="ChineseName" placeholder="中文"/></td>
                <td width="30%"><input type="file" name="my_file[]"></td>
				<td width="30%"><select id="soundtype" onChange="setSoundType();">
				<option value="#">全部</option>
        <?php
        $classfication = array();
        for($j = 1;$j <= mysqli_num_rows($category);$j++){
          $rs_category = mysqli_fetch_assoc($category);
          $tmp = explode(";",$rs_category['category']);
            $classfication = array_merge($classfication, $tmp);
        }
        $classfication = array_unique($classfication);
        foreach ($classfication as $key => $value){
          echo '<option value="'.$value.'"';
          echo '>'.$value.'</option>';
        }?>
				</select></td>
              <td width="30%"><select id="soundoption" name="SoundSrc">
        <?php
        for($i = 1;$i <= mysqli_num_rows($sound);$i++){
          $rs_sound = mysqli_fetch_assoc($sound);
          echo "<option value=\"{$rs_sound['id']}\" name=\"{$rs_sound['category']}\"";
          echo ">{$rs_sound['name']}</option>";
        }?>
				</select></td>
			  </tr>
			  <tr>
			  <td><input type="submit" value="送出" /></td>
			  </tr>
		</table>
		</form>
		<h1 align="center">個別物件調整</h1>
		<form action="upload_objectEdit.php" id="editObData" method="post" enctype="multipart/form-data" >
		<input id="upID" type="hidden" name="updataId" value="" >
		<input id="upcoord" type="hidden" name="updataCoordinate" value="" >
		<input id="upSize" type="hidden" name="updataSize" value="" >
		<table align="center" width="1000px">
		 <tr>
                <td id="obname" width="20%">未選擇</td>
                <td width="30%"><input type="button" value="放大" onclick="bigger()"></input></td>
				<td width="20%"><input type="button" value="縮小" onclick="smaller()"></input></td>
                <td width="30%"><input type="button" value="刪除" onclick="deobject()"></input></td>
         </tr>
		 <tr>
		 
			  <td><input type="button" value="儲存" onclick="savedata()"></input></td>
		
		 </tr>
		</table>
		</form>
		</div>
		
		
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
  var displayList = [];
  var displayX = [];
  var displayY = [];
  var displayName= [];
  var displayID= [];
	<?php
		       $object = explode(",",$rs['object']);
		       foreach($object as $key => $value)
			   {
				   $obje = $con->query("select * from object where id = '$value'");
				   $rs_obje = mysqli_fetch_assoc($obje);
				   $coordinate = explode(",",$rs_obje['coordinate']);
				   ?>
            var img=new Image();
            img.src=<?php echo "'{$rs_obje['pic_src']} '";?>;
			img.height=(img.height*<?php if($rs_obje['size']!='') echo "{$rs_obje['size']}";else echo "0"?>)/img.width;
			img.width =<?php if($rs_obje['size']!='') echo "{$rs_obje['size']}";else echo "0"?>;
			
			displayList.push(img);
			displayX.push(<?php echo "{$coordinate[0]} "?>);
			displayY.push(<?php if(count($coordinate)>1) echo "{$coordinate[1]} ";?>);
			displayName.push(<?php echo "'{$rs_obje['name']}'";?>);
			displayID.push(<?php echo "'{$rs_obje['id']}'";?>);
			  <?php }?>
			  
		//網頁載入完成
		window.onload = function(){
			
        drawScene();
		}		
            var WIDTH = canvasObject.width, HEIGHT = canvasObject.height;

            //定義當被滑鼠選取的狀態
            var SELECTED;
			var SELECTEDID;
            var MouseOriginX, MouseOriginY;

			if(canvasObject && canvasObject.getContext){

                //定義監聽事件
                canvasObject.addEventListener('mousedown', canvasMouseDownHandler, false);
                canvasObject.addEventListener('mousemove', canvasMouseMoveHandler, false);
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

            //定義 draw 重繪物件  ===============================================
            function draw(x, y){
                ctxObject = canvasObject.getContext('2d');
                ctxObject.clearRect(0, 0, canvasObject.width, canvasObject.height);

                ctxObject.fillStyle = '#c00';
                ctxObject.beginPath();
                ctxObject.rect(x, y, 100, 100);
                DrawOriginX = x;
                DrawOriginY = y;
                ctxObject.fill();
            }


            //定義 mousedown 事件處理函數  ===============================================
            function canvasMouseDownHandler(evt){
                evt.preventDefault(); //取消預設行為

                //取得滑鼠點擊位置
                var coord = getMousePointerCoord(evt);		

                //記錄滑鼠點擊位置，作為一個原始位置
                MouseOriginX = coord.x;
                MouseOriginY = coord.y;

                //檢測是否點擊在矩形內
                drawScene(coord);
                	if(SELECTED)					//為變數設定值為 true 表示選取了矩形
                    canvasObject.style.cursor = 'move';			//改變鼠標
					console.log(SELECTEDID);
					document.getElementById("obname").innerHTML=displayName[SELECTEDID];
                
            }


            //定義 mousemove 事件處理函數  ===============================================
            function canvasMouseMoveHandler(evt){
                evt.preventDefault(); //取消預設行為

                //檢測是否有選取到的物體，然後移動物體到 x、y 座標到滑鼠位置
                if(SELECTED){
                    var coord = getMousePointerCoord(evt);	//取得滑鼠點擊位置
					displayX[SELECTEDID]=displayX[SELECTEDID]+coord.x-MouseOriginX;
					displayY[SELECTEDID]=displayY[SELECTEDID]+coord.y-MouseOriginY;

                    //使用起始的座標繪製一個矩形
                    drawScene();

                    //更新滑鼠原始位置
                    MouseOriginX = coord.x;
                    MouseOriginY = coord.y;
                }
            }


            //定義 mouseup 事件處理函數  ===============================================
            function canvasMouseUpHandler(evt){
                evt.preventDefault(); //取消預設行為

                SELECTED = false;							//為變數設定值為 false 表示沒有選取矩形
                canvasObject.style.cursor = 'auto';				//還原鼠標
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
            }
			
			
		
		var soundT = document.getElementById('soundtype');
		var soundO = document.getElementById('soundoption');
		var ALLOpValue =[];
		var ALLOpText  =[];
		var ALLOpName  =[];
		for(var i=0;i<<?php echo mysqli_num_rows($sound);?>;i++)
		{
			ALLOpValue.push(soundO.options[i].value);
			ALLOpText.push(soundO.options[i].text);
			ALLOpName.push(soundO.options[i].getAttribute("name"));
		}
		//在選擇聲音類型後更變聲音選項
		function setSoundType()
		{
			soundO.options.length=0;
			
			for(var i=0;i<<?php echo mysqli_num_rows($sound);?>;i++)
			{
				
			if(soundT.value==ALLOpName[i])
			soundO.add(new Option(ALLOpText[i],ALLOpValue[i]));
			}
        
		};
		
		//放大功能
		function bigger()
		{
			displayList[SELECTEDID].height=displayList[SELECTEDID].height*(displayList[SELECTEDID].width+5)/displayList[SELECTEDID].width;
			displayList[SELECTEDID].width=5+displayList[SELECTEDID].width;
			drawScene();
		}
		
		//縮小功能
		function smaller()
		{
			displayList[SELECTEDID].height=displayList[SELECTEDID].height*(displayList[SELECTEDID].width-5)/displayList[SELECTEDID].width;
			displayList[SELECTEDID].width=displayList[SELECTEDID].width-5;
			drawScene();
		}
		//刪除功能
		function deobject()
		{
			
		}
		//儲存功能
		function savedata()
		{
			for(var i=0;i<displayList.length;i++)
			{
				if(document.getElementById("upcoord").value=="")
				{
					document.getElementById("upcoord").value=displayX[i]+","+displayY[i];
				}
				else 
				{	
					document.getElementById("upcoord").value=document.getElementById("upcoord").value+":"+displayX[i]+","+displayY[i];
				}
				if(document.getElementById("upSize").value=="")
				{
					document.getElementById("upSize").value=displayList[i].width;
				}
				else 
				{
					document.getElementById("upSize").value=document.getElementById("upSize").value+":"+displayList[i].width;
				}
				if(document.getElementById("upID").value=="")
				{
					document.getElementById("upID").value=displayID[i];
				}
				else 
				{
					document.getElementById("upID").value=document.getElementById("upID").value+":"+displayID[i];
				}
			}
			document.getElementById("editObData").submit();
		}
		
	
  

</script>
  
</body>

</html>
