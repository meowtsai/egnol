$(function(){
	$("#end_date").datepicker({
		changeMonth: true,
    changeYear: true
	});
	$( "#end_date" ).datepicker( "option", "dateFormat", "yy-mm-dd");
});

function open_modal(game_id,role_id)
{
  $("#modal-alert").removeClass();
  $("#modal-alert").hide();
  $("#role_id").val(role_id);
	$("#game_id").val(game_id);
	console.log($("#tr" + role_id).children(":nth-child(14)").text().trim());

	$("#end_date").val($("#tr" + role_id).children(":nth-child(14)").text().trim());

  $("#modal-alert").text("");
  $("#commentModalLabel").text("設定角色 " + role_id + "的最後登入日期");

	if ($("#end_date").val() !=="")
	{
		$('#commentModal').find(".btn-danger").show();
	}
	else {
		$('#commentModal').find(".btn-danger").hide();
	}

}

function confirm_lastlogin(opt)
{
	let role_id = $("#role_id").val();
	let game_id = $("#game_id").val();
	let last_login = $("#end_date").val();
	console.log(game_id,role_id,last_login,opt);
	if (opt === 'reset')
	{
		last_login = "";
	}
	if (!last_login && opt !== 'reset')
	{
		$("#modal-alert").removeClass();
    $("#modal-alert").addClass( "alert alert-danger" );
    $("#modal-alert").text("請輸入最後登入日期");
    $("#modal-alert").show();
    return;
	}
	let url = "./whale_users_set_lastlogin";
  $("#modal-alert").removeClass();
  $("#modal-alert").hide();
  $.ajax({
    type: "POST",
    url: url,
    data: "game_id=" + game_id + "&role_id=" + role_id +"&last_login=" + last_login +"&opt=" + opt  ,
  }).done(function(result) {
		console.log( result );
		var resultObj = JSON.parse(result);
		//console.log( "Request done: " + resultObj.status );
		if (resultObj.status == 'success') {
      $("#tr" + role_id).children(":nth-child(14)").text(last_login);
      $('#commentModal').modal('hide');
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


function complaint_add_comment()
{
  let id = $("#complaint_id").val();
  let comment = $("#txt_comment").val();
  if (!comment)
  {
    $("#modal-alert").removeClass();
    $("#modal-alert").addClass( "alert alert-danger" );
    $("#modal-alert").text("請輸入備註");
    $("#modal-alert").show();
    return;
  }
  let url = "./complaint_add_comment";
  $("#modal-alert").removeClass();
  $("#modal-alert").hide();
  $.ajax({
    type: "POST",
    url: url,
    data: "id=" + id+"&comment=" + comment ,
  }).done(function(result) {
    //alert( "success" );
    //{"status":"success","message":"success"}
    console.log( "Request done: " + result );
    //$("#tr" + id).css("background-color","silver");
    //$("#tr" + id).hide();
    //let btn = $("#tr" + id).find(".btn-secondary");
    //btn.hide();
    //$("#tr" + id).children(":nth-child(2)").text(comment);
    //$("#tr" + id).children(":nth-child(2)").text(comment);
    //$('#commentModal').modal('hide');
    console.log(result.status);

    $("#tr" + id).children(":nth-child(2)").text(comment);

    $('#commentModal').modal('hide');
    if (result.status == 'success') {
      $("#tr" + id).children(":nth-child(2)").text(comment);
      $('#commentModal').modal('hide');
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
