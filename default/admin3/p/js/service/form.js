$(function(){

	var server = $("select[name='server']");
	var server_pool = $("#server_pool");
	var game = $("select[name='game']");
	server.change_game = function(game) {
		$(this).empty().append("<option value=''>--請選擇--</option>");
		if (typeof game !== 'undefined' && game !== '') {
			server_pool.find("option."+game).clone().appendTo(server);
		}
	};
	server.change_game(game.val());
	
	game.on('change', function(){
		server.change_game(game.val());
	});
	
	$("form").validate({
		submitHandler: function(form) {
			$(form).json_ajaxSubmit(function(json){
				location.href = '/service/view/'+json.id;
			});
		}
	});
	
});
