<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>:::龍邑 Web Game:::絕代雙嬌</title>
<link href="/p/register/gt/css/index.css" rel="stylesheet" type="text/css" />

<script type="text/javascript" src="/p/register/gt/js/jquery-1.8.3.min.js"></script>
<script type="text/javascript" src="/p/js/jquery.validate.min.js"></script>
<script type="text/javascript" src="/p/js/jquery.form.js"></script>
<script type="text/javascript" src="/p/register/default.js"></script>
<script type="text/javascript" src="/p/register/gt/js/swfobject_modified.js"></script>

<script>	
var flashvars = {},
params = {wmode:"transparent"},
attributes = {};
swfobject.embedSWF("/p/register/gt/swf/flash.swf", "flashCon", "1400", "900", "9.0.0","/p/register/gt/swf/expressInstall.swf", flashvars, params, attributes);	

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

<body style="background:#000;">

	<div id="form" style="display:none; position:absolute; margin:27px 0px 0px 158px;">
	<form id="register_form" class="register-form" method="post" action="<?=site_url("gate/register_json/bw")?>">
		<input type="hidden" value="<?=$redirect_url?>" id="redirect_url">
		
<table border="0" align="center" cellpadding="0" cellspacing="0">
  <tr>
    <td colspan="2"><img src="/p/register/gt/img/registration-img-1.png" border="0" usemap="#Map" /></td>
  </tr>
  <tr>
    <td width="192"><img src="/p/register/gt/img/registration-img-2.png" /></td>
    <td width="588" align="left" valign="top" style="background:url(/p/register/gt/img/registration-img-4.png) top center no-repeat;"><table width="256" border="0" cellpadding="1" cellspacing="2">
      <tr>
        <td width="78"><img src="/p/register/gt/img/registration-img-31.png" height="21" /></td>
        <td colspan="2"><input type="text" name="account" size="20" maxlength="35" class="form-1 required" minlength="6" value="" /></td>
      </tr>
      <tr>
        <td><img src="/p/register/gt/img/registration-img-32.png" height="21" /></td>
        <td colspan="2"><input type="password" name="pwd" size="20" maxlength="35" class="form-1 required" value="" /></td>
      </tr>
      <tr>
        <td><img src="/p/register/gt/img/registration-img-33.png" height="21" /></td>
        <td colspan="2"><input type="password" name="pwd2" size="20" maxlength="35" class="form-1 required" value="" /></td>
      </tr>
      <tr>
        <td><img src="/p/register/gt/img/registration-img-34.png" height="21" /></td>
        <td width="86"><input type="text" name="captcha" size="5" maxlength="4" minlength="4" class="form-1 required" value="" /></td>
        <td width="136"><div id="captcha_area"></div></td>
      </tr>
      <tr>
        <td colspan="3"><img src="/p/register/gt/img/hr.png" width="304" height="11" /></td>
        </tr>
      <tr>
        <td colspan="3"><table border="0" align="center" cellpadding="2" cellspacing="1">
          <tr>
            <td>
<?php 
	$items = get_channel_items("gt", "../");
	foreach($items as $channel => $name):
?>
	<a href="http://www.longeplay.com.tw/gate/login/gt?channel=<?=$channel?>&ad=<?=$ad?>"><img src="http://www.longeplay.com.tw/img/login/<?=$channel?>.png" width="30" title="<?=$name?> 帳號登入"/></a>
<? endforeach;?>              
            </td>
          </tr>
        </table></td>
        </tr>
      <tr>
        <td colspan="3" align="center">
        	<div id="enter"><a href="javascript:;" onclick="$('#register_form').submit();"><img src="/p/register/gt/img/registration-img-7.png" border="0" /></a></div>
       	</td>
       </tr>
    </table></td>
  </tr>
</table>

<map name="Map" id="Map">
  <area shape="rect" coords="571,78,632,141" href="javascript:hideForm()"/>
</map>

    </form>

</div>


  <div id="flashCon">
    <p><a href="http://www.adobe.com/go/getflashplayer"><img src="http://www.adobe.com/images/shared/download_buttons/get_flash_player.gif" alt="Get Adobe Flash player" /></a></p>
  </div>
</body>
</html>



