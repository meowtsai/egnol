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
body {background:url('/p/img/fb_20130326/bg.jpg?1') no-repeat center top; padding:0; margin:0;}
#container {height:783px; position:relative;}
#msg {width:440px; margin:0 auto 0; position:absolute; top:440px; left:269px;}
#mask {}
#content {background:url('/p/img/fb_20130326/code.jpg') no-repeat; width:299px; height:80px; position:relative; padding:11px;}
#code {position:relative; margin:3px; color:#000; font-size:15px; font-weight:bold;}

img {border:0px;}
</style>
</head>
<body>
<div id="container">

<div id="msg">
	
	<? if ($code):?>
	<div id="content" style="display:none;">
		<span id="code">
			專屬序號：<br>
			<span style="font-size:11px;"><?=$code?></span>
		</span>

		<div style="margin-top:12px;">
		<a href="http://www.long_e.com.tw/gate/login/xl?channel=facebook" target="_blank"><img src="/p/img/fb_20130326/enter_off.jpg" class="change"></a>
		<a href="http://xl.long_e.com.tw" target="_blank"><img src="/p/img/fb_20130326/home_off.jpg" class="change"></a>
		</div>

	</div>
	<? endif;?>
	
	<div id="mask">
		<? if ($msg):?>
		<a href="javascript: alert('<?=$msg?>');">
		<? else:?>
		<a href="javascript:;" onclick="$('#mask').hide(); $('#content').show()">
		<? endif;?>
			<img src="/p/img/fb_20130326/buttom_off.jpg" class="change">	
		</a>
	</div>
	
</div>
</div>
</body>
</html>
