<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>:::龍邑 Web Game:::炎黃之魂</title>
<link href="/p/register/yh/css/index.css" rel="stylesheet" type="text/css" />

<script type="text/javascript" src="/p/register/yh/js/jquery-1.8.3.min.js"></script>
<script type="text/javascript" src="/p/js/jquery.validate.min.js"></script>
<script type="text/javascript" src="/p/js/jquery.form.js"></script>
<script type="text/javascript" src="/p/register/default.js"></script>
<script type="text/javascript" src="/p/register/yh/js/swfobject_modified.js"></script>

<script>	
var flashvars = {},
params = {wmode:"transparent"},
attributes = {};
swfobject.embedSWF("/p/register/yh/swf/page.swf", "flashCon", "1000", "630", "9.0.0","/p/register/yh/swf/expressInstall.swf", flashvars, params, attributes);	

	function showForm(){
	  $("#form").fadeIn(500);
	}
	
	function hideForm(){
		$("#form").fadeOut(500);
	}


</script>

<? if ($ad == 'google'):?>

				
<? endif;?>


</head>

<body>
<div id="main" style="margin:0 auto; width:1000px;">
	<div id="form" style="display:none; position:absolute; margin:27px 0px 0px 158px;">
	<form id="register_form" class="register-form" method="post" action="<?=site_url("gate/register_json/yh")?>">
		<input type="hidden" value="<?=$redirect_url?>" id="redirect_url">
		
		

<table border="0" align="center" cellpadding="0" cellspacing="0">
  <tr>
    <td colspan="2"><img src="/p/register/yh/img/registration-img-1.png" width="680" height="192" border="0" usemap="#Map" /></td>
  </tr>
  <tr>
    <td width="118"><img src="/p/register/yh/img/registration-img-2.png" width="118" height="287" /></td>
    <td width="562" align="left" valign="top" style="background:url(/p/register/yh/img/registration-img-4.png) top center no-repeat;"><table width="256" border="0" cellpadding="1" cellspacing="2">
      <tr>
        <td width="78"><img src="/p/register/yh/img/registration-img-31.jpg" height="21" /></td>
        <td colspan="2"><input type="text" name="account" size="20" maxlength="35" class="form-1 required" minlength="6" value="" /></td>
      </tr>
      <tr>
        <td><img src="/p/register/yh/img/registration-img-32.jpg" height="21" /></td>
        <td colspan="2"><input type="password" name="pwd" size="20" maxlength="35" class="form-1 required" value="" /></td>
      </tr>
      <tr>
        <td><img src="/p/register/yh/img/registration-img-33.jpg" height="21" /></td>
        <td colspan="2"><input type="password" name="pwd2" size="20" maxlength="35" class="form-1 required" value="" /></td>
      </tr>
      <tr>
        <td><img src="/p/register/yh/img/registration-img-34.jpg" height="21" /></td>
        <td width="86"><input type="text" name="captcha" size="5" maxlength="4" minlength="4" class="form-1 required" value="" /></td>
        <td width="136"><div class="verification" style="float:left;"><div id="captcha_area"></div></div></td>
      </tr>
      <tr>
        <td colspan="3">
<?php 
	$items = get_channel_items("yh", "../");
	foreach($items as $channel => $name):
?>
	<a href="http://www.longeplay.com.tw/gate/login/yh?channel=<?=$channel?>&ad=<?=$ad?>"><img src="http://www.longeplay.com.tw/img/login/<?=$channel?>.png" width="34" title="<?=$name?> 帳號登入"/></a>
<? endforeach;?>  		
		</td>
      </tr>
      <tr>
        <td></td>
        <td colspan="2"><a href="javascript:;" onclick="$('#register_form').submit();"><img src="/p/register/yh/img/registration-img-7.png" width="166" height="45" border="0" /></a></td>
      </tr>
    </table></td>
  </tr>
</table>
    
    </form>
  </div>
  
<map name="Map" id="Map">
  <area shape="rect" coords="480,96,541,159" href="javascript:hideForm();" />
</map>

  <div id="flashCon">
    <p><a href="http://www.adobe.com/go/getflashplayer"><img src="http://www.adobe.com/images/shared/download_buttons/get_flash_player.gif" alt="Get Adobe Flash player" /></a></p>
  </div>
</div>
</body>
</html>



