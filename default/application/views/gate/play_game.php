<!DOCTYPE HTML>
<html lang="zh-tw">
<head>
<meta charset="utf-8">

<title>龍邑《<?=$server->game_name?>》- <?=$server->name?></title>

<link rel="shortcut icon" href="http://<?=$server->game_id?>.long_e.com.tw/p/favicon.ico">

<script src="/p/js/jquery-1.8.3.min.js"></script>
<script src="/p/js/jquery-ui-1.9.1.min.js"></script>
<!-- <script src="http://layout.jquery-dev.net/lib/js/jquery.layout-latest.js"></script> -->
<script src="/p/js/jquery.layout-latest.js"></script>
<script src="/p/js/jquery.marquee.min.js"></script>
<script type="text/javascript">

var curl = window.location.toString();
if (curl.indexOf('#_=_') > 0 ) {
    var newval = curl.replace(/\#\_\=\_/,'');
    window.location = newval;
}
//document.domain = 'long_e.com.tw';

var my_layout;

$(function(){

	//if (top.location!=location) top.location=self.location;
	
	my_layout = $('body').layout({
		closable: true,
		slidable: false,
		resizable: false,
		livePaneResizing: true,
		spacing_closed: <?=$frame_conf['spacing']?>,
		spacing_open: <?=$frame_conf['spacing']?>,
		<?=$frame_conf['direction']?>__size:<?=$frame_conf['size']?>,
		center__maskContents: true // IMPORTANT - enable iframe masking				
	});
	
	$("img.change").mouseenter(function(){$(this).attr("src", $(this).attr("src").replace('off', 'on'))})
		.mouseleave(function(){$(this).attr("src", $(this).attr("src").replace('on', 'off'))});
});

</script>

<? if (ENVIRONMENT == "production") $this->load->view("templates/analyticstracking")?>

<style type="text/css">
img {border:0;}
ul {
	display: block;	
	list-style: none;
	padding: 0;
	margin: 0;	
}
#marquee_block ul {	
	position: relative;
	overflow: hidden;
}
#marquee_block ul li {
	position: absolute;
	top: -999em;
	left: 0;
	display: block;
	white-space: nowrap; /* keep all text on a single line */
}
.marquee ul {	
	position: relative;
	overflow: hidden;
}
.marquee ul li {
	position: absolute;
	top: -999em;
	left: 0;
	display: block;
	white-space: nowrap; /* keep all text on a single line */
}
</style>

</head>
<body>

<iframe id="mainFrame" name="mainFrame" class="ui-layout-center"
	width="100%" height="600" frameborder="0" scrolling="auto" src="<?=$game_url?>"></iframe>

<? if ($frame_conf['size']>'0'):?>
<div class="ui-layout-<?=$frame_conf['direction']?>">
	<? $this->load->view("gate/gamebar/".$server->game_id.".php")?>
</div>
<? endif;?>


</body></html>