$(document).ready(function()
{
	$("#login_form").validate({
        focusInvalid: false,
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
		                $('.login-button img').show();
						leOpenDialog('登入錯誤', json.message, leDialogType.MESSAGE);
					}
				}
			});
		}
	});
	
    $('.login-button img').on('click', function(event)
	{
		$(this).hide();
		$('#doSubmit').trigger('click');
    });

	var quickBtn = $('#login-btn-quick');
    quickBtn.on('click', { deviceId: quickBtn.attr('parm1'), gameId: quickBtn.attr('parm2') }, function(event)
	{
		location.href='/api2/ui_quick_login?deviceid=' + event.data.deviceId + '&site=' + event.data.gameId;
	});
	
    $('#login-btn-facebook').on('click', function(event)
	{
		if(typeof LongeAPI != 'undefined')
		{
			LongeAPI.onFacebookLogin();
		}
		else
		{
			startFacebookLogin();
			
//			window.location = "ios://facebooklogin-_-";
			
			var iframe = document.createElement("IFRAME");
			iframe.setAttribute("src", "ios://facebooklogin-_-");
			document.documentElement.appendChild(iframe);
			iframe.parentNode.removeChild(iframe);
			iframe = null;
		}
	});

	var googleBtn = $('#login-btn-google');
    googleBtn.on('click', { webVersionLogin: googleBtn.attr('parm') }, function(event)
	{
		if(typeof LongeAPI != 'undefined')
		{
			LongeAPI.onGoogleLogin();
		}
		else
		{
			location.href = event.data.webVersionLogin;
			/*
			var iframe = document.createElement("IFRAME");
			iframe.setAttribute("src", "ios://googlelogin-_-\"");
			document.documentElement.appendChild(iframe);
			iframe.parentNode.removeChild(iframe);
			iframe = null;
			*/
		}
	});
});

function startFacebookLogin()
{
}

function onFacebookLoginSuccess(appId, uid)
{
    if(uid == "")
    {
		leOpenDialog('登入錯誤', "Facebook 回傳資料錯誤!", leDialogType.MESSAGE);
        return;
    }
    
	$.post("/api2/check_facebook_uid", { uid_list: uid }, function(result)
	{
		if(result == '0')
		{
			var uids = uid.split(',');
			location.href='/api2/ui_mobile_facebook_login?site=' + appId + '&uid=' + uids[0];
		}
		else
			location.href='/api2/ui_mobile_facebook_login?site=' + appId + '&uid=' + result;
	});
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
		/*
		var iframe = document.createElement("IFRAME");
		iframe.setAttribute("src", "ios://googlelogin-_-\"");
		document.documentElement.appendChild(iframe);
		iframe.parentNode.removeChild(iframe);
		iframe = null;
		*/
	}
}

function onGoogleLoginSuccess(appId, uid, email)
{
	location.href='/api2/ui_mobile_google_login?site=' + appId + '&uid=' + uid + '&email=' + email;
}

function onGoogleLoginFail(errorCode, param)
{
	leOpenDialog('登入錯誤', "Google 登入錯誤: " + param, leDialogType.MESSAGE);
}
