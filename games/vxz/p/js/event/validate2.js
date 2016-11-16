$(function()
{
	$("#choose_form").validate({
		onfocusout: false,
		onkeyup: false,
		onclick: false,
		messages:
		{
			character_id: {
				required: "尚未小李飛刀獲得點數角色"
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
				leOpenDialog('記錄錯誤', err, leDialogType.MESSAGE);
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
						location.href = '/event/e02_content';
						return;
					}					
					else
					{
						leOpenDialog('記錄錯誤', json.message, leDialogType.MESSAGE);
					}
				}
			});
		}
	});
});
