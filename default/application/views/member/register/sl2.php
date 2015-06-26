<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>《新小李飛刀》龍邑遊戲</title>
<link href="/p/register/sl2/css/index.css" rel="stylesheet" type="text/css" />
<link href="/p/register/sl2/css/site.css" rel="stylesheet" type="text/css" />

<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.8.2/jquery.min.js"></script>
<script type="text/javascript" src="/p/js/jquery.validate.min.js"></script>
<script type="text/javascript" src="/p/js/jquery.form.js"></script>
<script type="text/javascript" src="/p/register/default.js"></script>
<script type="text/javascript" src="/p/register/sl2/js/swfobject.js"></script>

<script type="text/javascript">
function MM_swapImgRestore() { //v3.0
  var i,x,a=document.MM_sr; for(i=0;a&&i<a.length&&(x=a[i])&&x.oSrc;i++) x.src=x.oSrc;
}
function MM_preloadImages() { //v3.0
  var d=document; if(d.images){ if(!d.MM_p) d.MM_p=new Array();
    var i,j=d.MM_p.length,a=MM_preloadImages.arguments; for(i=0; i<a.length; i++)
    if (a[i].indexOf("#")!=0){ d.MM_p[j]=new Image; d.MM_p[j++].src=a[i];}}
}

function MM_findObj(n, d) { //v4.01
  var p,i,x;  if(!d) d=document; if((p=n.indexOf("?"))>0&&parent.frames.length) {
    d=parent.frames[n.substring(p+1)].document; n=n.substring(0,p);}
  if(!(x=d[n])&&d.all) x=d.all[n]; for (i=0;!x&&i<d.forms.length;i++) x=d.forms[i][n];
  for(i=0;!x&&d.layers&&i<d.layers.length;i++) x=MM_findObj(n,d.layers[i].document);
  if(!x && d.getElementById) x=d.getElementById(n); return x;
}

function MM_swapImage() { //v3.0
  var i,j=0,x,a=MM_swapImage.arguments; document.MM_sr=new Array; for(i=0;i<(a.length-2);i+=3)
   if ((x=MM_findObj(a[i]))!=null){document.MM_sr[j++]=x; if(!x.oSrc) x.oSrc=x.src; x.src=a[i+2];}
}
</script>

<script>	
var flashvars = {},
params = {wmode:"transparent"},
attributes = {};
swfobject.embedSWF("/p/register/sl2/swf/index.swf", "flashCon", "1000", "660", "9.0.0","/p/register/sl2/swf/swf/expressInstall.swf", flashvars, params, attributes);	

	function showForm(){
	  $("#form").fadeIn(500);
	}
	
	function hideForm(){
		$("#form").fadeOut(500);
	}


</script>

<? if ($ad == 'google'):?>
   				
<!-- Google Code for &#20877;&#34892;&#37559;&#27161;&#35352; -->
<!-- Remarketing tags may not be associated with personally identifiable information or placed on pages related to sensitive categories. For instructions on adding this tag and more information on the above requirements, read the setup guide: google.com/ads/remarketingsetup -->
<script type="text/javascript">
/* <![CDATA[ */
var google_conversion_id = 989426073;
var google_conversion_label = "ROPUCP-rkQQQmePl1wM";
var google_custom_params = window.google_tag_params;
var google_remarketing_only = true;
/* ]]> */
</script>
<script type="text/javascript" src="//www.googleadservices.com/pagead/conversion.js">
</script>
<noscript>
<div style="display:inline;">
<img height="1" width="1" style="border-style:none;" alt="" src="//googleads.g.doubleclick.net/pagead/viewthroughconversion/989426073/?value=0&amp;label=ROPUCP-rkQQQmePl1wM&amp;guid=ON&amp;script=0"/>
</div>
</noscript>			

<? endif;?>


</head>

