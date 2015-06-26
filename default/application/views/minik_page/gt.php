<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>:::龍邑 Web Game:::絕代雙驕</title>

		<style type="text/css" media="screen">
		html, body { height:100%; background-color: #ffffff;}
		body { margin:0; padding:0; overflow:hidden; }
		#flashContent { width:100%; height:100%; }
		</style>
		
<link rel='stylesheet' type='text/css' href='http://gt.longeplay.com.tw/p/css/default.css'>
<script src="/p/minik/gt/swfobject_modified.js" type="text/javascript"></script>

<body>

<iframe id="invite_ifm" src="" width="0" height="0" frameborder="0"></iframe>

<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td align="center" style="background:url(/p/minik/gt/img/bg.jpg) top center no-repeat;"><object id="FlashID" classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" width="1000" height="630">
      <param name="movie" value="/p/minik/gt/page.swf" />
      <param name="quality" value="high" />
      <param name="wmode" value="opaque" />
      <param name="swfversion" value="6.0.65.0" />
      <!-- 此 param 標籤會提示使用 Flash Player 6.0 r65 和更新版本的使用者下載最新版本的 Flash Player。如果您不想讓使用者看到這項提示，請將其刪除。 -->
      <param name="expressinstall" value="/p/minik/gt/expressInstall.swf" />
      <!-- 下一個物件標籤僅供非 IE 瀏覽器使用。因此，請使用 IECC 將其自 IE 隱藏。 -->
      <!--[if !IE]>-->
      <object type="application/x-shockwave-flash" data="/p/minik/gt/page.swf" width="1000" height="630">
        <!--<![endif]-->
        <param name="quality" value="high" />
        <param name="wmode" value="opaque" />
        <param name="swfversion" value="6.0.65.0" />
        <param name="expressinstall" value="/p/minik/gt/expressInstall.swf" />
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
</table>

<div id="choose" style="display:none;"><?=$choose_server_form?></div>

<script type="text/javascript" src="/p/js/jquery-1.7.2.min.js"></script>
<script type="text/javascript" src='/p/js/jquery.blockUI.js'></script>
<script type="text/javascript">
function invite_friend() {
	$('#invite_ifm').attr("src", "<?=$inviteSrc?>");
}
function chose_server_form() {
	<? if ($old_user):?>
	window.open("http://gt.longeplay.com.tw/common/choose_server_form");
	<? else:?>	
	window.open("<?=base_url()?>/play_game/gt");
	<? endif;?>    
}
</script>

</body>
</html>