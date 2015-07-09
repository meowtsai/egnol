$(function(){
	
	$("input[name=start_date]").datetimepicker({dateFormat: "yy-mm-dd", changeMonth: true, changeYear: true});
	$("input[name=end_date]").datetimepicker({dateFormat: "yy-mm-dd", changeMonth: true, changeYear: true});
	
	var server = $("select[name='server']");
	var server_pool = $("#server_pool");
	var game = $("select[name='game']");
	server.change_game = function(game) {
		$(this).empty().append("<option value=''>--</option>");
		if (typeof game !== 'undefined' && game !== '') {
			server_pool.find("option."+game).clone().appendTo(server);
		}
	};
	server.change_game(game.val());
	
	game.on('change', function(){
		server.change_game(game.val());
	});
	
});