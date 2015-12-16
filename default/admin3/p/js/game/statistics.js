$(function(){
	$("input[name=start_date].date").datepicker({dateFormat: "yy-mm-dd", changeMonth: true, changeYear: true});
	$("input[name=end_date].date").datepicker({dateFormat: "yy-mm-dd", changeMonth: true, changeYear: true});
	
	//$("input[name=end_date]").datetimepicker({dateFormat: "yy-mm-dd", changeMonth: true, changeYear: true});
	
	$("input[name=ad_channel]").autocomplete('/admin3/ajax/ad_channel_autocomplete');
});