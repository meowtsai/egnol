<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>:::龍邑 Web Game:::仙府之緣</title>

<link href="/p/register/xf/index.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.8.2/jquery.min.js"></script>
<script type="text/javascript" src="/p/js/jquery.validate.min.js"></script>
<script type="text/javascript" src="/p/js/jquery.form.js"></script>
<script type="text/javascript" src="/p/register/default.js"></script>

<body>

<form id="register_form" class="register-form" method="post" action="<?=site_url("gate/register_json/xf")?>">
<input type="hidden" value="<?=$redirect_url?>" id="redirect_url">

<table border="0" align="center" cellpadding="0" cellspacing="0">
  <tr>
    <td><img src="/p/register/xf/img/img-01.jpg" width="501" height="212" /></td>
    <td><img src="/p/register/xf/img/img-03.jpg" width="575" height="212" border="0" usemap="#MapMap" /></td>
  </tr>
  <tr>
    <td><img src="/p/register/xf/img/img-02.jpg" width="501" height="388" /></td>
    <td valign="top" style="background:url(/p/register/xf/img/img-04-bg.jpg) top center no-repeat;"><table width="284" border="0" cellpadding="0" cellspacing="0">
      <tr>
        <td width="89"><img src="/p/register/xf/img/img-title-01.jpg" width="89" height="25" /></td>
        <td colspan="2" style="background:url(/p/register/xf/img/img-title-bg.png) no-repeat;">
        	<input type="text" name="account" size="24" maxlength="35" class="form-1 required" minlength="6" value="" />
        	</td>
      </tr>
      <tr>
        <td height="4" colspan="3"></td>
      </tr>
      <tr>
        <td><img src="/p/register/xf/img/img-title-02.jpg" width="89" height="25" /></td>
        <td colspan="2" style="background:url(/p/register/xf/img/img-title-bg.png) no-repeat;">
        	<input type="password" name="pwd" size="24" maxlength="35" class="form-1 required" value="" />
        	</td>
      </tr>
      <tr>
        <td height="4" colspan="3"></td>
      </tr>
      <tr>
        <td><img src="/p/register/xf/img/img-title-03.jpg" width="89" height="25" /></td>
        <td colspan="2" style="background:url(/p/register/xf/img/img-title-bg.png) no-repeat;">
        	<input type="password" name="pwd2" size="24" maxlength="35" class="form-1 required" value="" />
        </td>
      </tr>
      <tr>
        <td height="4" colspan="3"></td>
      </tr>
      <tr>
        <td><img src="/p/register/xf/img/img-title-04.jpg" width="89" height="25" /></td>
        <td width="78" style="background:url(/p/register/xf/img/img-title-bg2.png) no-repeat;">
        	<input type="text" name="captcha" size="5" maxlength="4" minlength="4" class="form-1 required" value="" />	        
		</td>
        <td width="117"><div class="verification" style="float:left;"><div id="captcha_area"></div></div></td>
      </tr>
      <tr>
        <td height="6" colspan="3"></td>
      </tr>
      <tr>
        <td height="52" colspan="3"><a href="javascript:;" onclick="$('#register_form').submit(); "><img src="/p/register/xf/img/img-icon.jpg" width="209" height="49" border="0" /></a></td>
      </tr>
      <tr>
        <td colspan="3"><table border="0" cellspacing="1" cellpadding="2">
          <tr>
          	<td>
<?php 
	$items = get_channel_items("xf", "../");
	foreach($items as $channel => $name):
?>
	<a href="http://www.longeplay.com.tw/gate/login/xf?channel=<?=$channel?>&ad=<?=$ad?>"><img src="http://www.longeplay.com.tw/img/login/<?=$channel?>.png" width="29" title="<?=$name?> 帳號登入"/></a>
<? endforeach;?>          	
          	</td>                 

          </tr>
          <tr>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
          </tr>
        </table></td>
      </tr>
    </table></td>
  </tr>
</table>
<map name="MapMap" id="MapMap">
  <area shape="rect" coords="138,42,406,168" href="http://xf.longeplay.com.tw/index.php" />
</map>
</body>
</html>
