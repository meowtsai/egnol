<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>:::龍邑 Web Game:::萌寵過江</title>

<style type="text/css">
body {
	margin-left: 0px;
	margin-top: 0px;
	margin-right: 0px;
	margin-bottom: 0px;
	background-color: #976c40;
}
</style>
<link rel='stylesheet' type='text/css' href='http://mon.long_e.com.tw/p/css/default.css'>

<body>

<div style="width:760px; position:relative; margin:0 auto;">
	<div style="position:absolute; right:3px; top:150px;">
		<a href="http://www.long_e.com.tw/payment?site=mon" target="_blank"><img src="/p/minik/icon-pay.png"></a>
	</div>
</div>

<table border="0" align="center" cellpadding="0" cellspacing="0">
  <tr>
    <td><img src="/p/minik/mon/img/img-01.jpg" width="760" height="220" border="0" usemap="#Map" /></td>
  </tr>
  <tr>
    <td><img src="/p/minik/mon/img/img-02.jpg" width="760" height="68" border="0" usemap="#Map2" /></td>
  </tr>
  <tr>
    <td><img src="/p/minik/mon/img/img-03.jpg" width="760" height="239" /></td>
  </tr>
  <tr>
    <td><img src="/p/minik/mon/img/img-04.jpg" width="760" height="193" /></td>
  </tr>
</table>

<map name="Map" id="Map">
	<? if ($old_user):?>
	<area shape="rect" coords="382,116,676,225" href="javascript:chose_server_form();">
	<? else:?>
  	<area shape="rect" coords="382,116,676,225" href="http://www.long_e.com.tw/play_game/mon" target="_blank" />
  	<? endif;?>
</map>

<map name="Map2" id="Map2">
  <area shape="rect" coords="367,12,457,52" href="http://mon.long_e.com.tw" target="_blank"  />
  <area shape="rect" coords="470,12,574,54" href="http://tw.socialgame.yahoo.net/mtag/mtag.php?ac=home&tagid=4807" target="_blank" />
  <area shape="rect" coords="576,12,664,57" href="http://mon.long_e.com.tw/bulletin/get_list" target="_blank" />
</map>

<iframe id="invite_ifm" src="" width="0" height="0" frameborder="0"></iframe>
<div id="choose" style="display:none;"><?=$choose_server_form?></div>

<script type="text/javascript" src="http://www.long_e.com.tw/p/js/jquery-1.7.2.min.js"></script>
<script type="text/javascript" src='http://www.long_e.com.tw/p/js/jquery.blockUI.js'></script>
<script type="text/javascript">
function invite_friend() {
	$('#invite_ifm').attr("src", "<?=$inviteSrc?>");
}
function chose_server_form() {
	$.blockUI({ message: $("#choose"),
		css: { 
			top: '30px',
			backgroundColor: '',
			cursor: 'auto',
			border: '0'
		}}); 
	$('.blockOverlay').click($.unblockUI);
	$("#choose").find('.close').click($.unblockUI);		
}
</script>
</body>
</html>

