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
	$("input[name=priority]").change(function(){
		if ($(this).val() == "0") {
			$("input[name=start_time]").attr("disabled", true);
		}
		else $("input[name=start_time]").attr("disabled", false);
	});
	$("input[name=priority]:checked").change();

	$("select[name=bulletin_type]").change(function(){
		if ($("select[name=bulletin_type] option:selected").text() == '遊戲跑馬燈') {
			$('#choose_target_server').show();
		}
		else $('#choose_target_server').hide();
	}).change();

	$("#clickAll").click(function() {
		   if($("#clickAll").prop("checked"))
		   {
		     $("input[name='target[]']").each(function() {
		         $(this).prop("checked", true);
		     });
		   }
		   else
		   {
		     $("input[name='target[]']").each(function() {
		         $(this).prop("checked", false);
		     });
		   }
	});
});
