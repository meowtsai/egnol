<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>:::龍邑 Web Game:::大漢傳奇</title>

<link href="/p/register/dh/index.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.8.2/jquery.min.js"></script>
<script type="text/javascript" src="/p/js/jquery.validate.min.js"></script>
<script type="text/javascript" src="/p/js/jquery.form.js"></script>
<script type="text/javascript" src="/p/register/default.js"></script>
<script src="/p/register/dh/Scripts/swfobject_modified.js" type="text/javascript"></script>

<body>

<form id="register_form" class="register-form" method="post" action="<?=site_url("gate/register_json/dh")?>">
<input type="hidden" value="<?=$redirect_url?>" id="redirect_url">

<table width="100%" border="0" cellspacing="0" cellpadding="0" style="background:url(/p/register/dh/img/y-login-bg.jpg) top center no-repeat;">
  <tr>
    <td align="center" valign="top"><table width="1013" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td colspan="3"><img src="/p/register/dh/img/index-space.gif" width="1014" height="156" /></td>
        </tr>
      <tr>
        <td width="356" align="left" valign="top"><img src="/p/register/dh/img/index-space.gif" width="356" height="341" /></td>
        <td width="298" align="left" valign="top"><table width="100" border="0" cellpadding="0" cellspacing="0">
          <tr>
            <td align="left"><img src="/p/register/dh/img/y-login-logo.gif" width="298" height="120" /></td>
          </tr>
          <tr>
            <td align="left"><img src="/p/register/dh/img/y-login-img-01.jpg" width="120" height="37" /></td>
          </tr>
          <tr>
            <td align="left"><table width="281" border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td width="98"><img src="/p/register/dh/img/y-login-img-02.jpg" width="98" height="23" /></td>
                <td width="183" style="background:url(/p/register/dh/img/y-login-img-05.jpg) top no-repeat;">
                	<input type="text" name="account" size="24" maxlength="35" class="form-2 required" minlength="6" value="" style="background:transparent"  />
                  </span></td>
              </tr>
            </table></td>
          </tr>
          <tr>
            <td height="5" align="left"></td>
          </tr>
          <tr>
            <td align="left"><table width="280" border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td width="98"><img src="/p/register/dh/img/y-login-img-03.jpg" width="98" height="23" /></td>
                <td width="182" style="background:url(/p/register/dh/img/y-login-img-05.jpg) top no-repeat;">
                	<input type="password" name="pwd" size="24" maxlength="35" class="form-2 required" value="" style="background:transparent" />
                  </span></td>
              </tr>
            </table></td>
          </tr>
          <tr>
            <td height="5" align="left"></td>
          </tr>
          <tr>
            <td align="left"><table width="281" border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td width="98"><img src="/p/register/dh/img/y-login-img-04.jpg" width="98" height="23" /></td>
                <td width="183" style="background:url(/p/register/dh/img/y-login-img-05.jpg) top no-repeat;">
                	<input type="password" name="pwd2" size="24" maxlength="35" class="form-2 required" value="" style="background:transparent"/>
                  </span></td>
              </tr>
            </table></td>
          </tr>
          <tr>
            <td height="5" align="left"></td>
          </tr>
          <tr>
            <td align="left"><table width="281" border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td width="98" style="text-align:center">驗證碼</td>
                <td width="183" >
                  	<input type="text" name="captcha" size="5" maxlength="4" minlength="4" class=" required" value="" />
                  	<div class="verification" style="float:left;"><div id="captcha_area"></div></div>
                 </td>
              </tr>
            </table></td>
          </tr>          
          <tr>
            <td align="left"><img src="/p/register/dh/img/index-space.gif" alt="" width="86" height="14" /></td>
          </tr>
          <tr>
            <td align="left"><a href="javascript:;" onclick="$('#register_form').submit()"><img src="/p/register/dh/img/y-login-icon-01.jpg" height="34" /></a></td>
          </tr>
          <tr>
            <td height="57" align="left">
           <div style="padding:12px;">
<?php 
	$items = get_channel_items("dh", "../");
	foreach($items as $channel => $name):
?>
	<a href="http://www.longeplay.com.tw/gate/login/dh?channel=<?=$channel?>&ad=<?=$ad?>"><img src="http://www.longeplay.com.tw/img/login/<?=$channel?>.png" width="33" title="<?=$name?> 帳號登入"/></a>
<? endforeach;?>  
                </div>
            </td>
          </tr>
        
        </table></td>
        <td width="360" valign="top"><table border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td colspan="2"><img src="/p/register/dh/img/index-space.gif" width="360" height="120" /></td>
            </tr>
          <tr>
            <td width="86"><img src="/p/register/dh/img/index-space.gif" width="86" height="126" /></td>
            <td><object id="FlashID" classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" width="274" height="126">
                <param name="movie" value="/p/register/dh/img/y-login-img-10-1.swf" />
                <param name="quality" value="high" />
                <param name="wmode" value="transparent" />
                <param name="swfversion" value="6.0.65.0" />
                <!-- 此 param 標籤會提示使用 Flash Player 6.0 r65 和更新版本的使用者下載最新版本的 Flash Player。如果您不想讓使用者看到這項提示，請將其刪除。 -->
                <param name="expressinstall" value="Scripts/expressInstall.swf" />
                <!-- 下一個物件標籤僅供非 IE 瀏覽器使用。因此，請使用 IECC 將其自 IE 隱藏。 -->
                <!--[if !IE]>-->
                <object type="application/x-shockwave-flash" data="/p/register/dh/img/y-login-img-10-1.swf" width="274" height="126">
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
              </object></td>
            </tr>
          <tr>
            <td colspan="2" valign="top"><img src="/p/register/dh/img/index-space.gif" width="360" height="95" /></td>
            </tr>
          </table></td>
      </tr>
      <tr>
        <td colspan="3" valign="top"><img src="/p/register/dh/img/index-space.gif" width="1014" height="170" /></td>
      </tr>
    </table></td>
  </tr>
</table>

</form>

<map name="Map" id="Map">
  <area shape="rect" coords="11,7,52,48" href="http://p1.games.tp2.yahoo.com/playweb/reg.php?gamename=DH" />
  <area shape="rect" coords="94,5,134,48" href="http://www.longeplay.com.tw/member/login_gate.php?gate=bahamut&game_site=dh" />
  <area shape="rect" coords="135,4,175,47" href="http://www.longeplay.com.tw/member/login_gate.php?gate=gamebase&game_site=dh" />
</map>
<script type="text/javascript">
swfobject.registerObject("FlashID");
swfobject.registerObject("FlashID");

function chkForm()
{
    var frm = document.getElementById('form1');
    if (frm.account.value == '' || frm.password.value == '' || frm.password2.value == '' ) {
        alert('尚有欄位未填妥!');
    }
    else if (frm.password.value !== frm.password2.value) {
        alert('兩次密碼輸入不同!');
    }
    else {
        frm.submit();
    }
}
</script>
</body>
</html>

