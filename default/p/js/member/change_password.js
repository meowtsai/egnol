$(function()
{
	$("#change_form").validate({
		onfocusout: false,
		onkeyup: false,
		onclick: false,
		messages:
		{
			old: {
				required: "`舊密碼`必填",
			},
			pwd: {
				required: "`密碼`必填",
				minlength: "`密碼`最少6碼",
				maxlength: "`密碼`最多18碼",
			},
			pwd2: { 
				required: "`確認密碼`必填",
				equalTo: "兩次密碼不相同",
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
				leOpenDialog('修改密碼錯誤', err, leDialogType.MESSAGE);
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
						leOpenDialog('修改密碼', json.message, leDialogType.MESSAGE);
					}
				}
			});
		}
	});
});

