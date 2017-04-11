<?
	$email = !empty($this->g_user->email) ? $this->g_user->email : "";
	$mobile = !empty($this->g_user->mobile) ? $this->g_user->mobile : "";
	$external_id = !empty($this->g_user->external_id) ? $this->g_user->external_id : "";
	$account_type = "龍邑會員";
	$is_bind = false;

	if(strpos($external_id, "@facebook"))
	{
		$account_type = "Facebook 帳號";
	}
	else if(strpos($external_id, "@google"))
	{
		$account_type = "Google 帳號";
	}
	else if(strpos($external_id, "@device"))
	{
		$account_type = "行動裝置帳號";
	}

	if(!empty($this->g_user->email) || !empty($this->g_user->mobile))
		$is_bind = true;
?>
<div class="site_container">
    <div class="grid_container">
        <h1>會員中心</h1>
        <div id="line"></div>
        <form id="login_form" method="post" action="<?=$api_url?>api2/ui_login_game_json?site=<?=$site?>">
            <? if(!$is_bind && $account_type == "Google 帳號"): ?>
                <p class="text_01">2017/4/18以後將不再支援Google帳號登入，請玩家務必先完成帳號密碼綁定。</p>
            <? elseif(!$is_bind): ?>
                <p class="text_01">若您目前使用第三方帳號或直接玩登入遊戲，建議進行帳號綁定，避免遊戲資料遺失。</p>
            <? endif; ?>
            <div id="text_area">
                <? if($is_bind): ?>
                    <p><?=$account_type?></p>
                    <p><?=(!empty($email) ? $email : "Email尚未設定")?></p>
                    <p><?=(!empty($mobile) ? $mobile : "手機號碼尚未設定")?></p>
                <? else: ?>
                    <p><?=$account_type?>(尚未綁定)</p>
                <? endif; ?>
            </div>
            <br>
            <p>
                <button id="_change-account-btn" type="button" class="btn btn-primary btn-large" style="width:32%;" onclick="javascript:window.location.href='<?=$api_url?>api2/ui_change_account?site=<?=$site?>'">切換帳號</button>
                <? if($is_bind): ?>
                    <button id="_change-pwd-btn" type="button" class="btn btn-primary btn-large" style="width:32%;" onclick="javascript:window.location.href='<?=$api_url?>api2/ui_change_password?site=<?=$site?>'">修改密碼</button>
                <? else: ?>
                    <button id="_bind-account-btn" type="button" class="btn btn-primary btn-large" style="width:32%;" onclick="javascript:window.location.href='<?=$api_url?>api2/ui_bind_account?site=<?=$site?>'">綁定帳號</button>
                <? endif; ?>
                <button type="button" class="btn btn-primary btn-large" style="width:32%;" onclick="javascript:window.location.href='<?=$api_url?>api2/ui_service?site=<?=$site?>'">客服中心</button>
            </p>
            <input name="doLogin" type="submit" id="doSubmit" value="" style="display:none;" />
			
            <? if(!$is_bind && $account_type == "Google 帳號"): ?>
                <p class="text_01">2017/4/18以後將不再支援Google帳號登入，請玩家務必先完成帳號密碼綁定。</p>
            <? else: ?>
            	<button id="continue" type="button" class="btn btn-primary btn-block btn-large _continue">進入遊戲</button>
			<? endif; ?>
        </form>
        <div id="line"></div>
        <div id="longe"><img src="<?=$api_url?>/p/image/api2/longe.png" width="66" height="30"></div>
    </div>
</div>