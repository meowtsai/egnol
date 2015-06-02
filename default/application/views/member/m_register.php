<script src='/p/js/member/register.js'></script>
<style type="text/css">
#long_e_register {}
#long_e_register a {color:#00a;}
#long_e_register table td {padding:3px;}
#long_e_register .tip {font-size:13px; color:#095;}
</style>

<div id="long_e_register">

<form id="register_form" method="post" action="/member/register_json">
<input type="hidden" id="redirect_url" value="<?=$redirect_url?>">

<table>
	<tr>
		<td style="width:110px"><img src="/img/ball-blue.gif"> 會員帳號：</td>
		<td><input type="text" name="account" class="required" minlength="6" maxlength="18">
			<div class="tip">6~18碼英文(系統會自動將大寫轉小寫)或數字組合。</div>
		</td>
	</tr>
	<tr>
		<td><img src="/img/ball-blue.gif"> 會員密碼：</td>
		<td><input type="password" id="pwd" name="pwd" class="required" minlength="6" maxlength="18">
			<div class="tip">6~18碼。</div>
		</td>
	</tr>
	<tr>
		<td><img src="/img/ball-blue.gif"> 確認密碼：</td>
		<td><input type="password" name="pwd2" class="required" equalTo='#pwd'></td>
	</tr>
</table>

<table>
	<tr>
		<td><img src="/img/ball-blue.gif"> 驗證碼：</td>
		<td>
			<div id="captcha_area"></div>
			<input type="text" name="captcha" size="5" maxlength="4" minlength="4" class="required" value="" />
		</td>
	</tr>		
	<tr>
		<td></td>
		<td>
			<input name="chk" type="checkbox" id="chk" class="required"> <label for="chk">我同意</label> <a href="<?=site_url("platform/member_rule")?>" target="_blank">會員條款</a>	
		</td> 
	</tr>	
</table>

<div style="text-align:center; margin:5px;">
	<input type="image" src="/img/member-icon-3.gif" width="107" height="25" >
</div>

</form>

</div>