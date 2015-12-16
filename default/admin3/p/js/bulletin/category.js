$(function(){
	$(".del").click(function(evt){	
		if ($(this).attr("cnt") > 0) {
			alert('已存在文章，不能刪除');
		}
		else
		{
			if (confirm("確定要刪除嗎?")) {
				$.json_post($(this).attr("url"), function(){
					location.reload();
				});	
			}
		}
		return false;
	});
});