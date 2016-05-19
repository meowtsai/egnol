$(function(){
	$("#ticket_form").validate({
		submitHandler: function(form) {
			$(form).json_ajaxSubmit(function(json){
				alert(json.message);
				if (json.status == 'success') {
					location.href = json.redirect_url;
				}
			});
		}
	});
	
	$("#cancel_ticket_form").validate({
		submitHandler: function(form) {
			$(form).json_ajaxSubmit(function(json){
				alert(json.message);
				if (json.status == 'success') {
					location.href = json.redirect_url;
				}
			});
		}
	});
    
	$("#new_ticket_form").validate({
		submitHandler: function(form) {
			$(form).json_ajaxSubmit(function(json){
				alert(json.message);
				if (json.status == 'success') {
                    location.reload();
				}
			});
		}
	});
	$("input[name=billing_time]").datetimepicker({dateFormat: "yy-mm-dd", changeMonth: true, changeYear: true});
});
