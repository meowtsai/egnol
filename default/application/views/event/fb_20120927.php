<!DOCTYPE HTML>
<html lang="zh-tw">
<head>
<title></title>
<meta charset="utf-8">
<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.8.2/jquery.min.js"></script>
<style type="text/css">
body {background:url('/p/img/fb_20120927/20121011_bg_compressed.jpg?1') no-repeat center top; padding:0; margin:0;}
#container {height:800px; position:relative;}
#msg {width:300px; margin:0 auto 0; position:relative; top:735px}
#code {display:inline-block; margin-left:6px; padding:2px 5px; vertical-align:bottom; font-size:18px; background:#fff; border:1px solid #aaa;}
</style>
</head>
<body>
<div id="container">
<div id="msg">
	
	<? if ($code):?>
	<div id="content" style="display:none;">
		<img src="/p/img/fb_20120927/20121011_OK.gif">
		<span id="code"><?=$code?></span>
		<div style="margin:6px 0 0;">
			<a href="/gate/login/sg2?channel=facebook" target="_blank">進入遊戲</a> | 
			<a href="http://sg2.long_e.com.tw" target="_blank">開啟官網</a>
		</div>
	</div>
	<? endif;?>
	
	<div id="mask" style="position:absolute; left:0; top:10px; ">
		<? if ($msg):?>
		<a href="javascript: alert('<?=$msg?>');">
		<? else:?>
		<a href="javascript:;" onclick="$('#mask').hide(); $('#content').show()">
		<? endif;?>
			<img src="/p/img/fb_20120927/20121011_buttom.gif">	
		</a>
	</div>
	
</div>
</div>
</body>
</html>
