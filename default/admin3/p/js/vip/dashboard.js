
var page_count = 1;
let role_id = "";
let game_id =  "";
editMode = () => {

let mode = $("#cmd_edit").text();
//console.log(mode);
  switch (mode) {
    case "編輯":
      $("#lbl_line_id").hide();
      $("#lbl_line_date").hide();
      $("input[name=line_id]").show();
      $("input[name=line_date]").show();
      $("#line_date").val($("#lbl_line_date").text());
      $("#cmdAction").show();
      $("#cmd_edit").text("取消編輯");
      break;
    default:
      $("#lbl_line_id").text($("input[name=line_id]").val());
      $("#lbl_line_date").text($("input[name=line_date]").val());
      $("#lbl_line_date").show();
      $("#lbl_line_id").show();
      $("#lbl_line_date").show();
      $("input[name=line_id]").hide();
      $("input[name=line_date]").hide();
      $("#cmdAction").hide();
      $("#cmd_edit").text("編輯");

      break;

  }


}




$(function() {
  $("#end_date").datepicker({
    changeMonth: true,
    changeYear: true
  });
  $( "#end_date" ).datepicker( "option", "dateFormat", "yy-mm-dd");
  $("#start_date").datepicker({
    changeMonth: true,
    changeYear: true
  });
  $( "#start_date" ).datepicker( "option", "dateFormat", "yy-mm-dd");


  $("#end_date2").datepicker({
    changeMonth: true,
    changeYear: true
  });
  $( "#end_date2" ).datepicker( "option", "dateFormat", "yy-mm-dd");
  $("#start_date2").datepicker({
    changeMonth: true,
    changeYear: true
  });
  $( "#start_date2" ).datepicker( "option", "dateFormat", "yy-mm-dd");

  editMode();
  role_id = $("input[name=role_id]").val();
  game_id =  $("input[name=game_id]").val();
  ////http://test-payment.longeplay.com.tw/default/admin3/vip/vip_request_list/h35naxx1hmt/390709/1/2
  get_vip_requests_log('1',1);
  get_vip_requests_log('2',1);
  $("#line_date").datepicker({
		changeMonth: true,
    changeYear: true
	});
	$("#line_date").datepicker( "option", "dateFormat", "yy-mm-dd");


  $('.alert').hide();
  $('.alert').html('');


});




function update_vip_info()
{
  let line_id = $("input[name=line_id]").val();
  let line_date = $("input[name=line_date]").val();
  let game_id =  $("input[name=game_id]").val();
  let vip_uid =  $("input[name=vip_uid]").val();


  if (!line_id && !line_date)
  {
    $("#div-alert").removeClass();
    $("#div-alert").addClass( "alert alert-danger" );
    $("#div-alert").text("請輸入至少一個欄位");
    $("#div-alert").show();
    return;
  }
  let url = "../../../vip/update_vip_info";
  $.ajax({
    type: "POST",
    url: url,
    data: "game_id=" + game_id+"&vip_uid=" + vip_uid + "&line_id=" + line_id+"&line_date=" + line_date ,
  }).done(function(result) {
    //console.log("post result:","game_id=" + game_id+"&vip_uid=" + vip_uid + "&line_id=" + line_id+"&line_date=" + line_date );
    //console.log("post result:",result);
    let resultObj = JSON.parse(result);
    if (resultObj.status == 'success') {

      editMode('default');
    }
    else {
      alert(resultObj.message)

    }

  })
  .fail(function( jqXHR, textStatus ) {
    console.log( "Request failed: " + textStatus );
  })
  .always(function() {
    console.log("complete")
  });;
}

function add_vip_request(service_type) {


  // role_id = $("input[name=role_id]").val();
  //let game_id =  $("input[name=game_id]").val();

  var tableElem = null;
  var pageElem = null;
  var addOptionElem = null;
  var addNoteElem = null;
  var alertDiv = null;

  switch (service_type) {
    case '1':
      tableElem = $("#request-log");
      addOptionElem = $("#serviceOptionSelector");
      addNoteElem = $("input[name=inputServiceInfo]");
      alertDiv = $("#divAdd");
      break;
    case '2':
      tableElem = $("#feedback-log");
      addOptionElem = $("#serviceFeedbackOptionSelector");
      addNoteElem = $("input[name=inputServiceFeedbackInfo]");
      alertDiv = $("#divFeedbackAdd");
      break;
    default:
      tableElem = $("#request-log");
      addOptionElem = $("#serviceOptionSelector");
      addNoteElem = $("input[name=inputServiceInfo]");
      break;
  }

  alertDiv.find('.alert').hide();
  alertDiv.find('.alert').html('');
  let request_code = addOptionElem.val();
  let request_text =addOptionElem.find(":selected").text();
  let note = addNoteElem.val();
  if (request_code==='')
  {
    alertDiv.find('.alert').show();
    alertDiv.find('.alert').html('<strong>請選擇要新增的分類</strong>');
    return;
  }

  let url = "../../../vip/add_vip_request";
  $.ajax({
    type: "POST",
    url: url,
    data: "game_id=" + game_id+"&role_id=" + role_id + "&service_type=" + service_type+"&request_code=" + request_code +"&note=" + note,
  }).done(function(result) {
    let resultObj = JSON.parse(result);
    if (resultObj.status == 'success') {
      console.log(result);
      //{"id":"1","request_code":"A","note":"123","create_time":"2018-03-13 12:26:47","name":"\u55b5"}
      const log = resultObj.message;
      //const request_text = $('#serviceOptionSelector option[value="'+ log.request_code+ '"]').text();
      tableElem.find('tbody')
        .append($('<tr><th>'+ log.id +'</th><td>'+ log.create_time +'</td><td>'+ request_text +'</td><td>'+ log.note +'</td><td>'+ log.name +'</td><td><button class=\'btn btn-danger\' onclick=\'delRecord('+ log.id +')\'>X</button></td></tr>'));

    }
    else {
      alert(resultObj.message)
    }

  })
  .fail(function( jqXHR, textStatus ) {
    console.log( "Request failed: " + textStatus );
  })
  .always(function() {
    console.log("complete")
  });;

}


