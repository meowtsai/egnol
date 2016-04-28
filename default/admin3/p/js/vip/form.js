$(function(){
	
	$("form").validate({
		submitHandler: function(form) {
			$(form).json_ajaxSubmit(function(json){
				location.href = json.redirect_url;
			});
		}
	});
	
});
