$(function(){
	$("form").validate({
		submitHandler: function(form) {
			$(form).json_ajaxSubmit(function(json){
				location.href = json.back_url;
			});
		}
	});
	$("input[name=start_time]").datetimepicker({dateFormat: "yy-mm-dd", changeMonth: true, changeYear: true});
	$("input[name=end_time]").datetimepicker({dateFormat: "yy-mm-dd", changeMonth: true, changeYear: true});
});
