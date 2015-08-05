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
		submitHandler: function(form)
		{
			$(form).ajaxSubmit(
			{
				dataType: 'json',
				success: function(json)
				{
	                leOpenDialog('玩家提問', '提問成功！', leDialogType.MESSAGE, function()
					{
	                    location.href = '/service/listing';
					});
				}
			});
		}
	});
});
