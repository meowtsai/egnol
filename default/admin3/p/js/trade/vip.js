$(function(){
	
	$("input[name=start_date]").datetimepicker({dateFormat: "yy-mm-dd", changeMonth: true, changeYear: true});
	$("input[name=end_date]").datetimepicker({dateFormat: "yy-mm-dd", changeMonth: true, changeYear: true});
	$("input[name=billing_start_date]").datetimepicker({dateFormat: "yy-mm-dd", changeMonth: true, changeYear: true});
	$("input[name=billing_end_date]").datetimepicker({dateFormat: "yy-mm-dd", changeMonth: true, changeYear: true});
	
});