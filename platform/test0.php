<script>
let canvas = document.getElementById("myCanvas");
let ctx = canvas.getContext("2d");
canvas.height = window.innerHeight;
canvas.width = window.innerWidth;

//建立一個物件儲存滑鼠目前的x,y座標
let mouse = {
    x : 0,
    y : 0,
}

//加入監聽器
window.addEventListener('mousemove',(event) => {
  //在這裡把滑鼠座標寫到物件mouse中
  mouse.x = event.pageX;
  mouse.y = event.pageY;
})
function run()
{
  document.getElementById('x').value = mouse.x;
	document.getElementById('y').value = mouse.y;
  var t=setTimeout("run()",1);
}
</script>
<body onload="run()">
	<canvas id="myCanvas"></canvas>
	<form>
		<input type="text" id="x" name="x">
		<input type="text" id="y" name="y">
	</form>
</body>
