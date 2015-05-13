<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>:::龍邑 Web Game:::仙府之緣</title>

<style type="text/css">
body {
	margin-left: 0px;
	margin-top: 0px;
	margin-right: 0px;
	margin-bottom: 0px;
	background-color: #5aa3c5;
}
</style>
<link rel='stylesheet' type='text/css' href='http://xf.long_e.com.tw/p/css/default.css'>
<body>

<div style="width:760px; position:relative; margin:0 auto;">
	<div style="position:absolute; right:67px; top:510px;">
		<a href="http://www.long_e.com.tw/payment?site=xf" target="_blank"><img src="/p/minik/icon-pay.png"></a>
	</div>
</div>

<iframe id="invite_ifm" src="" width="0" height="0" frameborder="0"></iframe>

<table border="0" align="center" cellpadding="0" cellspacing="0">
  <tr>
    <td colspan="3"><img src="/p/minik/xf/img/img-01.jpg" width="760" height="174" /></td>
  </tr>
  <tr>
    <td><img src="/p/minik/xf/img/img-02.jpg" width="262" height="250" /></td>
    <td>
            <? if ($old_user):?>
        	<a href="javascript:;" onclick="chose_server_form()"><img src="/p/minik/xf/img/img-03.jpg" width="265" height="250" /></a>
        	<? else:?>
        	<a href="http://www.long_e.com.tw/play_game/xf" target="_blank"><img src="/p/minik/xf/img/img-03.jpg" width="265" height="250" /></a>
        	<? endif;?>  
    </td>
    <td><img src="/p/minik/xf/img/img-04.jpg" width="233" height="250" /></td>
  </tr>
  <tr>
    <td colspan="3"><img src="/p/minik/xf/img/img-05.jpg" width="760" height="128" usemap="#Map"/></td>
  </tr>
  <tr>
    <td colspan="3"><img src="/p/minik/xf/img/img-06.jpg" width="760" height="248" /></td>
  </tr>
</table>

<map name="Map" id="Map">
  <area shape="rect" coords="277,13,500,71" href="http://xf.long_e.com.tw" target="_blank" />
  <area shape="rect" coords="276,86,397,120" href="http://tw.socialgame.yahoo.net/mtag/mtag.php?ac=home&tagid=4817" target="_blank" />
  <area shape="rect" coords="397,86,500,120" href="javascript:invite_friend();" />
</map>

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
			left: '120px',
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