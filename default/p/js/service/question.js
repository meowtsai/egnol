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

		var character = $(this).parents("form").find("select[name='character_name']");
		character.empty().append("<option value=''>--請選擇角色--</option>");
	};
	server.change_game(game.val());

	game.on('change', function()
	{
		server.change_game(game.val());
	});

	var character_pool = $("#character_pool");
	$("select[name='server']").on('change', function()
	{
		var character = $(this).parents("form").find("select[name='character_name']");
		character.empty().append("<option value=''>--請選擇角色--</option>");
		if (typeof $(this).val() !== 'undefined' && $(this).val() !== '')
		{
			character_pool.find("option."+$(this).val()).clone().appendTo(character);
		}
	});

	$("#question_form").validate({
		onfocusout: false,
		onkeyup: false,
		onclick: false,
		messages: {
			game: "尚未選擇遊戲",
			server: "尚未選擇伺服器",
			character_name: "尚未選擇角色",
			question_type: "尚未選擇問題類型",
			content: {
				required: "尚未填寫問題描述",
				minlength: "問題描述最少需填寫 5 個字",
				maxlength: "問題描述最多填寫 500 個字"
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
		                leOpenDialog('玩家提問', '提問成功！', leDialogType.MESSAGE, function()
						{
		                    location.href = '/service/listing?site=' + json.site;
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
