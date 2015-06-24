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
body {background:url('/p/img/fb_20130509/bg.jpg?1') no-repeat center top; padding:0; margin:0;}
#container {height:783px; position:relative;}
#msg {width:440px; margin:0 auto 0; position:absolute; top:508px; left:85px;}
#logo {position:absolute; top:444px; left:461px;}
#mask {}
#content {background:url('/p/img/fb_20130509/code.png') no-repeat; width:410px; height:80px; position:relative; padding:17px 8px;}
#code {position:relative; margin:3px; color:#000; font-size:15px; font-weight:bold;}

img {border:0px;}
</style>
</head>
<body>
<div id="container">

<div id="msg">
	
	<? if ($code):?>
	<div id="content" style="display:none; position:relative;">
		<span id="code">
			專屬序號：<span style="font-size:17px; padding:3px;"><?=$code?></span>
		</span>
	</div>
	<? endif;?>
	
	<div id="mask">
		<? if ($msg):?>
		<a href="javascript: alert('<?=$msg?>');">
		<? else:?>
		<a href="javascript:;" onclick="$('#mask').hide(); $('#content').show()">
		<? endif;?>
			<img src="/p/img/fb_20130509/buttom_off.png" class="change">	
		</a>
	</div>
	
</div>

<div id="logo">
	<a href="http://gt.longeplay.com.tw/" target="_blank"><img src="/p/img/fb_20130509/logo.png"></a>
</div>
	
</div>
</body>
</html>
