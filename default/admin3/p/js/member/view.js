$(function()
{
	var validation_option = {
		onfocusout: false,
		onkeyup: false,
		onclick: false,
		messages: {
			game: "尚未選擇遊戲",
			server: "尚未選擇伺服器",
			character: "尚未選擇角色"
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
				leOpenDialog('', err, leDialogType.MESSAGE);
			}
		}
	 }

    $("#choose_form").validate(validation_option);

	var server_pool = $("#server_pool");
	var game_selector = $("select[name='game']");
	game_selector.on('change', function()
	{
		var server = $(this).parents("form").find("select[name='server']");
		server.empty().append("<option value=''>--請選擇伺服器--</option>");
		if (typeof $(this).val() !== 'undefined' && $(this).val() !== '')
		{
			server_pool.find("option."+$(this).val()).clone().appendTo(server);
		}

		var character = $(this).parents("form").find("select[name='character']");
		character.empty().append("<option value=''>--請選擇角色--</option>");

        var option = $("option:selected", this);
	});
	game_selector.trigger("change");

	var character_pool = $("#character_pool");
	var server_selector = $("select[name='server']");
	server_selector.on('change', function()
	{
		var character = $(this).parents("form").find("select[name='character']");
		character.empty().append("<option value=''>--請選擇角色--</option>");
		if (typeof $(this).val() !== 'undefined' && $(this).val() !== '')
		{
			character_pool.find("option."+$(this).val()).clone().appendTo(character);
		}
	});
    server_selector.trigger("change");
    
    $("select[name='character']").on('change', function() {
        $("#choose_form").submit();
    });

	$("#note_form").validate({
		submitHandler: function(form) {
			$(form).json_ajaxSubmit(function(json){
				alert(json.message);
				if (json.status == 'success') {
					location.href = location.href;
				}
			});
		}
	});
});