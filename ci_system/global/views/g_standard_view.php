<!DOCTYPE HTML>
<html lang="zh-tw">
<head>
	<title><?=$meta["title"]?></title>
	<link rel="icon" type="image/png" href="<?=$game_url?>p/img/favicon.png" />
    <link rel='stylesheet' type='text/css' href='<?=$longe_url?>p/css/default.css'>
	<script src='<?=$longe_url?>p/js/jquery-1.7.2.min.js'></script>
	<script src='<?=$longe_url?>p/js/default.js'></script>
    <meta name=viewport content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=0">
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=EmulateIE9" />
	<?
		unset($meta["title"]);
		foreach($meta as $key => $content)
		{
			echo "<meta name='{$key}' content='{$content}'>";
		}
		echo $css_link;
		echo $js_include;
	?>
</head>
<body>
  <div id="container">
  	<? $this->load->view("g_top_bar"); ?>
	<div id="content_frame">
		<div id="content_view">
			<?=$layout_content?>
		</div>
	</div>
   	<? $this->load->view("g_copyright")?>
  </div>
</body>
</html>
