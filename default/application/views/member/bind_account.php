<style type="text/css">
#main table td {padding:3px;}
</style>

<div class="tip">綁定帳號是將您之前使用的第三方社群帳號與龍邑帳號連結使用，讓您不用再因為第三方社群平台維護而無法登入遊戲。</div>

<p>您的帳號為 <?=$this->g_user->display_account();?></p>


<div style="padding:12px; border-top:1px solid #ccc;">

<? if ($bind_data):?>

<p>帳號已綁定在 <b><?=$bind_data->account?></b></p>

<!-- <a href="<?=site_url("member/change_password")?>">#修改密碼</a> -->

<? else:?>

<form method="post" class="json_form" action="<?=site_url("member/bind_account_json")?>">
<input type="hidden" name="redirect_url" value="<?=$this->input->get("redirect_url", TRUE)?>">
              <table style="width:100%;">
                <tr> 
                  <td width="150" align="right">請設定帳號：</td>
                  <td><input type="text" name="account" size="24" maxlength="35" class="required" minlength="6" value="" /></td>
                </tr>
                <tr> 
                  <td align="right">請設定密碼：</td>
                  <td><input type="password" id="pwd" name="pwd" size="24" maxlength="35" class="required" value="" /></td>
                </tr>
                <tr> 
                  <td align="right">請再次輸入密碼：</td>
                  <td><input type="password" name="pwd2" size="24" maxlength="35" class="required" equalTo="#pwd" value=""  /></td>
                </tr>
			</table>
<p><input type="submit" class="btn" value="確認送出"></p>
</form>

<? endif;?>

</div>


