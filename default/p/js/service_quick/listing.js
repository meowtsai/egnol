$(function()
{
	$("#listing_form").validate({
		onfocusout: false,
		onkeyup: false,
		onclick: false,
		messages: {
			email: {
				required: "`電子信箱`與`行動電話`至少需填寫其中之一",
				email: "請填寫正確的電子信箱位址"
			},
			mobile: {
				required: ""
			},
			check_id: "尚未填寫客服代碼",
		},
		rules:
		{
			email: {
				required: function(element) { return ($("#mobile").val() == ''); }
			},
			mobile: {
				required: function(element) { return ($("#email").val() == ''); }
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
				leOpenDialog('輸入錯誤', err, leDialogType.MESSAGE);
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
		                location.href = '/service_quick/view/' + json.id + '?site=' + json.site;
					}
					else
					{
						leOpenDialog('輸入錯誤', json.message, leDialogType.MESSAGE);
					}
				}
			});
		}
	});
    
    $("#listing_reset_form").validate({
		onfocusout: false,
		onkeyup: false,
		onclick: false,
		messages: {},
		rules: {},
		showErrors: function(errorMap, errorList)
		{
		   var err = '';
		   $(errorList).each(function(i, v)
		   {
			   err += v.message + "<br/>";
		   });
		   if (err)
		   {
				leOpenDialog('輸入錯誤', err, leDialogType.MESSAGE);
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
		                location.href = '/service_quick/listing?site=' + json.site;
					}
					else
					{
						leOpenDialog('輸入錯誤', json.message, leDialogType.MESSAGE);
					}
				}
			});
		}
	});
});
