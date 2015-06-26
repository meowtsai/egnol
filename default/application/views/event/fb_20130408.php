<!DOCTYPE HTML>
<html lang="zh-tw">
<head>
<title></title>
<meta charset="utf-8">
<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.8.2/jquery.min.js"></script>
<script>
$(function(){
	$('.change').on('mouseover', function(){$(this).attr("src", $(this).attr("src").replace("off", "on"));})
		.on('mouseout', function(){$(this).attr("src", $(this).attr("src").replace("on", "off"));});
	
})
</script>
<style type="text/css">
body {background:url('/p/img/fb_20130408/bg.jpg?1') no-repeat center top; padding:0; margin:0;}
#container {height:783px; position:relative;}
#msg {width:440px; margin:0 auto 0; position:absolute; top:366px; left:269px;}
#mask {}
#content {background:url('/p/img/fb_20130408/code.png') no-repeat; width:410px; height:80px; position:relative; padding:19px 0 0 118px; margin-left:-103px}
#code {position:relative; margin:3px; color:#000; font-size:15px; font-weight:bold;}

img {border:0px;}
</style>
</head>
<body>
<div id="container">

<div id="msg">
	
	<? if ($code):?>
	<div id="content" style="display:none; position:relative;">
		<div style="position:absolute; left:0px; top:0px;">
			<a href="http://www.longeplay.com.tw/gate/login/yh?channel=facebook" target="_blank"><img src="/p/img/fb_20130408/enter_off.png" class="change"></a>
		</div>
		<span id="code">
			請複製以下序號，進遊戲內領獎<br>
			<span style="font-size:13px; padding:3px;"><?=$code?></span>
		</span>
	</div>
	<? endif;?>
	
	<div id="mask">
		<? if ($msg):?>
		<a href="javascript: alert('<?=$msg?>');">
		<? else:?>
		<a href="javascript:;" onclick="$('#mask').hide(); $('#content').show()">
		<? endif;?>
			<img src="/p/img/fb_20130408/buttom_off.png" class="change">	
		</a>
	</div>
	
</div>
</div>
</body>
</html>
