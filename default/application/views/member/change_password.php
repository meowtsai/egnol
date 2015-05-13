<style type="text/css">
#main table td {
	padding: 3px;
}
</style>
<div>

<? if ($bind_data):?>

<p>帳號綁定在 <b><?=$bind_data->account?></b></p>
<? endif;?>

<form method="post" class="json_form" action="<?=site_url("member/change_password_json")?>">
<input type="hidden" name="redirect_url" value="<?=$this->input->get("redirect_url", TRUE)?>">

              <table style="width:100%;">
                <tr> 
                  <td width="130" align="right">修改密碼：</td>
                  <td><input type="password" id="pwd" name="pwd" size="24" maxlength="35" class="required" value="" minlength="6" /></td>
                </tr>
                <tr> 
                  <td align="right">請再次輸入密碼：</td>
                  <td><input type="password" id="pwd2" name="pwd2" size="24" maxlength="35" class="required" equalTo="#pwd" value="" minlength="6"  /></td>
                </tr>
              </table>
              
    <input type="submit" value="確認送出" class="btn">
</form>

</div>


