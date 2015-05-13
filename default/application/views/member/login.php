<div style="text-align:center; padding:45px;">

<table width="610" height="180" border="0" cellpadding="0" cellspacing="0" align="center">
  <tr>
    <td align="center">
     
     <? if ($game_name):?>
	<!-- <div>登入<?=$game_name?></div> -->
	<? endif;?>    
	<div>請先登入會員</div>
	
    <form name="form1" id="form1" method="post" action="/gate/login/<?=$site?>?redirect_url=<?=urlencode($redirect_url)?>">
                          <table width="50%" height="155" border="0" cellpadding="1" cellspacing="4">
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
                                    <input tabindex="4" type="hidden" name="remember" id="remember" value="0" />
                              </td>
                            </tr> 
                           
                            <tr> 
                              <td>&nbsp;</td>
                              <td class="text-gray-light">
                                <a href="<?=site_url("member/register")?>">立即註冊</a>│<a href="<?=site_url("member/forgot_password")?>">忘記密碼</a>
                              </td>
                            </tr>
                          </table>
    	</form>
	</td>
  </tr>
  <tr>
    <td bgcolor="#e5e5e5" width="100%" >
    	
		<img src="/img/login-img-01.jpg" width="150" border="0">
		<div style="padding:6px">
<? 
	$ad = $this->input->get("ad", true);
	$items = get_channel_items($site, "../");
	foreach($items as $channel => $name):		   	
?>
			<a href="http://www.long_e.com.tw/gate/login/<?=$site?>?channel=<?=$channel?>&ad=<?=$ad?>&redirect_url=<?=urlencode($redirect_url)?>&"><img src="http://www.long_e.com.tw/img/login/<?=$channel?>.png" width="40" title="<?=$name?> 帳號登入" border="0"/></a>
			<? endforeach;?>
		</div>
   </td>
  </tr>
</table>

</div>