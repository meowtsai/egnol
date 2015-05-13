$(function(){
	$(".del").click(function(){
		var item = $(this).parents("tr:first");
		
		if (confirm("確定要刪除嗎?")) {
			$.json_post($(this).attr("url"), function(){
				item.remove();
			});	
		}
	});
});