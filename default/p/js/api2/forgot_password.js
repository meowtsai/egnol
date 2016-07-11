$(function(){

	$("#forgot_form").validate({
		onfocusout: false,
		onkeyup: false,
		onclick: false,
		messages:
		{
			account: {
				required: "E-Mail 或手機號碼必填"
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
						leOpenDialog('成功', json.message, leDialogType.MESSAGE, function()
                        {
                            location.href = '/api2/ui_login?site=' + json.site;
                        });
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
