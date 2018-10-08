$(function(){

	$("input[name=start_date]").datetimepicker({dateFormat: "yy-mm-dd", changeMonth: true, changeYear: true});
	$("input[name=end_date]").datetimepicker({dateFormat: "yy-mm-dd", changeMonth: true, changeYear: true});

	$("input[name=reply_start_date]").datetimepicker({dateFormat: "yy-mm-dd", changeMonth: true, changeYear: true});
	$("input[name=reply_end_date]").datetimepicker({dateFormat: "yy-mm-dd", changeMonth: true, changeYear: true});

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


	$("select[name=batch_batch]").change(function(e)
	{
			console.log($(this).val());
			var batch_id = $(this).val();
			var arr = [];
			$('input[type=checkbox]:checked').each(function(){
				console.log($(this).val());
				arr.push($(this).val());
			})

			if (!batch_id || arr.length===0){
				return;
			}

			let url = "./batch_add_to_batch";
		  $.ajax({
		    type: "POST",
		    url: url,
		    data: "ids=" + arr + "&batch_id=" + batch_id ,
		  }).done(function(result) {
		    console.log( "Request done: " + result );
		    let obj = JSON.parse(result);
		    if (obj.status == 'success') {
					console.log('parent',parent);
					parent.location.reload();
		    }
		    else {
					alert(obj.message);
					$("select[name=batch_batch]").children('[value=""]').attr('selected', true);
		    }
		  })
		  .fail(function( jqXHR, textStatus ) {
		    console.log( "Request failed: " + textStatus );
		  })
		  .always(function() {
		    console.log("complete")
		  });;
	});


});