function get_vip_requests_log(service_type,page_num) {
  //http://test-payment.longeplay.com.tw/default/admin3/vip/vip_request_list/h35naxx1hmt/390709/1/2
  let url = "../../../vip/vip_request_list/" + game_id + "/" + role_id + "/" + service_type + "/" + page_num;

  var tableElem = null;
  var pageElem = null;
  var queryOptionElem = null;
  var queryNoteElem = null;
  var queryAdminOptionElem = null;
  var anchorString="";
  var sdate = null;
  var edate =null;


  switch (String(service_type)) {
    case '1':
      tableElem = $("#request-log");
      pageElem = $("#service_pages");
      queryOptionElem = $("#serviceOptionSelectorQuery");
      queryNoteElem = $("input[name=inputServiceInfoQuery]");
      queryAdminOptionElem = $("#adminSelectorQuery");
      anchorString ="a_request";
      sdate = $("#start_date");
      edate = $("#end_date");

      break;
    case '2':
      tableElem = $("#feedback-log");
      pageElem = $("#feedback_pages");
      queryOptionElem = $("#serviceFeedbackOptionSelectorQuery");
      queryNoteElem = $("input[name=inputServiceFeedbackInfoQuery]");
      queryAdminOptionElem = $("#adminFeedbackSelectorQuery");
      anchorString ="a_feedback";
      sdate = $("#start_date2");
      edate = $("#end_date2");

      break;
    default:
      tableElem = $("#request-log");
      pageElem = $("#service_pages");
      queryOptionElem = $("#serviceOptionSelectorQuery");
      queryNoteElem = $("input[name=inputServiceInfoQuery]");
      break;
  }


  let request_code = queryOptionElem.val();
  let note = queryNoteElem.val();
  let admin_uid = queryAdminOptionElem.val();
  let start_date = sdate.val();
  let end_date = edate.val();

  $.ajax({
    type: "GET",
    url: url,
    data: "request_code=" + request_code +"&note=" + note +"&admin_uid=" + admin_uid +"&start_date=" + start_date +"&end_date=" + end_date ,
  }).done(function(result) {
    var resultObj =  JSON.parse(result);
    page_count = resultObj.page_count;
    var logObj = resultObj.logs;

    pageElem.children().remove();
    tableElem.find('tbody').children().remove();

    for (i = 0; i < page_count; i++) {
      var _num = i+1;
      if (_num === page_num)
      {
        pageElem.append($("<li class='disabled'><a href='#"+ anchorString +"' onclick='get_vip_requests_log(\'" + service_type+ "\',"+ _num +")'>"+ _num +"</a></li>"));
      }
      else {
        pageElem.append($("<li><a href='#"+ anchorString+"' onclick='get_vip_requests_log(" + service_type+ ","+ _num +")'>"+ _num +"</a></li>"));
      }
    }


    for (i = 0; i < logObj.length; i++) {
        tableElem.find('tbody')
          .append($('<tr><th>'+ logObj[i].id +'</th><td>'+ logObj[i].create_time +'</td><td>'+ logObj[i].request_text +'</td><td>'+ logObj[i].note +'</td><td>'+ logObj[i].admin_name +'</td><td><button class=\'btn btn-danger\' onclick=\'delRecord('+ logObj[i].id +')\'>X</button></td></tr>'));
    }
  });
}

function delRecord(record_id){
   var isConfirmed = confirm("確定要刪除編號為"+ record_id +"的資料嗎?");
   if (isConfirmed)
   {
       let url = "../../../vip/del_vip_request";
       $.ajax({
         type: "POST",
         url: url,
         data: "record_id=" + record_id,
       }).done(function(result) {
         let resultObj = JSON.parse(result);
         if (resultObj.status == 'success') {
           //console.log(result);
           //const log = resultObj.message;
           $("th:contains('" + record_id +"')").parent().remove()

         }
         else {
           alert(resultObj.message)
         }

       });

   }
   else {
     //console.log("isConfirmed","不要刪除");
   }
}
