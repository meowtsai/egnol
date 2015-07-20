$(function()
{
	$("form").validate({
		messages:
		{
			email: {
				required: "`電子信箱`與`行動電話`至少需填寫其中之一",
				email: "請填寫正確的電子信箱位址"
			},
			mobile: {
				required: ""
			}
		},
		rules:
		{
			email: {
				required: "#mobile:blank"
			},
			mobile: {
				required: "#email:blank"
			}
    	},
		submitHandler: function(form)
		{
			$(form).json_ajaxSubmit(function(json)
			{
				alert(json.message);
				if (json.status == 'success')
				{
					location.href = location.href;
				} 				
			});
		}
	});
	
});
