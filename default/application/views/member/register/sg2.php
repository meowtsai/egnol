<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>:::龍邑 Web Game:::三國風雲2</title>
<link href="/p/register/sg2/index.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.8.2/jquery.min.js"></script>
<script type="text/javascript" src="/p/js/jquery.validate.min.js"></script>
<script type="text/javascript" src="/p/js/jquery.form.js"></script>
<script type="text/javascript" src="/p/register/default.js"></script>
<body>

<form id="register_form" class="register-form" method="post" action="<?=site_url("gate/register_json/sg2")?>">
<input type="hidden" value="<?=$redirect_url?>" id="redirect_url">

<table border="0" align="center" cellpadding="0" cellspacing="0">
  <tr>
    <td><img src="/p/register/sg2/img/img-01.jpg" width="369" height="246" /></td>
    <td><img src="/p/register/sg2/img/img-02.jpg" width="221" height="246" /></td>
    <td><img src="/p/register/sg2/img/img-03.jpg" width="349" height="246" /></td>
  </tr>
  <tr>
    <td><img src="/p/register/sg2/img/img-04.jpg" width="369" height="107" /></td>
    <td rowspan="2" bgcolor="#000000"><table border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td width="81" valign="top"><img src="/p/register/sg2/img/login-01.jpg" width="81" height="23" /></td>
        <td colspan="2" align="center" valign="middle" style="background:url(/p/register/sg2/img/login-bg.jpg) no-repeat;"><input type="text" name="account" size="24" maxlength="35" class="form-1 required" minlength="6" value="" /></td>
      </tr>
      <tr>
        <td height="5" colspan="3"></td>
        </tr>
      <tr>
        <td><img src="/p/register/sg2/img/login-02.jpg" alt="" width="81" height="23" /></td>
        <td colspan="2" align="center" valign="middle" style="background:url(/p/register/sg2/img/login-bg.jpg) no-repeat;">
          <input type="password" name="pwd" size="24" maxlength="35" class="form-1 required" value="" />
        </span></td>
      </tr>
      <tr>
        <td height="5" colspan="3"></td>
        </tr>
      <tr>
        <td><img src="/p/register/sg2/img/login-03.jpg" width="81" height="23" /></td>
        <td colspan="2" align="center" valign="middle" style="background:url(/p/register/sg2/img/login-bg.jpg) no-repeat;">
          <input type="password" name="pwd2" size="24" maxlength="35" class="form-1 required" value="" />
        </span></td>
      </tr>
      <tr>
        <td height="5" colspan="3"></td>
        </tr>
      <tr>
        <td valign="top"><img src="/p/register/sg2/img/login-04.jpg" width="81" height="23" /></td>
        <td width="70" height="23" align="center" valign="middle" style="background:url(/p/register/sg2/img/login-bg2.jpg) no-repeat;"><input type="text" name="captcha" size="5" maxlength="4" minlength="4" class="form-2 required" value="" /></td>
        <td width="70" align="center">
	        <div class="verification" style="float:left;"><div id="captcha_area"></div></div>
        </td>
      </tr>
         <tr>
        <td height="6" colspan="3"></td>
        </tr>
         <tr>
           <td height="10" colspan="3">
           	<a href="javascript:;" onclick="$('#register_form').submit(); "><img src="/p/register/sg2/img/img-10.jpg" width="221" height="51" /></a>
           </td>
         </tr>
         <tr>
           <td height="6" colspan="3"></td>
         </tr>
         <tr>
           <td height="5" colspan="3" align="left">
<?php 
	$items = get_channel_items("sg2", "../");
	foreach($items as $channel => $name):
?>
	<a href="http://www.longeplay.com.tw/gate/login/sg2?channel=<?=$channel?>&ad=<?=$ad?>"><img src="http://www.longeplay.com.tw/img/login/<?=$channel?>.png" width="28" title="<?=$name?> 帳號登入"/></a>
<? endforeach;?>             
           
           </td>
        </tr>
    </table></td>
    <td><img src="/p/register/sg2/img/img-05.jpg" width="349" height="107" /></td>
  </tr>
  <tr>
    <td><img src="/p/register/sg2/img/img-06.jpg" width="369" height="142" /></td>
    <td><img src="/p/register/sg2/img/img-07.jpg" width="349" height="142" /></td>
  </tr>
  <tr>
    <td colspan="3"><img src="/p/register/sg2/img/img-08.jpg" width="939" height="78" /></td>
  </tr>
</table>

</form>

</body>
</html>
