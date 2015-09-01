$(function(){

	$("#forgot_form").validate({
		onfocusout: false,
		onkeyup: false,
		onclick: false,
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
