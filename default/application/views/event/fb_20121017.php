<!DOCTYPE HTML>
<html lang="zh-tw">
<head>
<title></title>
<meta charset="utf-8">
<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.8.2/jquery.min.js"></script>
<style type="text/css">
body {background:url('/p/img/fb_20121017/bg.jpg?1') no-repeat center top; padding:0; margin:0;}
#container {height:800px; position:relative;}
#msg {width:300px; margin:0 auto 0; position:relative; top:323px; left:238px;}

#content {background:url('/p/img/fb_20121017/code.png') no-repeat; width:258px; height:249px; position:relative; text-align:center;
			padding-top:146px; color:#fff;}
#code {position:relative; top:5px; margin:auto;}

</style>
</head>
<body>
<div id="container">
<div id="msg">
	
	<? if ($code):?>
	<div id="content" style="display:none;">
		<span id="code" style="padding: 0 10px 0 0"><?=$code?></span>
		<div style="margin:18px 0 0; padding:0 22px 0 0;">
			<a href="http://www.long_e.com.tw/gate/login/mon?channel=facebook" target="_blank">進入遊戲</a> <Br> 
			<a href="http://mon.long_e.com.tw/bulletin/detail/31" target="_blank">領取方式</a>
		</div>
	</div>
	<? endif;?>
	
	<div id="mask" style="position:absolute; left:0; top:10px; ">
		<? if ($msg):?>
		<a href="javascript: alert('<?=$msg?>');">
		<? else:?>
		<a href="javascript:;" onclick="$('#mask').hide(); $('#content').show()">
		<? endif;?>
			<img src="/p/img/fb_20121017/buttom.gif">	
		</a>
	</div>
	
</div>
</div>
</body>
</html>
