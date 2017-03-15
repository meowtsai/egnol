<script type='text/javascript'>
	$(function ()
	{
		$("#btn-service-back").click(function OnClickBack()
		{
			if(typeof LongeAPI != 'undefined')
			{
				LongeAPI.onServiceSuccess();
			}
			else
			{
				var iframe = document.createElement('IFRAME');
				iframe.setAttribute('src', "ios://cancelbutton");
				document.documentElement.appendChild(iframe);
				iframe.parentNode.removeChild(iframe);
			}

			$('.login-button').css('display', 'none');
		});
	});
</script>
<div id="content-login">
	<div class="login-ins">
		<div class="login-button">
			<p>
			</p>
			<p>
				<img style="cursor:pointer;" src="<?=$longe_url?>p/image/server/server-btn.png" class="button_submit" onclick="javascript:location.href='<?=$api_url?>api2/ui_service_question?site=<?=$site?>'" />&nbsp;
				<img style="cursor:pointer;" src="<?=$longe_url?>p/image/server/server-btn2.png" class="button_submit" onclick="javascript:location.href='<?=$api_url?>api2/ui_service_list?site=<?=$site?>'" />
			</p>
			<div class="login-button">
				<p><img id="btn-service-back" style="cursor:pointer;" src="<?=$longe_url?>p/image/server/server-back-btn2.png" /></p>
			</div>
			<p>
				<a href="<?=$longe_url?>member/service_agreement?site=<?=$site?>" style="vertical-align:text-bottom;color:#a50000">會員服務條款</a><span style="vertical-align:text-bottom;">、</span>
				<a href="<?=$longe_url?>member/member_agreement?site=<?=$site?>" style="vertical-align:text-bottom;color:#a50000">個資同意書</a><span style="vertical-align:text-bottom;">、</span>
				<a href="<?=$longe_url?>member/privacy_agreement?site=<?=$site?>"  style="vertical-align:text-bottom;color:#a50000">隱私權政策</a>
			</p>
			<br>
			<ul class="notes">
				<li>提醒大俠：需附檔案回報時，請直接利用官網線上提問，謝謝。</li>
			</ul>
		</div>
	</div>
</div>