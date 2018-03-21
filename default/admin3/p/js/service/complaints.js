$(function(){
	$("input[name=start_date]").datetimepicker({dateFormat: "yy-mm-dd", changeMonth: true, changeYear: true});
	$("input[name=end_date]").datetimepicker({dateFormat: "yy-mm-dd", changeMonth: true, changeYear: true});

  get_ranking_report(1);
});

function mark_as_read(id)
{
  //alert('id=' + id);
  let url = "./complaint_mark_read";
  $.ajax({
    type: "POST",
    url: url,
    data: "id=" + id,
  }).done(function(result) {
    //alert( "success" );
    //{"status":"success","message":"success"}
    //console.log( "Request done: " + result );
    //$("#tr" + id).css("background-color","silver");
    //$("#tr" + id).hide();
    //let btn = $("#tr" + id).find(".btn-secondary");
    //btn.hide();
    $("#tr" + id).css("background-color","silver");
    $("#tr" + id).hide();
    if (result.status == 'success') {
      $("#tr" + id).css("background-color","silver");
      $("#tr" + id).hide();
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

function complaint_batch_mark(role_id)
{
  //alert('role_id=' + role_id);
  let url = "./complaint_batch_mark";
  $.ajax({
    type: "POST",
    url: url,
    data: "role_id=" + role_id,
  }).done(function(result) {
    //alert( "success" );
    //{"status":"success","message":"success"}
    console.log( "Request done: " + result );
    //$("#tr" + id).css("background-color","silver");
    //$("#tr" + id).hide();
    //let btn = $("#tr" + id).find(".btn-secondary");
    //btn.hide();
		let obj = JSON.parse(result);
    if (obj.status == 'success') {
			console.log('parent',parent);
			parent.location.reload();
    }
    else {
			alert('錯誤發生');
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

function open_modal(id)
{
  $("#modal-alert").removeClass();
  $("#modal-alert").hide();
  $("#complaint_id").val(id);
  $("#txt_comment").val("");
  $("#modal-alert").text("");
  $("#commentModalLabel").text($("#tr" + id).children(":first").text());

}

function get_ranking_report(how_many_days)
{
  //console.log($(this));
  //$(this).addClass("active");
  let listItems = $("#ranking_tab li");
  //console.log("listItems" + listItems.length);
  listItems.each(function(idx, li) {
      //console.log(li.innerText);
      if (li.innerText.startsWith(how_many_days))
      {
        li.className = "active";
      }
      else {
        li.className = "";
      }

        // and the rest of your code

    });

  let url = "./complaints_ranking";
  $.ajax({
    type: "POST",
    url: url,
    data: "period=" + how_many_days,
  }).done(function(result) {
    let obj = JSON.parse(result);
    //console.log(obj.status);
    //console.log(obj.message);
    $("#ranking_table tbody tr").remove();
    $.each(obj.message, function( index,item ) {
      var trObj = document.createElement("TR");
      trObj.innerHTML = "<td>"+ (index + 1) +"</td>";
      trObj.innerHTML += "<td>"+ item.server_id +"</td>";
      trObj.innerHTML += "<td>"+ item.flagged_player_name +
        "( <a href='complaints?character_name="+ item.flagged_player_name +"&character_id="+ item.flagged_player_char_id +"&action=查詢'>" + item.flagged_player_char_id + "</a>)</td>";
      trObj.innerHTML += "<td>"+ item.cnt +"</td>";
			trObj.innerHTML += "<td><button onclick=\"complaint_batch_mark('"+ item.flagged_player_char_id  +"')\">批次標示為永久停權或禁言</button></td>";

      $("#ranking_table tbody").append(trObj);

      });


  })
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
