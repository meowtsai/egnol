<!DOCTYPE HTML>
<html lang="zh-tw">
<head>
<title>::: 龍邑遊戲 ‧ Cooz Games :::</title>
<meta charset="utf-8"> 
<meta http-equiv="X-UA-Compatible" content="IE=EmulateIE9" /> 
<style type="text/css">
html body {padding:0; margin:0;}
img {border:0;}
</style>
</head>
<body style="background:url(/p/img/event/web_zj/bk.jpg) top center;">

<div id="fb-root"></div>
<script>(function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) return;
  js = d.createElement(s); js.id = id;
  js.src = "//connect.facebook.net/zh_TW/sdk.js#xfbml=1&appId=769806486415719&version=v2.0";
  fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));</script>

<div style="height:2600px; width:1000px; margin:0 auto; position:relative;">

	<div style="position:absolute; top:150px; left:233px;">
		<a href="http://zj.long_e.com.tw/platform">
			<img src="/p/img/event/web_zj/logo.png">
		</a>
	</div>
	
	<div style="position:absolute; top:414px; left:298px;">
		<iframe width="436" height="247" src="//www.youtube.com/embed/pf-7WuWgcsM?rel=0&amp;controls=0&autoplay=1&loop=1&playlist=pf-7WuWgcsM" frameborder="0" allowfullscreen></iframe>
	</div>
	
	<div style="position:absolute; top:851px; left:139px;">
 		<img src="http://zj.long_e.com.tw/p/img/qrcode.png" style="width:97px">
	</div>
	
	<div style="position:absolute; top:851px; left:244px;">
		<a href="https://play.google.com/store/apps/details?id=com.long_e.zj" target="_blank"><img src="/p/img/event/web_zj/googleplay.png"></a>
	</div>
	
	<div style="position:absolute; top:910px; left:244px;">
		<img src="/p/img/event/web_zj/apple.png">
	</div>
	
	<div style="position:absolute; top:851px; left:384px;">
		<a href="http://zj.long_e.com.tw/p/file/zj_1.01.apk" target="_blank"><img src="http://zj.long_e.com.tw/p/img/download_apk.png" style="width:136px"></a>	
	</div>
	
	<div style="position:absolute; top:910px; left:384px;">
		<a href="http://zj.long_e.com.tw/p/file/zj_1.01.ipa" target="_blank"><img src="http://zj.long_e.com.tw/p/img/download_jb.png" style="width:136px"></a>
	</div>	
	
	<div style="position:absolute; top:851px; left:575px;">
		<? if ($uid):?>
			<? if ($code):?>
				<div>
					<div style="font-size:13px; color:#777;">您的兌換碼為：</div>
					<span style="color:blue; font-size:13px;"><?=$code?></span>
				</div>
			<? else:?>
				<span style="color:red; font-size:13px;">要獲得序號必須先點</span> 
				<div class="fb-like" data-href="https://www.facebook.com/long_e.inc" data-layout="button" data-action="like" data-show-faces="false" data-share="false"></div>
			<? endif;?>
			<? if ($msg):?>
				<div>
					<?=$msg?>
				</div>
			<? endif;?>
		<? else:?>
		<a href="/event/web_fb_zj_login">
			<img src="/p/img/event/web_zj/fb.png">
		</a>
		<? endif;?>
	</div>

</div>

<script type="text/javascript">
 
window.fbAsyncInit = function() {
    //建立Like Button 點擊呈現 link 的eventhandle
    FB.Event.subscribe('edge.create', function(response) {
        window.location.reload();
    });
    //建立Like Button 點擊呈現 unlike 的eventhandle
    FB.Event.subscribe('edge.remove', function(response) {
    	window.location.reload();
    });
};

</script>

</body>
</html>