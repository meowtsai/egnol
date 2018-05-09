var resultObj = null;
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
  $( "#btn-download" ).hide();
  $(".alert").hide();

  // editMode();
  // role_id = $("input[name=role_id]").val();
  // game_id =  $("input[name=game_id]").val();
  // get_vip_requests_log('1',1);
  // get_vip_requests_log('2',1);
  //

});

//http://test-payment.longeplay.com.tw/default/admin3/vip/requests_report_data/h35naxx1hmt?service_type=1

function get_vip_requests_log(game_id) {
  let url = "../../vip/requests_report_data/" + game_id ;
//service_type,page_num
  var tableElem = $("#report-table");

  let service_type = $("#service_type").val();
  let start_date = $("#start_date").val();
  let end_date = $("#end_date").val();
  //console.log(service_type);
  $.ajax({
    type: "GET",
    url: url,
    data: "service_type=" + service_type +"&start_date=" + start_date +"&end_date=" + end_date,
  }).done(function(result) {
    //resultData = result;
    resultObj =  JSON.parse(result);

    tableElem.find('tbody').children().remove();
    if (resultObj.length>0)
    {
      $( "#btn-download" ).show();
    }
//	角色序號	角色時間	類別	時間	內容	專員
    for (i = 0; i < resultObj.length; i++) {
        tableElem.find('tbody')
          .append($('<tr><th>'+ resultObj[i].id +'</th><td>'+ resultObj[i].role_id +'</td><td>'+ resultObj[i].role_name +'</td><td>'+ resultObj[i].request_text +'</td><td>'+ resultObj[i].note +'</td><td>'+ resultObj[i].create_time +'</td><td>'+ resultObj[i].admin_name +'</td></tr>'));
    }
  });
}


function get_inactive_users_report(game_id) {
  let url = "../../vip/inactive_users_report/" + game_id ;
//service_type,page_num
  var tableElem = $("#report-table");
  //var queryOptionElem = $("#date_column");

  let date_column = $("#date_column").val();
  let start_date = $("#start_date").val();
  let end_date = $("#end_date").val();
  $(".alert").hide();
  //console.log(service_type);
  $.ajax({
    type: "GET",
    url: url,
    data: "date_column=" + date_column +"&start_date=" + start_date +"&end_date=" + end_date,
  }).done(function(result) {
    //resultData = result;
    resultObj =  JSON.parse(result);

    tableElem.find('tbody').children().remove();
    $(".alert").show();
    $(".alert").text("共找到:" + resultObj.length +"筆記錄" );
    if (resultObj.length>0)
    {
      $( "#btn-download" ).show();
    }
    for (i = 0; i < resultObj.length; i++) {
        tableElem.find('tbody')
        .append($('<tr><th>'+ resultObj[i].account +'</th><td>'+ resultObj[i].role_id +'</td><td>'+ resultObj[i].role_name +'</td><td>'+ resultObj[i].vip_ranking +'</td><td>'+ resultObj[i].latest_topup_date +'</td><td>'+ resultObj[i].last_login +'</td><td>'+ resultObj[i].inactive_confirm_date +'</td></tr>'));
    }
  });
}
//
function convertArrayOfObjectsToCSV(args) {
   var result, ctr, keys, columnDelimiter, lineDelimiter, data;

   data = args.data || null;
   if (data == null || !data.length) {
       return null;
   }

   columnDelimiter = args.columnDelimiter || ',';
   lineDelimiter = args.lineDelimiter || '\n';

   keys = Object.keys(data[0]);

   result = '';
   //result += keys.join(columnDelimiter);
   //
  switch (args.rpt_type) {
    case 'inactive_users':
      result += ["主帳號","角色序號","角色名","vip級別","最後付費","最後登入","確認流失"].join(columnDelimiter);
      break;
    default:
      result += ["編號","角色序號","角色名","類別","內容","時間","專員"].join(columnDelimiter);
      break;
  }

   result += lineDelimiter;

   data.forEach(function(item) {
       ctr = 0;
       keys.forEach(function(key) {
         //console.log("key",key);
         if (key !=='request_code')
         {
           if (ctr > 0) result += columnDelimiter;

           result += item[key];
           ctr++;
         }

       });
       result += lineDelimiter;
   });

   return result;
}
function downloadCSV(args) {
    var data, filename, link;
    console.log(resultObj);

    var csv = convertArrayOfObjectsToCSV({
        rpt_type: args.rpt_type,
        data: resultObj,
    });
    if (csv == null) return;



    filename = args.rpt_type + ' export.csv';

    if (!csv.match(/^data:text\/csv/i)) {
        csv = 'data:text/csv;charset=BIG5,' + csv;
    }
    data = encodeURI(csv);

    link = document.createElement('a');
    link.setAttribute('href', data);
    link.setAttribute('download', filename);
    link.click();
}
