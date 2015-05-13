$(function(){
	
    $("form").validate({
		onfocusout: false,
		onkeyup: false,
		onclick: false,
		messages: {
			game: "尚未選擇遊戲",
			server: "尚未選擇伺服器"
		},
		showErrors: function(errorMap, errorList) {
		   var err = '';
		   $(errorList).each(function(i, v){
			   err += v.message + "\n";
		   });
		   if (err) alert(err);
		}				
	 });
    
	var server = $("select[name='server']");
	var server_pool = $("#server_pool");
	var game = $("select[name='game']");	
	game.on('change', function(){
		
		var arr = ['10', '50', '100', '300', '500', '1000', '3000', '5000', '10000', '20000'];		
		switch (game.val()) {
			case 'ry':
				arr = ['50', '500', '1000', '5000', '10000'];
				break
				
			case 'zj':
				arr = ['60', '150', '300', '660', '790', '1490', '3590', '5990', '9990', '14900', '29900'];
				break;
		}		
				
		$(".amount_block label").each(function() {
			if ($.inArray($(this).find("input").val(), arr) === -1) {
				$(this).hide();
			} else $(this).show();
		});
		$('.amount_block input').eq(3).click();		
		
		server.empty().append("<option value=''>--請選擇--</option>");
		if (typeof game.val() !== 'undefined' && game.val() !== '') {
			server_pool.find("option."+game.val()).clone().appendTo(server);
		}
		update_gain_tip();
	});
	game.trigger("change");
	
    $("input[name='price']").on("change", "", function(event){
   		update_gain_tip();
    });
		
});

function update_gain_tip() {
	if ($("select[name='game']").val() && $("input[name='price']:checked").val()) {
		var game = $("select[name='game'] option:selected");
		$("#gain_tip").text("您將可以獲得 "+$("input[name='price']:checked").val()*game.attr("rate")+game.attr("goldname"));
	}
	else $("#gain_tip").text('');
}