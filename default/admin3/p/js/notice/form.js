$(function(){
	$("form").validate({
		submitHandler: function(form) {
			try {
				CKEDITOR.instances.notice_content.updateElement();
			} catch(e) {};
				
			$(form).json_ajaxSubmit(function(json){
				location.href = json.back_url;
			});
		}
	});
	
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
