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
<body class="inner2">
<div id="skybar"></div>
<div id="fb-root"></div>
<script>(function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) return;
  js = d.createElement(s); js.id = id;
  js.src = "//connect.facebook.net/zh_TW/all.js#xfbml=1&appId=338080079868";
  fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));</script>

<div id="container" class="inner2">

	<? $this->load->view("templates/_top_bar")?>

	<div id="spt" class="boundary">
	
		<div class="spt01">
		
			<? $this->load->view("templates/_login");?>
			
			<? if ( ! empty($submenu)):?>
			<div id="submenu" class="<?=$submenu?>">
				<? $this->load->view("templates/submenu/{$submenu}")?>
			</div>
			<? endif;?>
			
		</div>
		
		<div class="spt02">		
			
			<h2>
				<span class="path">目前位置：<?=$layout_breadcrumb?></span>
				<? if ( ! empty($subtitle)) echo $subtitle;?>
			</h2>
			
			<div id="main">
				<div class="hd"></div>
				<div class="bd">
					<?=$layout_content?>
				</div>
				<div class="ft"></div>
			</div>
		
		</div>
		
		<div class="clear"></div>
		
	</div>
	
	<div class="clear"></div>
	
	<? $this->load->view("templates/_footer")?>
</div>

</body>
</html>
