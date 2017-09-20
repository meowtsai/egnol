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
				required: "`電子信箱`與`行動電話`至少需填寫其中之一",
				email: "請填寫正確的電子信箱位址"
			},
			mobile: {
				required: ""
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
				required: function(element) { return ($("#mobile").val() == '' && $("#partner_uid").val() == ''); }
			},
			mobile: {
				required: function(element) { return ($("#email").val() == '' && $("#partner_uid").val() == ''); }
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
			$(form).ajaxSubmit(
			{
				dataType: 'json',
				success: function(json)
				{
					if (json.status == 'success')
					{
		                leOpenDialog('玩家提問', '提問成功！', leDialogType.MESSAGE, function()
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
