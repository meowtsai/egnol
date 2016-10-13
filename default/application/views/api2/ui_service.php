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
			<ul class="notes">
				<li>提醒大俠：查詢回報紀錄及需附檔案回報時，請直接利用官網線上提問或遊戲主畫面中的客服功能回報，謝謝。</li>
			</ul>
		</div>
	</div>
</div>