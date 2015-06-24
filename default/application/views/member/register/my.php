
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>龍邑遊戲-萌英雄</title>
<style>
Body{margin:0px; background-color:#000; font-family:微軟正黑體;}
#wrap{width:100%; height:800px; background-image:url(/p/register/my/bk.jpg); background-position:center top; background-repeat:no-repeat;}
#main{width:1000px; height:800px; margin:0 auto;}
#logo{width:308px; height:142px; position:absolute; margin-top:145px; margin-left:55px; z-index:60;}
#register{width:382px; height:397px; position:absolute; margin-top:300px; margin-left:60px; z-index:50; background-image:url(/p/register/my/rigester.png); background-position:center top; background-repeat:no-repeat;}
#character{width:750px; height:620px; position:absolute; margin-top:70px; margin-left:250px; z-index:20;}
form input {height:25px; line-height:25px; font-size:16px;}
</style>
<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.8.2/jquery.min.js"></script>
<script type="text/javascript" src="/p/js/jquery.validate.min.js"></script>
<script type="text/javascript" src="/p/js/jquery.form.js"></script>
<script type="text/javascript" src="/p/register/default.js"></script>
<script type="text/javascript" src="/p/register/swfobject.js"></script>

<script type="text/javascript">
function MM_preloadImages() { //v3.0
  var d=document; if(d.images){ if(!d.MM_p) d.MM_p=new Array();
    var i,j=d.MM_p.length,a=MM_preloadImages.arguments; for(i=0; i<a.length; i++)
    if (a[i].indexOf("#")!=0){ d.MM_p[j]=new Image; d.MM_p[j++].src=a[i];}}
}
function MM_swapImgRestore() { //v3.0
  var i,x,a=document.MM_sr; for(i=0;a&&i<a.length&&(x=a[i])&&x.oSrc;i++) x.src=x.oSrc;
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


<script type="text/javascript" src="//rec.scupio.com/recweb/js/rec.js">{"mid":7381,"pid":"landing"}</script>
<script type="text/javascript"> var _bwp=460; var _bwpid='landing'; </script>
<script type="text/javascript" src="//adsense.scupio.com/conv/js/conv.js"></script>


</head>

<body>

<form id="register_form" class="register-form" method="post" action="<?=site_url("gate/register_json/xf")?>">
	<input type="hidden" value="<?=$redirect_url?>" id="redirect_url">

<div id="wrap">
	<div id="main">
   	  <div id="logo">
   	    <object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" width="385" height="200" align="absmiddle" id="FlashID">
   	      <param name="movie" value="/p/register/my/logo.swf" />
   	      <param name="quality" value="high" />
   	      <param name="wmode" value="transparent" />
   	      <param name="swfversion" value="6.0.65.0" />
   	      <!-- 此 param 標籤會提示使用 Flash Player 6.0 r65 和更新版本的使用者下載最新版本的 Flash Player。如果您不想讓使用者看到這項提示，請將其刪除。 -->
   	      <param name="expressinstall" value="Scripts/expressInstall.swf" />
   	      <!-- 下一個物件標籤僅供非 IE 瀏覽器使用。因此，請使用 IECC 將其自 IE 隱藏。 -->
   	      <!--[if !IE]>-->
   	      <object data="/p/register/my/logo.swf" type="application/x-shockwave-flash" width="385" height="200" align="absmiddle">
   	        <!--<![endif]-->
   	        <param name="quality" value="high" />
   	        <param name="wmode" value="transparent" />
   	        <param name="swfversion" value="6.0.65.0" />
   	        <param name="expressinstall" value="Scripts/expressInstall.swf" />
   	        <!-- 瀏覽器會為使用 Flash Player 6.0 和更早版本的使用者顯示下列替代內容。 -->
   	        <div>
   	          <h4>這個頁面上的內容需要較新版本的 Adobe Flash Player。</h4>
   	          <p><a href="http://www.adobe.com/go/getflashplayer"><img src="http://www.adobe.com//p/register/my/shared/download_buttons/get_flash_player.gif" alt="取得 Adobe Flash Player" width="112" height="33" /></a></p>
            </div>
   	        <!--[if !IE]>-->
          </object>
   	      <!--<![endif]-->
        </object>
   	  </div>
   	  <div id="register">
   	    <table width="375" border="0" cellspacing="0" cellpadding="0">
   	      <tr>
   	        <td width="130" height="110">&nbsp;</td>
   	        <td height="110" colspan="2">&nbsp;</td>
          </tr>
   	      <tr>
   	        <td height="35">&nbsp;</td>
   	        <td height="35" colspan="2" align="left" valign="middle">
   	        	<input type="text" name="account" size="20" maxlength="35" class="form-2 required" minlength="6" value="" />
   	        </td>
          </tr>
   	      <tr>
   	        <td height="35">&nbsp;</td>
   	        <td height="35" colspan="2" align="left" valign="middle">
   	        	<input type="password" name="pwd" size="20" maxlength="35" class="form-2 required" value="" />
   	        </td>
          </tr>
   	      <tr>
   	        <td height="35">&nbsp;</td>
   	        <td height="35" colspan="2" align="left" valign="middle">
   	        	<input type="password" name="pwd2" size="20" maxlength="35" class="form-2 required" value="" />
   	        </td>
          </tr>
   	      <tr>
   	        <td height="50">&nbsp;</td>
   	        <td width="125" height="50" align="left" valign="middle">
   	        	<input type="text" name="captcha" size="5" maxlength="4" minlength="4" class="form-2 required" value="" />
   	        </td>
   	        <td width="120" align="left" valign="middle">
   	        	<div class="verification" style="float:left;"><div id="captcha_area"></div></div>
   	        </td>
          </tr>
   	      <tr>
   	        <td height="50">&nbsp;</td>
   	        <td height="50" colspan="2">
   	        	<a href="javascript:;" onclick="$('#register_form').submit(); ">
   	        		<img src="/p/register/my/start_01.png" name="btm" width="178" height="42" border="0" id="btm" onmouseover="MM_swapImage('btm','','/p/register/my/start_02.png',1)" onmouseout="MM_swapImgRestore()" />
   	        	</a></td>
          </tr>
   	      <tr>
   	        <td height="55" colspan="3" style="padding-left:40px;">
   	        <span style="font-weight:bold; font-size:17px; color:#fff">其他帳號登入：</span>
<?php 
	$items = get_channel_items($this->input->get("game"), "../");
	foreach($items as $channel => $name):
		if ( ! in_array($channel, array('facebook', 'google'))) continue;
?>
	<a href="http://www.longeplay.com.tw/gate/login/<?=$this->input->get("game")?>?channel=<?=$channel?>&ad=<?=$ad?>"><img src="http://www.longeplay.com.tw/img/login/<?=$channel?>.png" width="36" title="<?=$name?> 帳號登入"/></a>
<? endforeach;?>             	        

   	        </td>
          </tr>
        </table>
   	  </div>
      <div id="character">
        <object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" width="750" height="620" align="absmiddle" id="FlashID2">
          <param name="movie" value="/p/register/my/index.swf" />
          <param name="quality" value="high" />
          <param name="wmode" value="transparent" />
          <param name="swfversion" value="6.0.65.0" />
          <!-- 此 param 標籤會提示使用 Flash Player 6.0 r65 和更新版本的使用者下載最新版本的 Flash Player。如果您不想讓使用者看到這項提示，請將其刪除。 -->
          <param name="expressinstall" value="Scripts/expressInstall.swf" />
          <!-- 下一個物件標籤僅供非 IE 瀏覽器使用。因此，請使用 IECC 將其自 IE 隱藏。 -->
          <!--[if !IE]>-->
          <object data="/p/register/my/index.swf" type="application/x-shockwave-flash" width="750" height="620" align="absmiddle">
            <!--<![endif]-->
            <param name="quality" value="high" />
            <param name="wmode" value="transparent" />
            <param name="swfversion" value="6.0.65.0" />
            <param name="expressinstall" value="Scripts/expressInstall.swf" />
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
</div>

</form>

<script type="text/javascript">
swfobject.registerObject("FlashID");
swfobject.registerObject("FlashID2");
</script>
</body>
</html>
