<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>:::龍邑 Web Game:::三國風雲</title>
<link rel='stylesheet' type='text/css' href='http://sg2.longeplay.com.tw/p/css/default.css'>
<link href="/p/minik/sg2/index.css" rel="stylesheet" type="text/css" />
<body>

<iframe id="invite_ifm" src="" width="0" height="0" frameborder="0"></iframe>

<div style="width:1044px; position:relative; margin:0 auto;">
	<div style="position:absolute; right:186px; top:416px;">
		<a href="http://www.longeplay.com.tw/payment?site=sg2" target="_blank"><img src="/p/minik/icon-pay.png"></a>
	</div>
</div>

<table width="100%" border="0" cellpadding="0" cellspacing="0">
  <tr>
    <td align="center" style="background:url(/p/minik/sg2/img/bg.jpg) top center no-repeat;"><table border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td colspan="3" align="left"><img src="/p/minik/sg2/img/img-01.jpg" width="362" height="146" /><img src="/p/minik/sg2/img/img-02.jpg" width="324" height="146" /><img src="/p/minik/sg2/img/img-03.jpg" width="358" height="146" /></td>
        </tr>
      <tr>
        <td colspan="3"><img src="/p/minik/sg2/img/img-04.jpg" width="1044" height="239" /></td>
        </tr>
      <tr>
        <td><img src="/p/minik/sg2/img/img-05.jpg" width="337" height="132" /></td>
        <td>
        	<? if ($old_user):?>
        	<a href="javascript:;" onclick="chose_server_form()">
        		<img src="/p/minik/sg2/img/img-06.jpg" width="302" height="132" border="0" /></a>
        	<? else:?>
        	<a href="http://www.longeplay.com.tw/play_game/sg2" target="_blank">
        		<img src="/p/minik/sg2/img/img-06.jpg" width="302" height="132" border="0" /></a>
        	<? endif;?>        	
        </td>
        <td><img src="/p/minik/sg2/img/img-07.jpg" width="405" height="132" /></td>
      </tr>
      <tr>
        <td colspan="3"><img src="/p/minik/sg2/img/img-08.jpg" width="1044" height="260" border="0" usemap="#Map" /></td>
        </tr>
    </table></td>
  </tr>
</table>

<map name="Map" id="Map">
  <area shape="rect" coords="614,-10,743,50" href="http://sg2.longeplay.com.tw" target="_blank" />
  <area shape="rect" coords="746,-14,867,49" href="http://tw.socialgame.yahoo.net/mtag/mtag.php?ac=home&tagid=4805" target="_blank" />
  <area shape="rect" coords="869,-8,989,51" href="javascript:invite_friend();" />
</map>

<div id="choose" style="display:none;"><?=$choose_server_form?></div>

<script type="text/javascript" src="http://www.longeplay.com.tw/p/js/jquery-1.7.2.min.js"></script>
<script type="text/javascript" src='http://www.longeplay.com.tw/p/js/jquery.blockUI.js'></script>
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
