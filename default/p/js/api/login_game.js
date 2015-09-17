$(function()
{
	$("#login_form").validate({
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
				leOpenDialog('µn¤J¿ù»~', err, leDialogType.MESSAGE);
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
                        location.reload();
						return;
					}
					else
					{
						leOpenDialog('µn¤J¿ù»~', json.message, leDialogType.MESSAGE);
					}
				}		
			});
		}
	});
});