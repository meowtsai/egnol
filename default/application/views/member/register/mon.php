<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>:::龍邑 Web Game:萌寵過江</title>
<link href="/p/register/mon/index.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.8.2/jquery.min.js"></script>
<script type="text/javascript" src="/p/js/jquery.validate.min.js"></script>
<script type="text/javascript" src="/p/js/jquery.form.js"></script>
<script type="text/javascript" src="/p/register/default.js"></script>
<body>

<form id="register_form" class="register-form" method="post" action="<?=site_url("gate/register_json/mon")?>">
<input type="hidden" value="<?=$redirect_url?>" id="redirect_url">

<table border="0" align="center" cellpadding="0" cellspacing="0">
  <tr>
    <td colspan="3"><img src="/p/register/mon/img/img-01.jpg" width="923" height="268" /></td>
  </tr>
  <tr>
    <td width="95"><img src="/p/register/mon/img/img-02.jpg" width="95" height="363" /></td>
    <td width="285" align="left" valign="top" style="background:url(/p/register/mon/img/img-03-bg.jpg) top center no-repeat;"><table width="256" border="0" cellpadding="1" cellspacing="2">
      <tr>
        <td width="55"><img src="/p/register/mon/img/title-01.png" height="36" /></td>
        <td width="201"><input type="text" name="account" size="24" maxlength="35" class="form-1 required" minlength="6" value="" /></td>
      </tr>
      <tr>
        <td><img src="/p/register/mon/img/title-02.png" height="36" /></td>
        <td><input type="password" name="pwd" size="24" maxlength="35" class="form-1 required" value="" /></td>
      </tr>
      <tr>
        <td><img src="/p/register/mon/img/title-03.png" height="36" /></td>
        <td><input type="password" name="pwd2" size="24" maxlength="35" class="form-1 required" value="" /></td>
      </tr>
      <tr>
        <td style="font-size:12px; color:#ee0; text-align:center;">驗證碼</td>
        <td>
        	
	        <input type="text" name="captcha" size="5" maxlength="4" minlength="4" class="text-input required" value="" />
	        <div class="verification" style="float:left;"><div id="captcha_area"></div></div>
        </td>
      </tr>      
      <tr>
        <td colspan="2" style="text-align:center; padding-top:12px;"><a href="javascript:;" onclick="$('#register_form').submit(); "><img src="/p/register/mon/img/icon.png" border="0" /></a></td>
      </tr>
      <tr>
        <td colspan="2">
        <table border="0" align="center" cellpadding="2" cellspacing="1">
          <tr>
          	<td>
<?php 
	$items = get_channel_items("mon", "../");
	foreach($items as $channel => $name):
?>
	<a href="http://www.long_e.com.tw/gate/login/mon?channel=<?=$channel?>&ad=<?=$ad?>"><img src="http://www.long_e.com.tw/img/login/<?=$channel?>.png" width="31" title="<?=$name?> 帳號登入"/></a>
<? endforeach;?>
           	
          	</td>          
          </tr>
        </table></td>
      </tr>
    </table></td>
    <td width="543" align="right"><img src="/p/register/mon/img/img-04.jpg" height="363" /></td>
  </tr>
</table>

</form>

</body>
</html>
