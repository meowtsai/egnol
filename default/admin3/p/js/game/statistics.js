$(function(){
	$("input[name=start_date].date").datepicker({dateFormat: "yy-mm-dd", changeMonth: true, changeYear: true});
	$("input[name=end_date].date").datepicker({dateFormat: "yy-mm-dd", changeMonth: true, changeYear: true});

	//$("input[name=end_date]").datetimepicker({dateFormat: "yy-mm-dd", changeMonth: true, changeYear: true});

	$("input[name=ad_channel]").autocomplete('/admin3/ajax/ad_channel_autocomplete');
});

function open_modal(id)
{

  $("#modal-alert").removeClass();
  $("#modal-alert").hide();
  $("#complaint_id").val(id);
  $("input[name=end_date].date").datepicker({dateFormat: "yy-mm-dd", changeMonth: true, changeYear: true});
  $("#modal-alert").text("");
  $("#commentModalLabel").text($("#tr" + id).children(":first").text());

}
