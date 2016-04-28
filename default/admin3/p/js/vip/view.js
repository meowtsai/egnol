$(function(){
	$("#ticket_form").validate({
		submitHandler: function(form) {
			$(form).json_ajaxSubmit(function(json){
				alert(json.message);
				if (json.status == 'success') {
					location.href = location.href;
				}
			});
		}
	});
	
	$("#cancel_ticket_form").validate({
		submitHandler: function(form) {
			$(form).json_ajaxSubmit(function(json){
				alert(json.message);
				if (json.status == 'success') {
					location.href = location.href;
				}
			});
		}
	});
    
	$("#new_ticket_form").validate({
		submitHandler: function(form) {
			$(form).json_ajaxSubmit(function(json){
				alert(json.message);
				if (json.status == 'success') {
					location.href = location.href;
				}
			});
		}
	});
	$("input[name=billing_time]").datetimepicker({dateFormat: "yy-mm-dd", changeMonth: true, changeYear: true});
});
