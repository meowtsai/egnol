<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?=$meta["title"]?></title>

<link rel="shortcut icon" href="<?=$longe_url?>p/img/favicon.png"/>
<link rel="Bookmark" href=""/>
<meta name="keywords" content="">
<meta name="description" content="" />
<meta name="viewport" content="width=device-width; initial-scale=1.0; maximum-scale=1.0; user-scalable=0;">
<link href="<?=$longe_url?>p/css/reset_api.css" rel="stylesheet" type="text/css" />
<link href="<?=$longe_url?>p/css/primary.css" rel="stylesheet" type="text/css" />
<? if(empty($is_system_page)) { ?>
<link href="<?=$game_url?>p/css/primary.css" rel="stylesheet" type="text/css" />
<? } ?>
<!--[if lt IE 9]>
<script src="<?=$longe_url?>p/js/html5shiv.min.js"></script>
<![endif]-->
<!--[if (gte IE 6)&(lte IE 8)]>
<script type="text/javascript" src="<?=$longe_url?>p/js/selectivizr-min.js"></script>
<![endif]-->
<!--[if (gte IE 6)&(lte IE 8)]>
<script type="text/javascript" src="<?=$longe_url?>p/js/respond.js"></script>
<![endif]-->
<!--[if (gte IE 6)&(lte IE 8)]>
<script type="text/javascript" src="<?=$longe_url?>p/js/background_size_emu.js"></script>
<![endif]-->
<script type="text/javascript" src="https://code.jquery.com/jquery-1.11.0.min.js"></script>
<script type="text/javascript" src="https://code.jquery.com/jquery-migrate-1.2.1.min.js"></script>
<script src="https://s3.amazonaws.com/nwapi/nwmatcher/nwmatcher-1.2.5-min.js"></script>
<?
	unset($meta["title"]);
	foreach($meta as $key => $content)
	{
		echo "<meta name='{$key}' content='{$content}'>";
	}
	echo $css_link;
?>
<script src='<?=$longe_url?>p/js/default.js'></script>
<?
	echo $js_include;
?>
</head>
<body>
<?=$layout_content?>
</body>
</html>
