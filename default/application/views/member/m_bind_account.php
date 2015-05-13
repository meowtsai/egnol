<style type="text/css">
input {padding:12px;}
label {display:block;}
</style>

<div style="color:#993333; font-size:13px;">綁定帳號可將第三方社群帳號及試玩帳號與龍邑帳號進行整合，以確保帳號相關紀錄的完整性。</div>

<p style="padding:12px 0;">您的帳號為 <?=$this->g_user->display_account();?></p>


<div style="border-top:1px solid #ccc; padding:5px 0;">

<? if ($bind_data):?>

<p>帳號已綁定在 <b><?=$bind_data->account?></b></p>

<!-- <a href="<?=site_url("member/change_password")?>">#修改密碼</a> -->

<? else:?>

<form method="post" class="json_form" action="<?=site_url("member/bind_account_json")?>">
<input type="hidden" name="redirect_url" value="<?=$this->input->get("redirect_url", TRUE)?>">

<label>請設定帳號：<br>
	<input type="text" name="account" size="24" maxlength="35" class="required" minlength="6" value="" />
</label>

<label>請設定密碼：<br>
	<input type="password" id="pwd" name="pwd" size="24" maxlength="35" class="required" value="" />	
</label>

<label>請再次輸入密碼：<br>
	<input type="password" name="pwd2" size="24" maxlength="35" class="required" equalTo="#pwd" value=""  />
</label>
            
<p><input type="submit" value="確認送出"></p>
</form>

<? endif;?>

</div>


