$(function()
{
	$("#question_form").validate({
		onfocusout: false,
		onkeyup: false,
		onclick: false,
		messages: {
			question_type: "尚未選擇問題類型",
			content: {
				required: "尚未填寫問題描述",
				minlength: "問題描述最少需填寫 5 個字",
				maxlength: "問題描述最多填寫 500 個字"
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
				leOpenDialog('提問錯誤', err, leDialogType.MESSAGE);
			}
		},
		submitHandler: function(form)
		{
			$(form).ajaxSubmit(
			{
				dataType: 'json',
				success: function(json)
				{
					if (json.status == 'success')
					{
		                leOpenDialog('玩家提問', '提問成功！', leDialogType.MESSAGE, function()
						{
		                    location.href = '/api2/ui_service?site=' + json.site;
						});
					}
					else
					{
						leOpenDialog('提問錯誤', json.message, leDialogType.MESSAGE);
					}
				}
			});
		}
	});
});
