$(function(){
	$(".del").click(function(evt){
		if (confirm("確定要刪除嗎?")) {
			$.json_post($(this).attr("url"), function(){
				location.href = $('#back_url').val();
			});
		}
		return false;
	});
	$("#reply_form").validate({
		submitHandler: function(form) {
			$(form).json_ajaxSubmit(function(json){
				alert(json.message);
				if (json.status == 'success') {
					location.href = $('#back_url').val();
				}
			});
		}
	});

});
