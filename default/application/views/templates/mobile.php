<!DOCTYPE HTML>
<html lang="zh-tw">
<head>
<title><?=$meta["title"]?></title>
<meta charset="utf-8"> 
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<? 
	unset($meta["title"]);
	foreach($meta as $key => $content) {
		echo "<meta name='{$key}' content='{$content}'>";
	}
	echo $css_link;
	echo $js_include;
?>
<style type="text/css">
html {height:100%;}
body {height:100%; background:#eee; font-family:"微軟正黑體"; font-size:15px; line-height:1.5em;}
#container {height:100%; width:100%; background:none; position:relative;}
#container2 {padding:6px; position:relative;}
</style>
</head>
<body class="mobile">

<div id="container">
	<div id="container2">
		<?=$layout_content?>
	</div>					
</div>

<script type="text/javascript">
$(function(){
	var viewport = document.querySelector("meta[name=viewport]");
	if ($('#container').width() < 660) {
		viewport.setAttribute("content", "width:device-width, initial-scale=1.0, user-scalable=1");
	}
	else {
		viewport.setAttribute("content", "width:device-width, initial-scale=2.0, user-scalable=1");
	} 	
});
</script>

</body>
</html>
