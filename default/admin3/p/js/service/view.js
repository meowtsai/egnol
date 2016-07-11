$(function(){

	$("#note_form").validate({
		submitHandler: function(form) {
			$(form).json_ajaxSubmit(function(json){
				alert(json.message);
				if (json.status == 'success') {
					location.href = location.href;
				}
			});
		}
	});
	
	$("#allocate_form").validate({
		submitHandler: function(form) {
			$(form).json_ajaxSubmit(function(json){
				alert(json.message);
				if (json.status == 'success') {
					location.href = location.href;
				}
			});
		}
	});
	
	$("#result_form").validate({
		submitHandler: function(form) {
			$(form).json_ajaxSubmit(function(json){
				alert(json.message);
				if (json.status == 'success') {
					location.href = location.href;
				}
			});
		}
	});
	
	$("#reply_form").validate({
		submitHandler: function(form) {
			$(form).json_ajaxSubmit(function(json){
				alert(json.message);
				if (json.status == 'success') {
					location.href = location.href;
				}
			});
		}
	});
	
	
    $("select[name=select_type]").change(function(e)
    {
       $("#type_form").submit();
    });
    
	$("#type_form").validate({
		submitHandler: function(form) {
			$(form).json_ajaxSubmit(function(json){
				alert(json.message);
				if (json.status == 'success') {
					location.href = location.href;
				}
			});
		}
	});
	
});
