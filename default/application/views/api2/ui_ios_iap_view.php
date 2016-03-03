<script type='text/javascript'>
	function OnClickConfirm()
	{
		var itemId = $('#items').val();
		if(itemId != "")
		{
			var iframe = document.createElement('IFRAME');
			iframe.setAttribute('src', "ios://iapconfirm-_-" + itemId);
			document.documentElement.appendChild(iframe);
			iframe.parentNode.removeChild(iframe);
			
			$('.login-button').css('display', 'none');
		}
	}
	
	function OnClickCancel()
	{
		var iframe = document.createElement('IFRAME');
		iframe.setAttribute('src', "ios://iapcancel");
		document.documentElement.appendChild(iframe);
		iframe.parentNode.removeChild(iframe);
			
		$('.login-button').css('display', 'none');
	}
	
	function AddItem(itemId, itemName, desc, price)
	{
		$('#items').append("<option value='" + itemId + "' desc='" + desc + "' price='" + price + "'>" + itemName + "</option>");
	}
	
	function SetStatus(status)
	{
	}
	
	$(function ()
	{
		$("#items").change(function()
		{
			var desc = $("#items option:selected" ).attr('desc');
	  		$('#payment_msg').html(desc);
		});
		
		var iframe = document.createElement('IFRAME');
		iframe.setAttribute('src', "ios://iapgetproducts");
		document.documentElement.appendChild(iframe);
		iframe.parentNode.removeChild(iframe);
	});
</script>
<div id="content-login">
	<div class="login-ins">
		<div class="login-form">
			<div>請選擇欲購買項目：</div>
			<table class="member_info">
				<tr>
					<th>商品</th>
					<td>
						<select id="items" name="items" class="required" style="width:85%;">
							<option value="" desc="">--請選擇--</option>
						</select>
					</td>
				</tr>
			</table>
			<ul class="notes">
				<li id="payment_msg" style="height:200px;overflow-y:auto;"></li>
			</ul>
			<div class="login-button">
				<p><img style="cursor:pointer;" src="<?=$longe_url?>p/image/money/confirm-btn.png" onclick="javascript:OnClickConfirm()" /></p>
				<p><img style="cursor:pointer;" src="<?=$longe_url?>p/image/member/clear.png" onclick="javascript:OnClickCancel();" /></p>
			</div>
		</div>
	</div>
</div>
