$(function()
{
	$("#member_update").validate({
		onfocusout: false,
		onkeyup: false,
		onclick: false,
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
		showErrors: function(errorMap, errorList)
		{
		   var err = '';
		   $(errorList).each(function(i, v)
		   {
			   err += v.message + "<br/>";
		   });
		   if (err)
		   {
				leOpenDialog('修改資料錯誤', err, leDialogType.MESSAGE);
		   }
		},
		submitHandler: function(form)
		{
			$(form).ajaxSubmit({
				dataType: 'json',
				success: function(json)
				{
					if (json.status == 'success')
					{
						location.href = '/member/index?site='+json.site;
						return;
					}
					else
					{
						leOpenDialog('修改資料錯誤', json.message, leDialogType.MESSAGE);
					}
				}
			});
		}
	});
	
});
