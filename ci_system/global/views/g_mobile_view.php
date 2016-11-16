<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?=$meta["title"]?></title>

<link rel="shortcut icon" href="<?=$longe_url?>p/img/favicon.png"/>
<link rel="Bookmark" href=""/>
<meta name="keywords" content="">
<meta name="description" content="" />
<meta name="viewport" content="width=1000px, user-scalable=1">
<script type="text/javascript" src="https://code.jquery.com/jquery-1.11.0.min.js"></script>

<?
	unset($meta["title"]);
	foreach($meta as $key => $content)
	{
		echo "<meta name='{$key}' content='{$content}'>";
	}
	echo $css_link;
?>
<?
	echo $js_include;
?>
</head>
<body>
<?=$layout_content?>
</body>
</html>
