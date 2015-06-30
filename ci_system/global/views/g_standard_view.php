<!DOCTYPE HTML>
<html lang="zh-tw">
<head>
	<title><?=$meta["title"]?></title>
	<link rel="icon" type="image/png" href="<?=$game_url?>p/img/favicon.png" />
    <link rel='stylesheet' type='text/css' href='/p/css/common.css'>
    <link rel='stylesheet' type='text/css' href='/p/css/jquery-ui-1.8.22.custom.css'>
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
	<?//<meta property="qc:admins" content="2601272605637726375647" />?>
</head>
<body>
  <div id="container">
  	<? $this->load->view("g_top_bar", Array("site" => $site)); ?>
	<?=$layout_content?>
   	<? $this->load->view("g_copyright")?>
  </div>
</body>
</html>
