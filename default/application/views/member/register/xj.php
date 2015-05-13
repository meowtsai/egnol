<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>《仙境幻想》龍邑遊戲</title>

<script type="text/javascript" src="/p/js/jquery-1.7.2.min.js"></script>
<script type="text/javascript" src="/p/js/jquery.validate.min.js"></script>
<script type="text/javascript" src="/p/js/jquery.form.js"></script>
<script type="text/javascript" src="/p/register/default.js"></script>
<script type="text/javascript" src="/p/register/swfobject.js"></script>
<script type="text/javascript" src="/p/register/swfobject_modified.js"></script>

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

function showForm(){
  $("#form").fadeIn(500);
}

function hideForm(){
	$("#form").fadeOut(500);
}

$(function(){
	$('.btn-close').click(hideForm);
})
</script>

<? if (ENVIRONMENT == "production") $this->load->view("templates/analyticstracking")?>


<? if ($this->input->get('v')=='3'):?>

<style type="text/css">
body{margin:0px; background-color:#000;}
#wrap{height: 750px;width: 100%;position: absolute;visibility: visible;z-index: 1;left: 0px;op: 0px; overflow: hidden;}
#main{position: relative;left: 50%;margin-left: -1000px;width:2000px;height:100%;}
#regist{width:811px; height:504px; background-image:url(/p/register/xj/img/regist_bk.png); background-position:center top; background-repeat:no-repeat;}
#content{width:550px; height:375px; position:relative; top:50px; left:150px;}
#close{ width:96px; height:35px; position:absolute; top:5px; left:280px; z-index:50; }
.input{width:200px; height:35px; font-family:Verdana, Geneva, sans-serif "微軟正黑體"; font-size:24px; color:#000;}
.input2{width:120px; height:35px; font-family:Verdana, Geneva, sans-serif "微軟正黑體"; font-size:24px; color:#000;}
#form {display:none; position:absolute; z-index:999; top:60px;}
</style>

<body>

<div id="wrap">
  <div id="main">
    <object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" width="2000" height="750" align="absmiddle" id="FlashID">
	  <param name="movie" value="/p/register/xj/swf/index4.swf" />
	  <param name="quality" value="high" />
	  <param name="wmode" value="transparent" />
	  <param name="swfversion" value="6.0.65.0" />
	  <!-- 此 param 標籤會提示使用 Flash Player 6.0 r65 和更新版本的使用者下載最新版本的 Flash Player。如果您不想讓使用者看到這項提示，請將其刪除。 -->
	  <param name="expressinstall" value="/p/register/xj/swf/expressInstall.swf" />
	  <!-- 下一個物件標籤僅供非 IE 瀏覽器使用。因此，請使用 IECC 將其自 IE 隱藏。 -->
	  <!--[if !IE]>-->
	  <object data="/p/register/xj/swf/index4.swf" type="application/x-shockwave-flash" width="2000" height="750" align="absmiddle">
	    <!--<![endif]-->
	    <param name="quality" value="high" />
	    <param name="wmode" value="transparent" />
	    <param name="swfversion" value="6.0.65.0" />
	    <param name="expressinstall" value="/p/register/xj/swf/expressInstall.swf" />
	    <!-- 瀏覽器會為使用 Flash Player 6.0 和更早版本的使用者顯示下列替代內容。 -->
	    <div>
	      <h4>這個頁面上的內容需要較新版本的 Adobe Flash Player。</h4>
	      <p><a href="http://www.adobe.com/go/getflashplayer"><img src="http://www.adobe.com/images/shared/download_buttons/get_flash_player.gif" alt="取得 Adobe Flash Player" width="112" height="33" /></a></p>
        </div>
	    <!--[if !IE]>-->
    </object>
	  <!--<![endif]-->
  </object>
</div>
</div>
<script type="text/javascript">
swfobject.registerObject("FlashID");
</script>
<div style="width:800px; margin:auto;">
<div id="form">
	<form id="register_form" class="register-form" method="post" action="<?=site_url("gate/register_json/xj")?>">
		<input type="hidden" value="<?=$redirect_url?>" id="redirect_url">

<div id="regist">
	<div id="content">
   	  <table width="450" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td width="130" height="50">&nbsp;</td>
    <td width="397" height="50" valign="bottom">
    	<input type="text" name="account" size="20" maxlength="35" class="input required" minlength="6" value="" />
    </td>
    </tr>
  <tr>
    <td height="20">&nbsp;</td>
    <td height="20">&nbsp;</td>
    </tr>
  <tr>
    <td height="40">&nbsp;</td>
    <td height="40"><input type="password" name="pwd" size="20" maxlength="35" class="input required" value="" autocomplete="off" /></td>
    </tr>
  <tr>
    <td height="30">&nbsp;</td>
    <td height="30">&nbsp;</td>
    </tr>
  <tr>
    <td height="35">&nbsp;</td>
    <td height="35"><input type="password" name="pwd2" size="20" maxlength="35" class="input required" value="" autocomplete="off" /></td>
    </tr>
  <tr>
    <td height="20">&nbsp;</td>
    <td height="20">&nbsp;</td>
    </tr>
  <tr>
    <td height="35">&nbsp;</td>
    <td height="35"><table width="270" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td width="120" height="30"><input type="text" name="captcha" size="5" maxlength="4" minlength="4" class="input2 required" value="" /></td>
        <td width="150" height="30"><em class="verification" style="display:inline-block;" ><div id="captcha_area"></div></em></td>
      </tr>
    </table></td>
    </tr>
  <tr>
    <td height="20">&nbsp;</td>
    <td height="20">&nbsp;</td>
    </tr>
  <tr>
    <td height="45" colspan="2" align="center" valign="middle">
<?php 
	$items = get_channel_items("xj", "../");
	foreach($items as $channel => $name):
		if ( ! in_array($channel, array("facebook", "yoid", "rc", "2000fun", "google"))) continue;
?>
	<a href="http://www.long_e.com.tw/gate/login/xj?channel=<?=$channel?>&ad=<?=$ad?>"><img src="http://www.long_e.com.tw/img/login/<?=$channel?>.png" width="40" title="<?=$name?> 帳號登入"/></a>
<? endforeach;?>     
    </td>
    </tr>
  <tr>
    <td height="70" colspan="2">
    	<a href="javascript:"  onclick="$('#register_form').submit();">
    		<img src="p/register/xj/img/start_1.png" name="start" width="197" height="54" hspace="165" border="0" id="start" onmouseover="MM_swapImage('start','','/p/register/xj/img/start_2.png',1)" onmouseout="MM_swapImgRestore()" />
    	</a>
    </td>
   </tr>
</table>

  </div>
</div>

</form>
</div>
</div>

<!-- Google Code for conversion Conversion Page -->
<script type="text/javascript">
/* <![CDATA[ */
var google_conversion_id = 980108397;
var google_conversion_language = "en";
var google_conversion_format = "2";
var google_conversion_color = "ffffff";
var google_conversion_label = "0Su_CNvumwcQ7Yit0wM";
var google_conversion_value = 0;
var google_remarketing_only = false;
/* ]]> */
</script>
<script type="text/javascript" src="//www.googleadservices.com/pagead/conversion.js">
</script>
<noscript>
<div style="display:inline;">
<img height="1" width="1" style="border-style:none;" alt="" src="//www.googleadservices.com/pagead/conversion/980108397/?value=0&amp;label=0Su_CNvumwcQ7Yit0wM&amp;guid=ON&amp;script=0"/>
</div>
</noscript>

</body>

<? elseif ($this->input->get('v')=='4'):?>

<style type="text/css">
body{margin:0px; background-color:#000;}
#wrap{height: 750px;width: 100%;position: absolute;visibility: visible;z-index: 1;left: 0px;op: 0px; overflow: hidden;}
#main{position: relative;left: 50%;margin-left: -1000px;width:2000px;height:100%;}
#new_add{width:100%; height:40px; position:absolute; top:645px; z-index:30;}
#regist{width:811px; height:504px; background-image:url(/p/register/xj/img/v3/regist_bk.png); background-position:center top; background-repeat:no-repeat;}
#content{width:550px; height:375px; position:relative; top:50px; left:150px;}
#close{ width:96px; height:35px; position:absolute; top:5px; left:280px; z-index:50; }
.input{width:200px; height:35px; font-family:Verdana, Geneva, sans-serif "微軟正黑體"; font-size:24px; color:#000;}
.input2{width:120px; height:35px; font-family:Verdana, Geneva, sans-serif "微軟正黑體"; font-size:24px; color:#000;}
#form {display:none; position:absolute; z-index:999; top:60px;}
</style>

<body>

<div id="wrap">
  <div id="main">
  	<div id="new_add"><table width="365" border="0" cellspacing="0" cellpadding="0" align="center" bgcolor="#000">
      <tr>
        <td width="295" height="40">
<?php 
	$items = get_channel_items("xj", "../");
	foreach($items as $channel => $name):
		if ( ! in_array($channel, array("facebook", "yoid", "rc", "2000fun", "google"))) continue;
?>
	<a href="http://www.long_e.com.tw/gate/login/xj?channel=<?=$channel?>&ad=<?=$ad?>"><img src="http://www.long_e.com.tw/img/login/<?=$channel?>.png" width="40" title="<?=$name?> 帳號登入"/></a>
<? endforeach;?> 
        </td>
        <td width="70">
        	<a href="javascript:;" onclick="showForm();">
        	<img src="/p/register/xj/img/v3/fast.png" name="fast" width="70" height="26" border="0" id="fast" onmouseover="MM_swapImage('fast','','/p/register/xj/img/v3/fast2.png',1)" onmouseout="MM_swapImgRestore()" />
        	</a>
        </td>
      </tr>
    </table>
    </div>  
    <object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" width="2000" height="750" align="absmiddle" id="FlashID">
	  <param name="movie" value="/p/register/xj/swf/index3.swf" />
	  <param name="quality" value="high" />
	  <param name="wmode" value="transparent" />
	  <param name="swfversion" value="6.0.65.0" />
	  <!-- 此 param 標籤會提示使用 Flash Player 6.0 r65 和更新版本的使用者下載最新版本的 Flash Player。如果您不想讓使用者看到這項提示，請將其刪除。 -->
	  <param name="expressinstall" value="/p/register/xj/swf/expressInstall.swf" />
	  <!-- 下一個物件標籤僅供非 IE 瀏覽器使用。因此，請使用 IECC 將其自 IE 隱藏。 -->
	  <!--[if !IE]>-->
	  <object data="/p/register/xj/swf/index3.swf" type="application/x-shockwave-flash" width="2000" height="750" align="absmiddle">
	    <!--<![endif]-->
	    <param name="quality" value="high" />
	    <param name="wmode" value="transparent" />
	    <param name="swfversion" value="6.0.65.0" />
	    <param name="expressinstall" value="/p/register/xj/swf/expressInstall.swf" />
	    <!-- 瀏覽器會為使用 Flash Player 6.0 和更早版本的使用者顯示下列替代內容。 -->
	    <div>
	      <h4>這個頁面上的內容需要較新版本的 Adobe Flash Player。</h4>
	      <p><a href="http://www.adobe.com/go/getflashplayer"><img src="http://www.adobe.com/images/shared/download_buttons/get_flash_player.gif" alt="取得 Adobe Flash Player" width="112" height="33" /></a></p>
        </div>
	    <!--[if !IE]>-->
    </object>
	  <!--<![endif]-->
  </object>
</div>
</div>
<script type="text/javascript">
swfobject.registerObject("FlashID");
</script>

<div style="width:800px; margin:auto;">
<div id="form">
	<form id="register_form" class="register-form" method="post" action="<?=site_url("gate/register_json/xj")?>">
		<input type="hidden" value="<?=$redirect_url?>" id="redirect_url">

<div id="regist">
	<div id="content">
   	  <table width="450" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td width="130" height="50">&nbsp;</td>
    <td width="397" height="50" valign="bottom">
    	<input type="text" name="account" size="20" maxlength="35" class="input required" minlength="6" value="" />
    </td>
    </tr>
  <tr>
    <td height="20">&nbsp;</td>
    <td height="20">&nbsp;</td>
    </tr>
  <tr>
    <td height="40">&nbsp;</td>
    <td height="40"><input type="password" name="pwd" size="20" maxlength="35" class="input required" value="" autocomplete="off" /></td>
    </tr>
  <tr>
    <td height="30">&nbsp;</td>
    <td height="30">&nbsp;</td>
    </tr>
  <tr>
    <td height="35">&nbsp;</td>
    <td height="35"><input type="password" name="pwd2" size="20" maxlength="35" class="input required" value="" autocomplete="off" /></td>
    </tr>
  <tr>
    <td height="20">&nbsp;</td>
    <td height="20">&nbsp;</td>
    </tr>
  <tr>
    <td height="35">&nbsp;</td>
    <td height="35"><table width="270" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td width="120" height="30"><input type="text" name="captcha" size="5" maxlength="4" minlength="4" class="input2 required" value="" /></td>
        <td width="150" height="30"><em class="verification" style="display:inline-block;" ><div id="captcha_area"></div></em></td>
      </tr>
    </table></td>
    </tr>
  <tr>
    <td height="20">&nbsp;</td>
    <td height="20">&nbsp;</td>
    </tr>
  <tr>
    <td height="70" colspan="2">
    	<a href="javascript:"  onclick="$('#register_form').submit();">
    		<img src="p/register/xj/img/start_1.png" name="start" width="197" height="54" hspace="165" border="0" id="start" onmouseover="MM_swapImage('start','','/p/register/xj/img/start_2.png',1)" onmouseout="MM_swapImgRestore()" />
    	</a>
    </td>
   </tr>
</table>

  </div>
</div>

</form>
</div>
</div>

<script type="text/javascript">
/* <![CDATA[ */
var google_conversion_id = 978906751;
var google_custom_params = window.google_tag_params;
var google_remarketing_only = true;
/* ]]> */
</script>
<script type="text/javascript" src="//www.googleadservices.com/pagead/conversion.js">
</script>
<noscript>
<div style="display:inline;">
<img height="1" width="1" style="border-style:none;" alt="" src="//googleads.g.doubleclick.net/pagead/viewthroughconversion/978906751/?value=0&amp;guid=ON&amp;script=0"/>
</div>
</noscript>


</body>

<? elseif ($this->input->get('v')=='2'):?>

<style type="text/css">
body{margin:0px; background-color:#000;}
#wrap{height: 750px;width: 100%;position: absolute;visibility: visible;z-index: 1;left: 0px;op: 0px; overflow: hidden;}
#main{position: relative;left: 50%;margin-left: -1000px;width:2000px;height:100%;}
#regist{width:811px; height:504px; background-image:url(/p/register/xj/img/regist_bk.png); background-position:center top; background-repeat:no-repeat;}
#content{width:550px; height:375px; position:relative; top:50px; left:150px;}
#close{ width:96px; height:35px; position:absolute; top:5px; left:280px; z-index:50; }
.input{width:200px; height:35px; font-family:Verdana, Geneva, sans-serif "微軟正黑體"; font-size:24px; color:#000;}
.input2{width:120px; height:35px; font-family:Verdana, Geneva, sans-serif "微軟正黑體"; font-size:24px; color:#000;}
#form {display:none; position:absolute; z-index:999; top:60px;}
</style>

<body>

<div id="wrap">
  <div id="main">
    <object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" width="2000" height="750" align="absmiddle" id="FlashID">
	  <param name="movie" value="/p/register/xj/swf/index2.swf" />
	  <param name="quality" value="high" />
	  <param name="wmode" value="transparent" />
	  <param name="swfversion" value="6.0.65.0" />
	  <!-- 此 param 標籤會提示使用 Flash Player 6.0 r65 和更新版本的使用者下載最新版本的 Flash Player。如果您不想讓使用者看到這項提示，請將其刪除。 -->
	  <param name="expressinstall" value="/p/register/xj/swf/expressInstall.swf" />
	  <!-- 下一個物件標籤僅供非 IE 瀏覽器使用。因此，請使用 IECC 將其自 IE 隱藏。 -->
	  <!--[if !IE]>-->
	  <object data="/p/register/xj/swf/index2.swf" type="application/x-shockwave-flash" width="2000" height="750" align="absmiddle">
	    <!--<![endif]-->
	    <param name="quality" value="high" />
	    <param name="wmode" value="transparent" />
	    <param name="swfversion" value="6.0.65.0" />
	    <param name="expressinstall" value="/p/register/xj/swf/expressInstall.swf" />
	    <!-- 瀏覽器會為使用 Flash Player 6.0 和更早版本的使用者顯示下列替代內容。 -->
	    <div>
	      <h4>這個頁面上的內容需要較新版本的 Adobe Flash Player。</h4>
	      <p><a href="http://www.adobe.com/go/getflashplayer"><img src="http://www.adobe.com/images/shared/download_buttons/get_flash_player.gif" alt="取得 Adobe Flash Player" width="112" height="33" /></a></p>
        </div>
	    <!--[if !IE]>-->
    </object>
	  <!--<![endif]-->
  </object>
</div>
</div>
<script type="text/javascript">
swfobject.registerObject("FlashID");
</script>

<div style="width:800px; margin:auto;">
<div id="form">
	<form id="register_form" class="register-form" method="post" action="<?=site_url("gate/register_json/xj")?>">
		<input type="hidden" value="<?=$redirect_url?>" id="redirect_url">

<div id="regist">
	<div id="close" class="btn-close"><img src="/p/register/xj/img/close_1.png" name="close" width="96" height="35" border="0" id="close" onmouseover="MM_swapImage('close','','/p/register/xj/img/close_2.png',1)" onmouseout="MM_swapImgRestore()" /></div>
	<div id="content">
   	  <table width="450" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td width="130" height="50">&nbsp;</td>
    <td width="397" height="50" valign="bottom">
    	<input type="text" name="account" size="20" maxlength="35" class="input required" minlength="6" value="" />
    </td>
    </tr>
  <tr>
    <td height="20">&nbsp;</td>
    <td height="20">&nbsp;</td>
    </tr>
  <tr>
    <td height="40">&nbsp;</td>
    <td height="40"><input type="password" name="pwd" size="20" maxlength="35" class="input required" value="" autocomplete="off" /></td>
    </tr>
  <tr>
    <td height="30">&nbsp;</td>
    <td height="30">&nbsp;</td>
    </tr>
  <tr>
    <td height="35">&nbsp;</td>
    <td height="35"><input type="password" name="pwd2" size="20" maxlength="35" class="input required" value="" autocomplete="off" /></td>
    </tr>
  <tr>
    <td height="20">&nbsp;</td>
    <td height="20">&nbsp;</td>
    </tr>
  <tr>
    <td height="35">&nbsp;</td>
    <td height="35"><table width="270" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td width="120" height="30"><input type="text" name="captcha" size="5" maxlength="4" minlength="4" class="input2 required" value="" /></td>
        <td width="150" height="30"><em class="verification" style="display:inline-block;" ><div id="captcha_area"></div></em></td>
      </tr>
    </table></td>
    </tr>
  <tr>
    <td height="20">&nbsp;</td>
    <td height="20">&nbsp;</td>
    </tr>
  <tr>
    <td height="45" colspan="2" align="center" valign="middle">
<?php 
	$items = get_channel_items("xj", "../");
	foreach($items as $channel => $name):
?>
	<a href="http://www.long_e.com.tw/gate/login/xj?channel=<?=$channel?>&ad=<?=$ad?>"><img src="http://www.long_e.com.tw/img/login/<?=$channel?>.png" width="40" title="<?=$name?> 帳號登入"/></a>
<? endforeach;?>     
    </td>
    </tr>
  <tr>
    <td height="70" colspan="2">
    	<a href="javascript:"  onclick="$('#register_form').submit();">
    		<img src="p/register/xj/img/start_1.png" name="start" width="197" height="54" hspace="165" border="0" id="start" onmouseover="MM_swapImage('start','','/p/register/xj/img/start_2.png',1)" onmouseout="MM_swapImgRestore()" />
    	</a>
    </td>
   </tr>
</table>

  </div>
</div>

</form>
</div>
</div>

<script type="text/javascript">
/* <![CDATA[ */
var google_conversion_id = 978906751;
var google_custom_params = window.google_tag_params;
var google_remarketing_only = true;
/* ]]> */
</script>
<script type="text/javascript" src="//www.googleadservices.com/pagead/conversion.js">
</script>
<noscript>
<div style="display:inline;">
<img height="1" width="1" style="border-style:none;" alt="" src="//googleads.g.doubleclick.net/pagead/viewthroughconversion/978906751/?value=0&amp;guid=ON&amp;script=0"/>
</div>
</noscript>


</body>

<? else:?>

<link href="/p/register/xj/css/index.css" rel="stylesheet" type="text/css" />
<link href="/p/register/xj/css/site.css" rel="stylesheet" type="text/css" />

<script>	
var flashvars = {},
params = {wmode:"transparent"},
attributes = {};
swfobject.embedSWF("/p/register/xj/swf/index.swf", "flashCon", "1000", "720", "9.0.0","/p/register/xj/swf/expressInstall.swf", flashvars, params, attributes);	



</script>

<? if ($ad == 'google'):?>
   				
<!-- Google Code for &#20877;&#34892;&#37559;&#27161;&#35352; -->
<!-- Remarketing tags may not be associated with personally identifiable information or placed on pages related to sensitive categories. For instructions on adding this tag and more information on the above requirements, read the setup guide: google.com/ads/remarketingsetup -->
<script type="text/javascript">
/* <![CDATA[ */
var google_conversion_id = 996112673;
var google_conversion_label = "AotWCL_b4QQQofL92gM";
var google_custom_params = window.google_tag_params;
var google_remarketing_only = true;
/* ]]> */
</script>
<script type="text/javascript" src="//www.googleadservices.com/pagead/conversion.js">
</script>
<noscript>
<div style="display:inline;">
<img height="1" width="1" style="border-style:none;" alt="" src="//googleads.g.doubleclick.net/pagead/viewthroughconversion/996112673/?value=0&amp;label=AotWCL_b4QQQofL92gM&amp;guid=ON&amp;script=0"/>
</div>
</noscript>

<? endif;?>

</head>

<body>
<div id="main">
	<div id="form">
	<form id="register_form" class="register-form" method="post" action="<?=site_url("gate/register_json/xj")?>">
		<input type="hidden" value="<?=$redirect_url?>" id="redirect_url">
		
        <a class="btn-close" href="#">關閉</a>
    	<div class="rows">
       		<span>龍邑帳號</span>
       		<input type="text" name="account" size="20" maxlength="35" class="form-2 required" minlength="6" value="" />
        </div>
        <div class="rows">
       		<span>龍邑密碼</span>
       		<input type="password" name="pwd" size="20" maxlength="35" class="form-2 required" value="" autocomplete="off" />
        </div>
        <div class="rows">
       		<span>確認密碼</span>
       		<input type="password" name="pwd2" size="20" maxlength="35" class="form-2 required" value="" autocomplete="off" />
        </div>
        <div class="rows">
       		<span>驗證碼</span>
       		<input type="text" name="captcha" size="5" maxlength="4" minlength="4" class="form-2 required" value="" />
       		<em class="verification" style="display:inline-block;" ><div id="captcha_area"></div></em> 
        </div>
        <div class="social-list">
<?php 
	$items = get_channel_items("xj", "../");
	foreach($items as $channel => $name):
?>
	<a href="http://www.long_e.com.tw/gate/login/xj?channel=<?=$channel?>&ad=<?=$ad?>"><img src="http://www.long_e.com.tw/img/login/<?=$channel?>.png" width="40" title="<?=$name?> 帳號登入"/></a>
<? endforeach;?> 
        </div>
        <a class="btn-start" href="javascript:;" onclick="$('#register_form').submit();">開始遊戲</a>
        </form>
  </div>
  	<div id="flashCon">
    <p><a href="http://www.adobe.com/go/getflashplayer"><img src="http://www.adobe.com/images/shared/download_buttons/get_flash_player.gif" alt="Get Adobe Flash player" /></a></p>
  </div>
</div>

<script type="text/javascript">
/* <![CDATA[ */
var google_conversion_id = 978906751;
var google_custom_params = window.google_tag_params;
var google_remarketing_only = true;
/* ]]> */
</script>
<script type="text/javascript" src="//www.googleadservices.com/pagead/conversion.js">
</script>
<noscript>
<div style="display:inline;">
<img height="1" width="1" style="border-style:none;" alt="" src="//googleads.g.doubleclick.net/pagead/viewthroughconversion/978906751/?value=0&amp;guid=ON&amp;script=0"/>
</div>
</noscript>


</body>

<? endif;?>


</html>



