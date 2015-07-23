$(function()
{
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
			pwd: {
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
			   err += v.message + "<br/>";
		   });
		   if (err)
		   {
				leOpenDialog('註冊錯誤', err, leDialogType.MESSAGE);
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
						// 回傳資料(建完帳號會自動完成登入, 可直接回傳登入結果)
						//
						//
						//
						return;
					}					
					else
					{
						leOpenDialog('註冊錯誤', json.message, leDialogType.MESSAGE);
					}
				}
			});
		}
	});
});

