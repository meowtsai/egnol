$(function()
{
	$("#earlylogin_form").validate({
		onfocusout: false,
		onkeyup: false,
		onclick: false,
		messages:
		{
			email: {
				required: "尚未填寫`電子信箱`",
				email: "請填寫正確的電子信箱位址"
			},
			mobile: {
				required: "尚未填寫`行動電話`"
			},
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
				leOpenDialog('預註冊錯誤', err, leDialogType.MESSAGE);
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
						location.href = '/event/e01_register?site='+json.site;
						return;
					}					
					else
					{
						leOpenDialog('預註冊錯誤', json.message, leDialogType.MESSAGE);
					}
				}
			});
		}
	});
});
