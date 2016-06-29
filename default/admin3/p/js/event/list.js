$(function(){
	$(".del").click(function(){	
		if (confirm("確定要刪除嗎?")) {
			$.json_post($(this).attr("url"), function(){
				location.reload();
			});	
		}
	});
    
	$(".toggle").click(function(){	
        $.json_post($(this).attr("url"), function(){
            location.reload();
        });	
	});
});