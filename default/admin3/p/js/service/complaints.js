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

function open_modal(id)
{
  $("#complaint_id").val(id);
  $("#txt_comment").val("");
  $("#commentModalLabel").text($("#tr" + id).children(":first").text());

}

function complaint_add_comment()
{
  let id = $("#complaint_id").val();
  let comment = $("#txt_comment").val();
  let url = "./complaint_add_comment";
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
