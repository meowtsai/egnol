$(function(){

	$("form").validate({
		onfocusout: false,
		onkeyup: false,
		onclick: false,
		submitHandler: function(form) {
			$(form).json_ajaxSubmit(function(json){
				alert('成功!');
				location.reload();
			});
		}
	});
	
	$(".close_question").click(function(){	
		$.json_post($(this).attr("url"), function(){
			location.href = '/service/listing';
		});	
	});
});
