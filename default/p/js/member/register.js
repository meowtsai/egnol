$(function(){
	
	$("#register_form").validate({
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
			},
/*			account: {
				required: "`帳號`必填",
				minlength: "`帳號`最少6碼",
				maxlength: "`帳號`最多18碼",
			},
*/			pwd: {
				required: "`密碼`必填",
				minlength: "`密碼`最少6碼",
				maxlength: "`密碼`最多18碼",
			},
			pwd2: { 
				required: "`確認密碼`必填",
				equalTo: "兩次密碼不相同",
			},
			captcha: {
				required: "`認證碼`必填",
				minlength: "`認證碼`應為4碼",
				maxlength: "`認證碼`應為4碼",
			},
			chk: "請詳閱會員條款並同意",
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
			   err += v.message + "\n";
		   });
		   if (err)
		   {
			   if (typeof window.CoozSDK == "undefined")
			   {
				   alert(err);
				}
				else
				{
					window.CoozSDK.showMsg(err);
				}
		   }
		   //this.defaultShowErrors();
		},
		submitHandler: function(form)
		{
			$(form).ajaxSubmit({
				dataType: 'json',
				success: function(json)
				{
					if (typeof window.CoozSDK == "undefined")
					{
						alert(json.message)
					}
					else
					{
						window.CoozSDK.showMsg(json.message);
					}					
					if (json.status == 'success')
					{
						if ($('#redirect_url').val()) location.href = $('#redirect_url').val();						
						else if (json.site == 'long_e') location.href = '/';
						else location.href = '/play_game/'+json.site;
						return;
					}					
				}		
			});
		}
	});
});

