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

</head>
<body class="pure">

<div id="container">

	<div id="main">
		<div class="hd"></div>
		<div class="bd">
			<?=$layout_content?>
		</div>
		<div class="ft"></div>
	</div>	
					
</div>



</body>
</html>
