$(document).ready(function()
{
	$("#login_form").validate({
		onfocusout: false,
		onkeyup: false,
		onclick: false,
		messages:
		{
			account: {
				required: "`電子信箱`或`行動電話`必填"
			},
			pwd: {
				required: "`密碼`尚未填寫",
				minlength: "`密碼`最少6碼",
				maxlength: "`密碼`最多18碼",
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
		        $('.login-button img').show();
				leOpenDialog('登入錯誤', err, leDialogType.MESSAGE);
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
		                $('.login-button img').show();
						leOpenDialog('登入錯誤', json.message, leDialogType.MESSAGE);
					}
				}		
			});
		}
	});
	
    $('.login-button img').on('click',function(event){
		$(this).hide();
		$('#doSubmit').trigger('click');
    });
});

function OnQuickLogin(deviceId, gameId)
{
    location.href='/api2/ui_quick_login?deviceid=' + deviceId + '&site=' + gameId;
}

function OnClickFacebookLogin()
{
	if(typeof LongeAPI != 'undefined')
	{
		LongeAPI.onFacebookLogin();
	}
	else
	{
		//window.location = "ios://facebooklogin-_-\"";
		var iframe = document.createElement("IFRAME");
		iframe.setAttribute("src", "ios://facebooklogin-_-\"");
		document.documentElement.appendChild(iframe);
		iframe.parentNode.removeChild(iframe);
		iframe = null;
	}
}

function onFacebookLoginSuccess(uid, email, accessToken)
{
	location.href='/api2/ui_facebook_login?uid=' + uid + '&email=' + email + '&token=' + accessToken;
}

function onFacebookLoginFail(errorCode, param1, param2)
{
	switch(errorCode)
	{
	case -1:
		leOpenDialog('登入錯誤', "Facebook 登入錯誤: " + param1 + ", " + param2, leDialogType.MESSAGE);
		break;
	case -2:
		leOpenDialog('登入錯誤', "Facebook 回傳資料錯誤!", leDialogType.MESSAGE);
		break;
	case -3:
		leOpenDialog('登入錯誤', "Facebook 異常: " + param1, leDialogType.MESSAGE);
		break;
	}
}

function OnClickGoogleLogin(webVersionLogin)
{
	if(typeof LongeAPI != 'undefined')
	{
		LongeAPI.onGoogleLogin();
	}
	else
	{
		location.href = webVersionLogin;
	}
}

function onGoogleLoginSuccess(uid, email, accessToken)
{
	location.href='/api2/ui_google_login?uid=' + uid + '&email=' + email + '&token=' + accessToken;
}

function onGoogleLoginFail(errorCode, param)
{
	leOpenDialog('登入錯誤', "Google 登入錯誤: " + param, leDialogType.MESSAGE);
}
