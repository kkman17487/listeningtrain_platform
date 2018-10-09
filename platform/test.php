<!doctype html>
<html>
	<head>
		<script>
			function rand(q){
				return Math.floor(Math.random()*(q+1));
			}
			function init(){
				allX = document.body.clientWidth;
				allY = document.body.clientHeight;
				var x=document.getElementById('obj');
				x.style.left=rand(allX)+'px';
				x.style.top=rand(allY)+'px';
				run();
			}
			step=1;
			function run(){
				var x=document.getElementById('obj');
				currentX=parseInt(x.style.left);
				if(currentX == allX){
					step=-1;
				}
				if(currentX == 0){
					step=1;
				}
				x.style.left=parseInt(x.style.left)+step+'px';
				setTimeout('run()',10);
			}
		</script>
	</head>
	<body onload="init()" style="width:500px;height:500px">
		<div id="obj" style="position:absolute;top:100px;left:0px">MLab</div>
	</body>
</html>
