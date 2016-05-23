<div id="header">
<div class="header-ins">
<div class="header-logo">
<img src="https://r2g.longeplay.com.tw/p/image/header-logo.png" />
</div>
</div>
</div>
<div id="content-login">
	<div class="login-ins">
	    <div class="login-form" style="padding-top:0px;">
			<div id="info_block">
				<table class="member_info">
					<tr>
						<th>帳號類型　|</th>
						<td>龍邑會員</td>
					</tr>
					<tr>
						<th>E-MAIL　|</th><td style="word-wrap:break-word;line-height:18px">abc.xyz.123@longeplay.com.tw</td>
					</tr>
					<tr>
						<th>手機號碼　|</th><td></td>
					</tr>
				</table>
			</div>
			<div id="button_block" class="login-button">
				<p>
					<img id="_change-account-btn" class="button_info" />
					<img id="_bind-account-btn" class="button_info" />
				</p>
				<img id="_continue" />
		    </div>
	    </div>
	</div>
</div>
<script>
$(function()
{
	var ib = $("#info_block");
	var h = ($(window).height() - $("#button_block").height() - $("#header").height()) * 0.6;
	if(h > ib.height())
	{
		ib.height(h);
	}
});
</script>