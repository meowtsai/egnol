<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>:::龍邑 Web Game:::仙落紅塵</title>
<link href="/p/register/xl/css/page.css" rel="stylesheet" type="text/css" />

<script type="text/javascript" src="/p/register/xl/js/jquery-1.8.3.min.js"></script>
<script type="text/javascript" src="/p/js/jquery.validate.min.js"></script>
<script type="text/javascript" src="/p/js/jquery.form.js"></script>
<script type="text/javascript" src="/p/register/default.js"></script>
<script type="text/javascript" src="/p/register/xl/js/swfobject.js"></script>

<script>	
var flashvars = {},
params = {wmode:"transparent"},
attributes = {};
swfobject.embedSWF("/p/register/xl/swf/index.swf", "flashCon", "1000", "630", "9.0.0","/p/register/xl/swf/expressInstall.swf", flashvars, params, attributes);	

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
<div id="main">
	<div id="form" style="display:none; position:absolute; margin:27px 0px 0px 158px;">
	<form id="register_form" class="register-form" method="post" action="<?=site_url("gate/register_json/xl")?>">
		<input type="hidden" value="<?=$redirect_url?>" id="redirect_url">
		
		

<table border="0" align="center" cellpadding="0" cellspacing="0">
  <tr>
    <td rowspan="2" align="center"><img src="/p/register/xl/img/img-01.png" width="70" height="412" /></td>
    <td align="center"><img src="/p/register/xl/img/img-02.png" width="353" height="161" /></td>
    <td rowspan="2" align="center"><img src="/p/register/xl/img/img-04.png" width="205" height="412" border="0" usemap="#Map" /></td>
  </tr>
  <tr>
    <td height="251" align="left" valign="top" style="background:url(/p/register/xl/img/img-03.png) no-repeat;"><table width="283" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td width="89"><img src="/p/register/xl/img/registration-img-05.jpg" width="89" height="25" /></td>
        <td colspan="2" style="background:url(/p/register/xl/img/registration-img-10.png) no-repeat;">
          <input type="text" name="account" size="20" maxlength="35" class="form-1 required" minlength="6" value="" />
        </td>
      </tr>
      <tr>
        <td height="5" colspan="3"></td>
      </tr>
      <tr>
        <td><img src="/p/register/xl/img/registration-img-06.jpg" width="89" height="25" /></td>
        <td colspan="2" style="background:url(/p/register/xl/img/registration-img-10.png) no-repeat;">
          <input type="password" name="pwd" size="20" maxlength="35" class="form-1 required" value="" />
        </td>
      </tr>
      <tr>
        <td height="5" colspan="3"></td>
      </tr>
      <tr>
        <td><img src="/p/register/xl/img/registration-img-07.jpg" width="89" height="25" /></td>
        <td colspan="2" style="background:url(/p/register/xl/img/registration-img-10.png) no-repeat;">
          <input type="password" name="pwd2" size="20" maxlength="35" class="form-1 required" value="" />
        </td>
      </tr>
      <tr>
        <td height="5" colspan="3"></td>
      </tr>
      <tr>
        <td><img src="/p/register/xl/img/registration-img-09.jpg" width="89" height="25" /></td>
        <td width="98" align="left" style="background:url(/p/register/xl/img/registration-img-11.png) no-repeat;">
                <input type="text" name="captcha" size="5" maxlength="4" minlength="4" class="form-1 required" value="" />
            	
        </td>
        <td width="96">
            	<div class="verification" style="float:left;"><div id="captcha_area"></div></div>
        </td>
      </tr>
       <tr>
         <td colspan="3"><table border="0" cellspacing="0" cellpadding="0">
           <tr>
             <td height="5"></td>
             </tr>
           <tr>
             <td><table border="0" cellspacing="2" cellpadding="2">
               <tr>
                 <td>
<?php 
	$items = get_channel_items("xl", "../");
	foreach($items as $channel => $name):
?>
	<a href="http://www.long_e.com.tw/gate/login/xl?channel=<?=$channel?>&ad=<?=$ad?>"><img src="http://www.long_e.com.tw/img/login/<?=$channel?>.png" width="34" title="<?=$name?> 帳號登入"/></a>
<? endforeach;?>                  
                 </td>
                 </tr>
               </table></td>
             </tr>
           <tr>
             <td><a href="javascript:;" onclick="$('#register_form').submit();"><img src="/p/register/xl/img/icon.png" width="161" height="38" border="0" /></a></td>
             </tr>
         </table></td>
       </tr>
    </table></td>
  </tr>
</table>

<map name="Map" id="Map">
  <area shape="rect" coords="37,101,124,156" href="javascript:hideForm();" />
</map>    
    
    
    </form>
  </div>
  	<div id="flashCon">
    <p><a href="http://www.adobe.com/go/getflashplayer"><img src="http://www.adobe.com/images/shared/download_buttons/get_flash_player.gif" alt="Get Adobe Flash player" /></a></p>
  </div>
</div>
</body>
</html>



