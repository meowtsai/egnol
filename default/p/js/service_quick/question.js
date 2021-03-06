$(function()
{
	var server = $("select[name='server']");
	var server_pool = $("#server_pool");
	var game = $("select[name='game']");
	server.change_game = function(game)
	{
		$(this).empty().append("<option value=''>--請選擇--</option>");
		if (typeof game !== 'undefined' && game !== '')
		{
			server_pool.find("option."+game).clone().appendTo(server);
			if (server_pool.find("option."+game).length==1)
			{
				server[0].selectedIndex = 1;
			}

		}
	};
	server.change_game(game.val());

	game.on('change', function()
	{
		server.change_game(game.val());
	});

	$("#question_form").validate({
		onfocusout: false,
		onkeyup: false,
		onclick: false,
		messages: {
			email: {
				required: "`電子信箱`必填",
				email: "請填寫正確的電子信箱位址"
			},
			mobile: {
				required: "`行動電話`必填"
			},
			game: "尚未選擇遊戲",
			character_name: "尚未填寫角色",
			question_type: "尚未選擇問題類型",
			content: {
				required: "尚未填寫問題描述",
				minlength: "問題描述最少需填寫 5 個字",
				maxlength: "問題描述最多填寫 500 個字"
			},
		},
		rules:
		{
			email: {
				required: function(element) { return ($("#partner_uid").val() == '' || $("#partner_uid").val() ==undefined ); }
			},
			mobile: {
				required: function(element) { return ($("#partner_uid").val() == '' || $("#partner_uid").val() ==undefined ); }
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
				leOpenDialog('提問錯誤', err, leDialogType.MESSAGE);
			}
		},
		submitHandler: function(form)
		{
            $.blockUI({ message: '<h1><img src="/p/image/loading.gif" /> 飛鴿傳書中...</h1>' });
			$(form).ajaxSubmit(
			{
				dataType: 'json',
				success: function(json)
				{
                    $.unblockUI();
					if (json.status == 'success')
					{
		                leOpenDialog('玩家提問', json.message, leDialogType.MESSAGE, function()
						{
		                    location.href = '/service_quick/listing?site=' + json.site;
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
