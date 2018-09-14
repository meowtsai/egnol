$(function(){
	$(".del").click(function(evt){
		if (confirm("確定要刪除嗎?")) {
			$.json_post($(this).attr("url"), function(){
				location.href = $('#back_url').val();
			});
		}
		return false;
	});
	var date = $("#contact_date").val();
	$("#contact_date").datepicker({
		changeMonth: true,
		changeYear: true
	});
	$("#contact_date").datepicker( "option", "dateFormat", "yy-mm-dd");
	$("#contact_date").val(date);

	var o_date = $("#o_case_date").val();
	$("#o_case_date").datepicker({
		changeMonth: true,
		changeYear: true
	});
	$("#o_case_date").datepicker( "option", "dateFormat", "yy-mm-dd");
	$("#o_case_date").val(o_date);

	if ($("input[name=req_date]").length>0)
	{
		var r_date = $("input[name=req_date]").val();
		$("input[name=req_date]").datetimepicker({dateFormat: "yy-mm-dd", changeMonth: true, changeYear: true});

		$("input[name=req_date]").val(r_date);
	}

	$("#txt_caseCloseDate").datepicker({
		changeMonth: true,
		changeYear: true
	});
	$("#txt_caseCloseDate").datepicker( "option", "dateFormat", "yy-mm-dd");





	$("#reply_form").validate({
		submitHandler: function(form) {
			$(form).json_ajaxSubmit(function(json){
				//alert(json.message);
				//if (json.back_url) {
				if (json.status == 'success') {
					//location.href = location.href;
					location.href = json.redirect_url;
				}
			});
		}
	});

	$("#form_mediation").validate({
		submitHandler: function(form) {
			console.log("submit");
			$(form).json_ajaxSubmit(function(json){
				console.log("json",json);
				//alert(json.message);
				//location.href = json.redirect_url;
				if (json.status == 'success') {
					//location.href = location.href;
					location.href = json.redirect_url;
				}
			});
		}
	});

});

function open_modal(id)
{
  //$("#modal-alert").removeClass();
  //$("#modal-alert").hide();
  //$("#complaint_id").val(id);
  //$("#txt_comment").val("");
  //$("#modal-alert").text("");
	var case_id = $("#td_o_case_id").text();
	var appellant = $("#td_appellant").text();
  $("#caseCloseModalLabel").html(`案件編號[<b>${case_id}</b>] - 申訴人:<u>${appellant}</u>`);


}


function caseClose()
{
  let id = $("input[name=case_id]").val();
  let close_date = $("#txt_caseCloseDate").val();
	console.log(id);
	console.log(close_date);
	//return;
  let url = "../move_case/" + id;
	//let url = "http://test-payment.longeplay.com.tw/default/admin3/cpl_case/move_case/" +id;

  $.ajax({
    type: "POST",
    url: url,
		dataType: "json",
		data: "status=4&close_date=" + close_date ,
  }).done(function(result) {
    console.log( "Request done: " + JSON.stringify(result)  );
    //console.log(result.status);

    $('#caseCloseModal').modal('hide');
    if (result.status == 'success') {
      //$("#tr" + id).children(":nth-child(2)").text(comment);
      //$('#caseCloseModal').modal('hide');
			location.reload();
    }
    else {
    }

  })
  .fail(function( jqXHR, textStatus ) {
    console.log( "Request failed: " + textStatus );

  })
  .always(function() {
    //alert( "complete" );
    console.log("complete")
  });;
}
