<!DOCTYPE html>
<?php
include('connect_to_sql.php');//加入連線資料庫的檔案
include('backendheader.php');//加入後台header
include('backendsidebar.php');//加入後台側邊選單
@$this_type = $_GET['id'];//抓取用GET傳入的id參數
$data = $con->query("SELECT * FROM enviro WHERE id =$this_type");//從資料庫選取對應的情境
$sound = $con->query("SELECT * FROM data ORDER BY category");//抓取所有聲音資料
$category = $con->query("select category from data where category != ''");//所有聲音類型
?>
<html lang="zh-Hant-TW">
<!--style設定開始-->
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
<!--style設定結束-->

<!--網頁內容開始-->
<body>
<p class="w3-left">添加物件</p>
  <?php
  $rs = mysqli_fetch_assoc($data);//在script中才會用到將情境資料抓出一行
  ?>
  <!--顯示情境用的canvas畫布第一個為背景用，第二個是物件-->
      <div class="col-md-6 col-md-offset-2" id="env">
        <canvas id="_2DCanvas" >
		瀏覽器如果不支援 canvas 元素，就顯示這行文字
        </canvas>
		<canvas id="Canvasobject" >
		瀏覽器如果不支援 canvas 元素，就顯示這行文字
        </canvas>
        </div>
		
		<!--新增物件的部分開始-->
		<div class="col-md-6 col-md-offset-2" id="addo">
		<h1 align="center">新增物件</h1>
		<!--新增物件的表單輸入的參數以POST傳輸到upload_object.php處理-->
		<form action="upload_object.php" method="post" enctype="multipart/form-data" style="position:relative;">
		<!--隱藏的參數紀錄是哪個情境-->
		<input type="hidden" name="enviro_ID" value=<?php echo "\"{$this_type}\""?>></input>
		<!--輸入的table-->
		<table align="center" width="1000px">
              <tr>
                <td width="20%" style="float:left;">名稱</td>
                <td width="30%">圖片</td>
				<td width="20%">聲音類別</td>
                <td width="30%">聲音</td>
              </tr>
			  <!--輸入的表格開始-->
			  <tr>
			  <!--輸入名字-->
			  <td width="20%" style="float:left;"><input type="text" name="ChineseName" placeholder="中文"/></td>
			  <!--輸入圖片檔案-->
              <td width="30%"><input type="file" name="my_file[]"></td>
			  <!--選擇聲音類別-->
			  <td width="30%"><select id="soundtype" onChange="setSoundType();">
			  <option value="#">全部</option>
        <?php
		//建立類別選項
        $classfication = array();
		//不斷將類別拿出一行直到沒有下一行，將其加入$classfication
        for($j = 1;$j <= mysqli_num_rows($category);$j++){
          $rs_category = mysqli_fetch_assoc($category);
          $tmp = explode(";",$rs_category['category']);
          $classfication = array_merge($classfication, $tmp);
        }
		//選出不重複的類別
        $classfication = array_unique($classfication);
		//將類別放入選項
        foreach ($classfication as $key => $value){
          echo '<option value="'.$value.'"';
          echo '>'.$value.'</option>';
        }?>
				</select></td>
		<!--選擇聲音-->
              <td width="30%"><select id="soundoption" name="SoundSrc">
        <?php
		//將聲音加入選項
        for($i = 1;$i <= mysqli_num_rows($sound);$i++){
          $rs_sound = mysqli_fetch_assoc($sound);
          echo "<option value=\"{$rs_sound['id']}\" name=\"{$rs_sound['category']}\"";
          echo ">{$rs_sound['name']}</option>";
        }?>
				</select></td>
			  </tr>
			  <tr>
			  <!--輸入的表格結束-->
			  <td><input type="submit" value="送出" /></td>
			  </tr>
		</table>
		</form>
		<!--新增物件的部分結束-->
		
		<!--調整物件的部分開始-->
		<h1 align="center">個別物件調整</h1>
		<!--參數以POST傳輸由upload_objectEdit.php處理-->
		<form action="upload_objectEdit.php" id="editObData" method="post" enctype="multipart/form-data" >
		<!--下面三個參數由Script填入-->
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
	<!--網頁內容結束-->	

  
  <script>
  //設定各畫布參數
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
  ctx.drawImage(backimg, 0, 0,1000,backimg.height*(1000/backimg.width));//繪製背景
  
}, false);
  backimg.src=<?php echo '\'';?><?php echo $rs['background_src']?><?php echo '\'';?>;
  //紀錄所有物件的資料
  var displayList = [];
  var displayX = [];
  var displayY = [];
  var displayName= [];
  var displayID= [];
  var width = [];
	<?php
	//將所有物件加入array中
		       $object = explode(",",$rs['object']);
		       foreach($object as $key => $value)
			   {
				   $obje = $con->query("select * from object where id = '$value'");
				   $rs_obje = mysqli_fetch_assoc($obje);
				   $coordinate = explode(",",$rs_obje['coordinate']);
				   ?>
            var img=new Image();
            img.src=<?php echo "'{$rs_obje['pic_src']} '";?>;
			width.push(<?php echo "{$rs_obje['size']} "?>);
			displayList.push(img);
			displayX.push(<?php echo "{$coordinate[0]} "?>);
			displayY.push(<?php if(count($coordinate)>1) echo "{$coordinate[1]} ";?>);
			displayName.push(<?php echo "'{$rs_obje['name']}'";?>);
			displayID.push(<?php echo "'{$rs_obje['id']}'";?>);
			  <?php }?>
			  
		//網頁載入完成；設定好所有物件大小後做第一次繪製
		window.onload = function(){
			for(var i=0,l=displayList.length;i<displayList.length;i++)
				{
					if(displayList[i].complete)
					{
						displayList[i].height=(displayList[i].height*width[i])/displayList[i].width;
						displayList[i].width =width[i];
					}
					else
					{
						displayList[i].onload=function(){displayList[i].height=(displayList[i].height*width[i])/displayList[i].width;
						displayList[i].width =width[i];}
					}
				}
        drawScene();
		}		
            var WIDTH = canvasObject.width, HEIGHT = canvasObject.height;

            //定義當被滑鼠選取的狀態
            var SELECTED;
			var SELECTEDID;
            var MouseOriginX, MouseOriginY;
			var radra = ctxObject.createRadialGradient(0,0,0,0,0,0);
			radra.addColorStop(0,'rgba(255,255,255,1)');
			radra.addColorStop(1,'rgba(255,255,255,0)');

			if(canvasObject && canvasObject.getContext){

                //定義監聽事件
                canvasObject.addEventListener('mousedown', canvasMouseDownHandler, false);
                canvasObject.addEventListener('mousemove', canvasMouseMoveHandler, false);
                canvasObject.addEventListener('mouseup', canvasMouseUpHandler, false);
                canvasObject.addEventListener('mouseout', canvasMouseUpHandler, false);   //特別加上 mouseout 是以防指標回來後，矩形會黏著它
            }
            //繪製顯示清單上的所有物件
			function drawScene(coord){
				//清空畫布
				ctxObject.clearRect(0,0,canvasObject.width,canvasObject.height);
				//檢查陣列繪製每個圖形
				for(var i=0,l=displayList.length;i<displayList.length;i++)
				{
					//繪製背光
					if(displayList[i].width>displayList[i].height)
					radra=ctxObject.createRadialGradient(displayX[i]+displayList[i].width/2,displayY[i]+displayList[i].height/2,displayList[i].width/4,
					                                        displayX[i]+displayList[i].width/2,displayY[i]+displayList[i].height/2,displayList[i].width*0.75);
					else
					radra=ctxObject.createRadialGradient(displayX[i]+displayList[i].width/2,displayY[i]+displayList[i].height/2,displayList[i].height/4,
					                                        displayX[i]+displayList[i].width/2,displayY[i]+displayList[i].height/2,displayList[i].height*0.75);
					radra.addColorStop(0,'rgba(255,255,255,1)');
					radra.addColorStop(1,'rgba(0,0,0,0)');
					//可點擊範圍
					ctxObject.beginPath();
					ctxObject.rect(displayX[i],displayY[i],displayList[i].width,displayList[i].height);
					ctxObject.closePath();
					//填入背光
					ctxObject.rect(displayX[i]-displayList[i].width/4,displayY[i]-displayList[i].height/4,displayList[i].width*1.5,displayList[i].height*1.5);
					ctxObject.fillStyle=radra;
					ctxObject.fill();
					//在圖片讀取完成後才開始繪製
					if(displayList[i].complete)
					{
						ctxObject.drawImage(displayList[i],displayX[i],displayY[i],displayList[i].width,displayList[i].height);
					}
					else
					{
						displayList[i].onload=function()
							{
								ctxObject.drawImage(displayList[i],displayX[i],displayY[i],displayList[i].width,displayList[i].height);
							};
					}
					//檢測是否點擊到物件
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

                //記錄滑鼠點擊位置，作為一個原始位置
                MouseOriginX = coord.x;
                MouseOriginY = coord.y;

                //檢測是否點擊在矩形內
                drawScene(coord);
                	if(SELECTED)					//為變數設定值為 true 表示選取了矩形
                    canvasObject.style.cursor = 'move';			//改變鼠標
					console.log(SELECTEDID);//測試用看選中的object
					document.getElementById("obname").innerHTML=displayName[SELECTEDID];//顯示選中的物件是哪個
                
            }


            //定義 mousemove 事件處理函數  ===============================================
            function canvasMouseMoveHandler(evt){
                evt.preventDefault(); //取消預設行為

                //檢測是否有選取到的物體，然後移動物體到 x、y 座標到滑鼠位置
                if(SELECTED){
                    var coord = getMousePointerCoord(evt);	//取得滑鼠點擊位置
					//更新XY座標將滑鼠位置的新座標減去滑鼠位置的舊座標後加入物件座標位置
					displayX[SELECTEDID]=displayX[SELECTEDID]+coord.x-MouseOriginX;
					displayY[SELECTEDID]=displayY[SELECTEDID]+coord.y-MouseOriginY;

                    //更新座標後重新繪製
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
			
			
		//soundT為聲音類別；soundO聲音；
		var soundT = document.getElementById('soundtype');
		var soundO = document.getElementById('soundoption');
		var ALLOpValue =[];
		var ALLOpText  =[];
		var ALLOpName  =[];
		//將所有聲音記錄下來更改聲音類別時要做更變使用
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
		
		//放大功能；將選中的物件長寬等比放大後重新繪製畫布
		function bigger()
		{
			displayList[SELECTEDID].height=(displayList[SELECTEDID].width+5)*(displayList[SELECTEDID].height/displayList[SELECTEDID].width);
			displayList[SELECTEDID].width=5+displayList[SELECTEDID].width;
			drawScene();
		}
		
		//縮小功能；將選中的物件長寬等比縮小後重新繪製畫布
		function smaller()
		{
			displayList[SELECTEDID].height=(displayList[SELECTEDID].width+5)*(displayList[SELECTEDID].height/displayList[SELECTEDID].width);
			displayList[SELECTEDID].width=displayList[SELECTEDID].width-5;
			drawScene();
		}
		//刪除功能；將object和enviro的ID給delete_object.php做刪除
		function deobject()
		{
			if(typeof SELECTEDID !== 'undefined')
			window.location = 'delete_object.php?OD='+displayID[SELECTEDID]+<?php echo"'&EID=".$this_type."'" ?>
			
		}
		//儲存功能；將所有的object資料照順序以：隔開，填入上面三個參數中後送出
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
