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
		var site = $(this).attr("site");
		$.json_post($(this).attr("url"), function(){
			location.href = '/service_quick/listing?site=' + site;
		});	
	});
});
