<style type="text/css">
input {padding:12px;}
</style>

<? if (get_mobile_os() == 'ios') {?>
	<script src='/p/js/iosBridge.js'></script>
	<script>
	function open_service()
	{
		calliOSFunction('openCoozPage', ['<?=base_url()?>/service?game=<?=$this->game?>']); 
	}
	</script>
<? 
}
else {
?>
	<script type="text/javascript">
	function open_service()
	{
		window.CoozSDK.openCoozPage("<?=base_url()?>/service?game=<?=$this->game?>");
	}
	</script>
	<? 
}
?>

<div id="main_content" >

<? if (get_mobile_os() == 'ios') echo  '<br><br><br><br>'?>

<? if ($this->g_user->check_login()):?>
<div style="padding:6px">
	Hello, <?=$this->g_user->display_account();?>
	<? if ($bind_account):?>
	<div>綁定於 <?=$bind_account?></div>
	<? endif;?>
</div>
<div>
<? if ( ! empty($redirect_url) && ! ($this->game == 'zj')):?>
<input type="button" value="開始遊戲" onclick="javascript:top.location.replace('<?=$redirect_url?>');" />
<? endif;?>

<? if ($this->g_user->is_channel_account() && $bind_account==false):
	$redirect_url = urlencode('http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']);
?>
<input type="button" value="綁定帳號" onclick="javascript:location.href='<?=site_url("member/bind_account?redirect_url={$redirect_url}")?>';" />
<? endif;?>

<input type="button" value="登出" onclick="javascript:location.href='<?=site_url("api/m_logout")?>'" />

<? if ($this->game == 'zj'):?>
<input type="button" value="線上提問" onclick="javascript:open_service()" />
<? endif;?>

<? if ($this->g_user->uid == '304757'): ?>

<form id="switch_uid" method="post" action="">
	<input type="text" style="width:200px;" name="uid">
	<input type="submit" value="切換" onclick="javascript:$('#switch_uid').attr('action', '<?=site_url("api/m_switch_uid?redirect_url={$redirect_url}")?>')"/>
</form>

<? endif;?>

<? if ($this->g_user->get_channel() == 'imei'):?>
<div style="color:#993333; font-size:13px; padding:12px 0;">
<? if ($this->game != 'zj'):?>
試玩帳號不須註冊即可立即體驗遊戲內容，讓您輕鬆暢遊遊戲；但若重新安裝或刪除遊戲及清除暫存後，將造成帳號資料遺失！
建議您體驗遊戲後可進行「綁定帳號」，避免您的心血遺失！

<?php else:?>

即日起將進行儲值返還作業；故使用「試玩」帳號者，請先於手機上綁定龍邑會員帳號，方可於返還後使用龍邑平台點數。

<?php endif;?>

</div>
<? endif;?>

<? if ($this->game == 'zj'):?>
<div>

<a href="<?=base_url()?>/bulletin/detail/6220" op="1">
《真三十六計》結束營運公告
</a>
</div>

<?php endif;?>

</div>
<? else:?>
<form name="form1" id="form1" method="post" action="/gate/login/<?=$this->game?>?redirect_url=<?=urlencode($redirect_url)?>">
                          <table height="155" border="0" cellpadding="1" cellspacing="4">
                            <tr> 
                              <td colspan="2" style="height: 10px;"></td>
                            </tr>
                            <tr> 
                              <td><div align="right">龍邑帳號：</div></td>
                              <td align="left"> <input tabindex="1" name="account" class="required" maxlength="18" type="text" size="18" 
                              						value="<?=empty($account) ? '' : ($this->g_user->check_extra_account($account) ? '' : $account)?>" /> 
                              </td>
                            </tr>
                            <tr> 
                              <td><div align="right">龍邑密碼：</div></td>
                              <td align="left"> <input tabindex="2" name="pwd" type="password"  class="required" id="txtbox" maxlength="32"  size="20" AUTOCOMPLETE='OFF'/> 
                              </td>
                            </tr>
                            <tr> 
                              <td></td>
                              <td valign="top"> 
                                    <input tabindex="3" name="doLogin" type="submit" id="doLogin3" value="送出" />
                                    <input type="button" value="註冊帳號" onclick="javascript:location.href='<?=site_url("member/register?redirect_url=".urlencode($redirect_url))?>';" />
                                    <? if ( ! empty($imei)):?>
                                    <input type="button" value="試玩" onclick="javascript:location.href='<?=site_url("api/m_use_imei?".$_SERVER['QUERY_STRING'])?>';" style="background:#019858; border:1px solid #01814A"/>
                                    <? endif;?> 
                                    <input tabindex="4" type="hidden" name="remember" id="remember" value="0" />
                              </td>
                            </tr> 
                           
                          </table>
    	</form>

		<img src="/img/login-img-01.jpg" width="150" border="0">
		<div style="padding:6px">
<? 
	$ad = $this->input->get("ad", true);
	$items = get_channel_items($this->game, "../");
	foreach($items as $channel => $name):
		if ($this->game == 'eya' && $channel == 'facebook') continue;		   	
?>
			<a href="<?=base_url()?>/gate/login/<?=$this->game?>?channel=<?=$channel?>&ad=<?=$ad?>&redirect_url=<?=urlencode($redirect_url)?>&"><img src="/img/login/<?=$channel?>.png" width="40" title="<?=$name?> 帳號登入" border="0"/></a>
			<? endforeach;?>
		</div>
		
<? endif;?>

</div>