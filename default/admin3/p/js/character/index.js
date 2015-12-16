$(function(){
	$("input[name=start_date]").datetimepicker({dateFormat: "yy-mm-dd", changeMonth: true, changeYear: true});
	$("input[name=end_date]").datetimepicker({dateFormat: "yy-mm-dd", changeMonth: true, changeYear: true});	
	
	var server = $("select[name='server']"), server_pool = $("#server_pool"), game = $("select[name='serverID']");
	server.change_game = function() {
		server.empty().append("<option value=''>--</option>");
		if (game.val() !== '') {
			server_pool.find("option."+game.val()).clone().appendTo(server);
		}
	};
	server.change_game();	
	game.on('change', server.change_game);
});