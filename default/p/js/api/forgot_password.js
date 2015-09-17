$(function(){

	$("#forgot_form").validate({
		submitHandler: function(form) {
			$(form).json_ajaxSubmit(function(json){
				alert(json.message);
				if (json.status == 'success') {
					location.href = '/';
				} 				
			});
		}
	});
	
});
