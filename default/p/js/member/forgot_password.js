$(function(){

	$("#forgot_form").validate({
		onfocusout: false,
		onkeyup: false,
		onclick: false,
		messages:
		{
			email: {
				required: "尚未填寫 E-Mail 或手機號碼",
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
				leOpenDialog('錯誤', err, leDialogType.MESSAGE);
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
						leOpenDialog('修改成功', json.message, leDialogType.MESSAGE, function()
						{
							location.href = '/member/index?site='+json.site;
						});
                        return;
					}
					else
					{
						leOpenDialog('錯誤', json.message, leDialogType.MESSAGE);
					}
				}
			});
		}
	});
	
});
