$(function(){
	$("form").validate({
		submitHandler: function(form) {
			CKEDITOR.instances.bulletin_content.updateElement();
			$(form).json_ajaxSubmit(function(json){
				location.href = json.back_url;
			});
		}
	});
	$("input[name=publish_date]").datetimepicker({dateFormat: "yy-mm-dd", changeMonth: true, changeYear: true});
	$("input[name=close_date]").datetimepicker({dateFormat: "yy-mm-dd", changeMonth: true, changeYear: true});
	$("input[name=priority]").change(function(){
		if ($(this).val() == "0") {			
			$("input[name=publish_date]").attr("disabled", true); 
		}	
		else $("input[name=publish_date]").attr("disabled", false);
	});
	$("input[name=priority]:checked").change();
	
	$("select[name=category_id]").change(function(){
		if ($("select[name=category_id] option:selected").text() == '遊戲跑馬燈') {
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
