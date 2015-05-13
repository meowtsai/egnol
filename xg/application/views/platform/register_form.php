<style>
#register_form input {border:0px; padding:3px; background:none; color:#fff;}
#register_form td {height:40px; line-height:40px;}
</style>

<form id="register_form" method="post" action="<?=site_url("platform/register_json")?>">

<div style="background:url(/p/img/register_bg.png); width:794px; height:691px; position:relative; text-align:left;">
	<div style="position:absolute; top:248px; left:599px;">
		<a href="javascript:;" class="close"><img src="/p/img/close_off.png" class="change"></a>
	</div>
	<div style="position:absolute;  top:283px; left:472px; text-align:left; width:380px;">	
		<table cellpadding="0" cellspacing="0" style="width:100%;">
			<tr>
				<td>
					<input type="text" name="account" size="24" maxlength="35" class="form-1 required" minlength="6" value="" /></td>
			</tr>
			<tr>
				<td><input type="password" name="pwd" size="24" maxlength="35" class="form-1 required" value="" /></td>
			</tr>
			<tr>
				<td><input type="password" name="pwd2" size="24" maxlength="35" class="form-1 required" value="" /></td>
			</tr>
			<tr>
				<td>
				<input type="text" name="captcha" size="5" maxlength="4" minlength="4" class="form-1 required" value="" />
				<span style="display:inline-block; margin-left:38px;"><div id="captcha_area"></div></span>
				</td>
			</tr>
		</table>
	</div>
	
	<div style="position:absolute; left:427px; bottom:191px;">
		<a href="javascript:;" onclick="$('#register_form').submit()"><img src="<?=$img_url?>register_btn_on.jpg" class="change" border="0" /></a>
	</div>
</div>

</form>