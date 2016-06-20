<div id="content-login">
	<div id="header">
	<div class="header-ins">
	<div class="header-logo" style="margin-left:0px;">
	<div id="api-header-logo"></div>
	</div>
	</div>
	</div>
	<div id="content-area" class="login-ins">
	    <div class="login-form" style="padding-top:0px;">
			<div id="info_block" style="border-radius:10px;border:1px solid #ddd;background-color:#d6d6d6;padding:10px 0;">
				<table class="member_info">
					<tr><td style="font-weight:bold;"><div style="color:#000;line-height:18px;">帳號類型</div></td></tr>
					<tr><td style=""><div style="color:#333333;margin-bottom:16px;padding:0;line-height:18px;">Facebok 帳號</div></td></tr>
					<tr><td style="font-weight:bold;"><div style="color:#000;line-height:18px;">E-MAIL</div></td></tr>
					<tr><td style=""><div style="color:#333333;margin-bottom:16px;padding:0;line-height:18px;">abc.xyz.123@longeplay.com.tw</div></td></tr>
					<tr><td style="font-weight:bold;"><div style="color:#000;line-height:18px;">手機號碼</div></td></tr>
					<tr><td style=""><div style="color:#333333;padding:0;line-height:18px;">0978978978</div></td></tr>
<?/*					
					<tr><td style="color:#000;font-weight:bold;margin:0;padding:0;">帳號類型</td></tr>
					<tr><td style="margin:0;padding:0;"><div style="color:#000;padding:4px;border-radius:10px;border:1px solid #ddd;background-color:#d6d6d6;">龍邑會員</div></td></tr>
					<tr><td style="color:#000;font-weight:bold;margin:0;padding:0;">E-MAIL</td></tr>
					<tr><td style="margin:0;padding:0;"><div style="color:#000;padding:4px;border-radius:10px;border:1px solid #ddd;background-color:#d6d6d6;word-wrap:break-word;">abc.xyz.123@longeplay.com.tw</div></td></tr>
					<tr><td style="color:#000;font-weight:bold;margin:0;padding:0;">手機號碼</td></tr>
					<tr><td style="margin:0;padding:0;"><div style="color:#000;padding:4px;border-radius:10px;border:1px solid #ddd;background-color:#d6d6d6;">0978978978</div></td></tr>
*/?>					
				</table>
			</div>
			<div id="button_block" class="login-button">
				<p>
					<img id="_change-account-btn" class="button_info" />
					<img id="_bind-account-btn" class="button_info" />
				</p>
				<img id="continue" class="_continue" />
		    </div>
	    </div>
	</div>
</div>
<script>
$(function()
{
	var h = $(window).height() - $("#content-area").height() - $("#header").height();
	if(h > 0)
	{
		$("#content-area").css("padding-top", h / 2 - 10);
	}
/*	
	var ib = $("#info_block");
	var h = ($(window).height() - $("#button_block").height() - $("#header").height()) * 0.6;
	if(h > ib.height())
	{
		ib.height(h);
	}
*/	
});
</script>