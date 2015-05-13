$(function(){
	$("form").validate({
		submitHandler: function(form) {
			CKEDITOR.instances.guide_content.updateElement();
			$(form).json_ajaxSubmit(function(json){
				location.href = json.back_url;
			});
		}
	});
});