<body>
<div id="main">
	<div id="form">
	<form id="register_form" class="register-form" method="post" action="<?=site_url("gate/register_json/sl2")?>">
		<input type="hidden" value="<?=$redirect_url?>" id="redirect_url">
		
    <table border="0" align="center" cellpadding="0" cellspacing="0">
  <tr>
    <td colspan="3"><img src="/p/register/sl2/img/registration-img-01.png" width="575" height="116" /></td>
  </tr>
  <tr>
    <td><img src="/p/register/sl2/img/registration-img-02.png" width="82" height="345" /></td>
    <td width="412" align="center" valign="middle" background="/p/register/sl2/img/registration-img-03.jpg"><table width="312" border="0" cellpadding="1" cellspacing="0">
      <tr>
        <td width="205" align="left"><img src="/p/register/sl2/img/registration-title.png" width="180" height="50" /></td>
        <td width="100" align="left" valign="bottom"><a href="#" onclick="hideForm();"><img src="/p/register/sl2/img/registration-x.png" width="69" height="33" border="0" /></a></td>
      </tr>
      <tr>
        <td colspan="2" align="center"><table width="250" border="0" cellpadding="0" cellspacing="0">
          <tr>
            <td width="90"><img src="/p/register/sl2/img/registration-img-06.jpg" /></td>
            <td width="160" align="left" style="background:url(/p/register/sl2/img/registration-img-11.gif) no-repeat;">
            	<input type="text" name="account" size="20" maxlength="35" class="form-2 required" minlength="6" value="" />
            </td>
          </tr>
          <tr>
            <td width="90" height="5"></td>
            <td height="5"></td>
          </tr>
          <tr>
            <td><img src="/p/register/sl2/img/registration-img-07.jpg" height="27" /></td>
            <td align="left" style="background:url(/p/register/sl2/img/registration-img-11.gif) no-repeat;">
            	<input type="password" name="pwd" size="20" maxlength="35" class="form-2 required" value="" />
            </td>
          </tr>
            <td width="90" height="5"></td>
            <td height="5"></td>
          <tr>
            <td><img src="/p/register/sl2/img/registration-img-08.jpg" height="27" /></td>
            <td align="left" style="background:url(/p/register/sl2/img/registration-img-11.gif) no-repeat;">
            	<input type="password" name="pwd2" size="20" maxlength="35" class="form-2 required" value="" />
            </td>
          </tr>
		  <tr>
            <td width="90" height="5"></td>
            <td height="5"></td>
          <tr>
            <td><img src="/p/register/sl2/img/registration-img-10.jpg" height="27" /></td>
            <td align="left" style="background:url(/p/register/sl2/img/registration-img-12.gif) no-repeat;">
            	<input type="text" name="captcha" size="5" maxlength="4" minlength="4" class="form-2 required" value="" />
            	<span style="float:right; padding-right:20px;">
            	<div class="verification" style="float:left;"><div id="captcha_area"></div></div>
            	</span>
            </td>
          </tr>
        </table></td>
      </tr>
      <tr>
      	<td colspan="2" style="padding:5px 0; ">
        <table width="280" border="0" cellpadding="0" cellspacing="0" align="center">
        	<tr>
            	<td>
<?php 
	$items = get_channel_items("sl2", "../");
	foreach($items as $channel => $name):
?>
	<a href="http://www.longeplay.com.tw/gate/login/sl2?channel=<?=$channel?>&ad=<?=$ad?>"><img src="http://www.longeplay.com.tw/img/login/<?=$channel?>.png" width="34" title="<?=$name?> 帳號登入"/></a>
<? endforeach;?>   
                </td>
            </tr>
        </table>
        </td>
      </tr>
      <tr>
        <td height="50" colspan="2" align="center">
        	<a href="javascript:;" onclick="$('#register_form').submit();" onmouseout="MM_swapImgRestore()" onmouseover="MM_swapImage('registration-icon','','/p/register/sl2/img/registration-icon-on.png',1)">        	
        		<img src="/p/register/sl2/img/registration-icon-off.png" name="registration-icon" width="207" height="47" border="0" id="registration-icon" /></a></td>
      </tr>
    </table></td>
    <td><img src="/p/register/sl2/img/registration-img-04.png" width="81" height="345" /></td>
  </tr>
  <tr>
    <td colspan="3"><img src="/p/register/sl2/img/registration-img-05.png" width="575" height="116" /></td>
  </tr>
</table>
    
    </form>
  </div>
  	<div id="flashCon">
    <p><a href="http://www.adobe.com/go/getflashplayer"><img src="http://www.adobe.com/images/shared/download_buttons/get_flash_player.gif" alt="Get Adobe Flash player" /></a></p>
  </div>
</div>
</body>
</html>



