$(document).ready(function()
{
	$("#login_form").validate({
        focusInvalid: false,
		onfocusout: false,
		onkeyup: false,
		onclick: false,
		messages:
		{
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
		        $('#continue').show();
				leOpenDialog('登入錯誤', err, leDialogType.MESSAGE);
		   }
		},
		submitHandler: function(form)
		{
			leLoading();
			$(form).ajaxSubmit({
				dataType: 'json',
				success: function(json)
				{
					if (json.status == 'success')
					{
                        location.reload();
						return;
					}
					else
					{
						leHideScreenMask();
		                $('#continue').show();
						leOpenDialog('登入錯誤', json.message, leDialogType.MESSAGE);
					}
				}		
			});
		}
	});
	
	setTimeout(function()
	{
		$('#continue').on('click',function(event)
		{
			$(this).hide();
			$('#doSubmit').trigger('click');
		});
	}, 500);
});