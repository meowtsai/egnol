<!DOCTYPE HTML>
<html lang="zh-tw">
<head>
<title><?=$meta["title"]?></title>
<meta charset="utf-8"> 
<meta http-equiv="X-UA-Compatible" content="IE=EmulateIE9" /> 
<? 
	unset($meta["title"]);
	foreach($meta as $key => $content) {
		echo "<meta name='{$key}' content='{$content}'>";
	}
	echo $css_link;
	echo $js_include;
?>

<style>
body {background:#000;}
#container {background:url(/p/img/event/zj_1106/bk_1.jpg) no-repeat top center;}
#main {width:1000px; margin:0 auto; min-height:800px; position:relative;}
</style>

<script>
$(function(){
	$('.change').on('mouseover', function(){$(this).attr("src", $(this).attr("src").replace("off", "on"));})
		.on('mouseout', function(){$(this).attr("src", $(this).attr("src").replace("on", "off"));});
	
})
</script>
</head>
<body>

<div id="fb-root"></div>
<script>(function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) return;
  js = d.createElement(s); js.id = id;
  js.src = "//connect.facebook.net/zh_TW/sdk.js#xfbml=1&appId=656319951150547&version=v2.0";
  fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));</script>

<div id="container">

	<div id="main">

		<div style="position:absolute; top:5px; right:77px; width:334px; height:354px; background:url(/p/img/event/zj_1106/QR.png)">
		</div>
		
		<div style="position:absolute; top:23px; left:-10px;">
			<a href="http://zj.long_e.com.tw/platform"><img src="/p/img/event/zj_1106/logo.png"></a>
		</div>
		
		<div style="position:absolute; top:-2px; right:25px;">
			<div class="fb-like-box" data-href="https://www.facebook.com/long_e.inc" data-colorscheme="dark" data-show-faces="false" data-header="false" data-stream="false" data-show-border="false"></div>
		</div>
		
		<div style="position:absolute; top:325px; left:453px;">
			<a href="http://zj.long_e.com.tw/p/file/zj_1.01.ipa"><img src="/p/img/event/zj_1106/apple_IPA.png"></a>
			<a href="http://zj.long_e.com.tw/p/file/zj_1.01.apk"><img src="/p/img/event/zj_1106/googleplay_apk.png"></a>
			<a href="https://play.google.com/store/apps/details?id=com.long_e.zj" target="_blank"><img src="/p/img/event/zj_1106/googleplay.png"></a>
<!-- 			<a href="javascript:alert('敬請期待');"><img src="/p/img/event/zj_1106/apple.png"></a> -->
		</div>
		
		<div style="position:absolute; top:392px; left:72px;">
			<a href="/event/zj_1106_p1"><img src="/p/img/event/zj_1106/event1_off.png" class="change"></a>
			<a href="/event/zj_1106_p2"><img src="/p/img/event/zj_1106/event2_off.png" class="change"></a>
			<a href="/event/zj_1106_p3"><img src="/p/img/event/zj_1106/event3_off.png" class="change"></a>
			<a href="/event/zj_1106_p4"><img src="/p/img/event/zj_1106/event4_off.png" class="change"></a>			
		</div>		
		
		<div style="background:url(/p/img/event/zj_1106/bk_2.jpg) repeat-y 38px top; position:relative; top:453px;">
			<?=$layout_content?>			
		</div>

	</div>	
					
</div>



</body>
</html>
