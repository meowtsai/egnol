$(function(){

	$("form").validate({
		submitHandler: function(form) {
			$(form).json_ajaxSubmit(function(json){
				alert('成功!');
				location.href = location.href;
			});
		}
	});
	
	$(".close_question").click(function(){	
		$.json_post($(this).attr("url"), function(){
			location.href = '/service/listing';
		});	
	});	
	
});
