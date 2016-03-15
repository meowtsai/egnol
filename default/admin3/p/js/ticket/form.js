$(function(){
	
	$("form").validate({
		submitHandler: function(form) {
			$(form).json_ajaxSubmit(function(json){
				location.href = '/admin3/ticket/view/'+json.id;
			});
		}
	});
	
});
