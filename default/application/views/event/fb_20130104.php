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
body {background:url('/p/img/fb_20130104/bg.jpg?1') no-repeat center top; padding:0; margin:0;}
#container {height:783px; position:relative;}
#msg {width:800px; margin:0 auto 0; position:relative; top:100px;}
#mask {position:absolute; right:66px;}
#content {background:url('/p/img/fb_20130104/code.png') no-repeat; width:280px; height:249px; position:relative; text-align:center;
			padding-top:146px; margin-left:483px;}
#code {position:relative; top:13px; margin:auto; padding:10px 32px 8px 26px; color:#000; font-size:15px; font-weight:bold;}
#logo {position:relative; top:13px; right:14px; text-align:right;}
img {border:0px;}
</style>
</head>
<body>
<div id="container">

<div id="logo" >
	<a href="http://sl2.longeplay.com.tw/" target="_blank"><img src="/p/img/fb_20130104/logo.png" border="0"></a>
</div>

<div id="msg">
	
	<? if ($code):?>
	<div id="content" style="display:none;">
		<span id="code"><?=$code?></span>
		<div style="margin:-15px 0 0 95px;">
			<a href="http://www.longeplay.com.tw/gate/login/sl2?channel=facebook" target="_blank">
				<img src="/p/img/fb_20130104/enter_off.png" class="change">	
			</a>
		</div>
	</div>
	<? endif;?>
	
	<div id="mask">
		<? if ($msg):?>
		<a href="javascript: alert('<?=$msg?>');">
		<? else:?>
		<a href="javascript:;" onclick="$('#mask').hide(); $('#content').show()">
		<? endif;?>
			<img src="/p/img/fb_20130104/buttom_off.png" class="change">	
		</a>
	</div>
	
</div>
</div>
</body>
</html>
