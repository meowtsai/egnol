$(function(){
	
	$("input[name=start_date]").datetimepicker({dateFormat: "yy-mm-dd", changeMonth: true, changeYear: true});
	$("input[name=end_date]").datetimepicker({dateFormat: "yy-mm-dd", changeMonth: true, changeYear: true});
	
    $("select[name=new_type]").change(function(e)
    {
        $("#update_question_id").val($(this).attr("question_id"));
        $("#select_type").val($(this).val());
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