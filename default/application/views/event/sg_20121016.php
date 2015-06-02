<!DOCTYPE HTML>
<html lang="zh-tw">
<head>
<title></title>
<meta charset="utf-8">
<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.8.2/jquery.min.js"></script>
<style type="text/css">
body {background:url('/p/img/sg_20121016/20121011_bg_compressed.jpg') no-repeat center top; padding:0px; margin:0px;}
#container {height:1200px; position:relative;}
#msg {width:300px; margin:0 auto; position:relative; left:30px; top:0px; color:#fff;}
#code {display:inline-block; margin-left:6px; padding:2px 5px; vertical-align:bottom; font-size:18px; background:#fff; border:1px solid #aaa; color:#000}
.s {background:url('/p/img/sg_20121016/s.png') no-repeat; width:517px; height:182px; }
.s div {padding:60px 80px;}
.s a {color:#fff;}
#content {background:url('/p/img/sg_20121016/20121011_OK.png') no-repeat; width:517px; height:182px;}
#content2 {padding:46px 0 0 232px;}
#content2 a {color:#fff;}
</style>
</head>
<body>
<div id="container">

	
	
<div id="msg">
	<div style="position:relative; margin-left:-100px ">
		<a href="http://sg2.long_e.com.tw"><img src="/p/img/sg_20121016/20121011_logo.png"></a>
	</div>
		
	<? if ($code):?>
	<div id="content" style="display:none; position:absolute; left:0; top:600px; ">
		<div id="content2">
			<span id="code"><?=$code?></span>
			<div style="margin:6px;">
			<a href="/gate/login_game/sg2" target="_blank">進入遊戲</a> | 
			<a href="http://sg2.long_e.com.tw" target="_blank">開啟官網</a>
			</div>
		</div>
	</div>
	<? endif;?>
	
	<div id="mask" style="position:absolute; left:0; top:600px; ">
		<? if ($status == 0):?>
		<div class="s">
			<div><a href="http://www.long_e.com.tw/member/login.php?returnUrl=http://www.long_e.com.tw/event/sg_20121016">請先登入long_e平台</a></div>
		</div>		
		<? elseif ($status == 1):?>
		<a href="javascript:;" onclick="$('#mask').hide(); $('#content').show()">
			<div class="s">
				<img src="/p/img/sg_20121016/g.gif">
			</div>	
		</a>
		<? elseif ($status == 2):?>
		<img src="/p/img/sg_20121016/s2.png">
		<? elseif ($status == 3):?>
		<div class="s">
			<div>序號已發放完畢。</div>
		</div>
		<? endif;?>
	</div>
	
</div>
</div>
</body>
</html>
