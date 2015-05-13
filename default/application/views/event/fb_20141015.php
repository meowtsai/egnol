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
body {background:url('/p/img/event/fb_20141015/bg.jpg?1') no-repeat center top; padding:0; margin:0;}
#container {height:783px; position:relative;}
#msg {width:325px; margin:0 auto 0; position:absolute; top:543px; left:470px;}
#logo {position:absolute; top:148px; left:60px;}
#mask {}
#content {background:url('/p/img/event/fb_20141015/code.png') no-repeat; height:92px;}
#code {font-size:17px; font-weight:bold; text-align:center;}

img {border:0px;}
</style>
</head>
<body>
<div id="container">

<div id="msg">
	
	<? if ($code):?>
	
	<div id="content" style="display:none;">
		<span id="code">
			序號：<br>
			<span style="font-size:17px; padding:3px 0; color:#921;"><?=$code?></span>
		</span>
	</div>
	
	<? endif;?>
	
	<div id="mask">
		<? if ($msg):?>
		<a href="javascript: alert('<?=$msg?>');">
		<? else:?>
		<a href="javascript:;" onclick="$('#mask').hide(); $('#content').show()">
		<? endif;?>
			<img src="/p/img/event/fb_20141015/buttom_off.png" class="change">	
		</a>
	</div>
	
	
	
	<div id="enter">
		<a href="http://zj.long_e.com.tw" target="_blank">
			<img src="/p/img/event/fb_20141015/enter_off.png" class="change">
		</a>	
	</div>

</div>

<div id="logo">
	<a href="http://zj.long_e.com.tw/" target="_blank"><img src="/p/img/event/fb_20141015/logo.png"></a>
</div>
	
</div>
</body>
</html>
