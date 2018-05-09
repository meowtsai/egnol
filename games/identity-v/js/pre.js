function load_page()
{
  // user_email=longeplay_ci_session
  var x = document.cookie;
  $("#event_info").removeClass();
  $("#event_info").hide();
  //console.log(x.user_email);
}

function test(e)
{
  e.preventDefault();

  $("#event_info").removeClass();
  $("#event_info").addClass("alert alert-danger" );
  $("#event_info").show();
  $("#event_info").text("請勿重複發送!")
}

function event_submit(e)
{
  e.preventDefault();
  $("#event_info").removeClass();
  $("#event_info").hide();

  let user_email = $("#user_email").val();
  //console.log("user_email",user_email);

  let url = "http://game.longeplay.com.tw/event/h55_prereg";
  $.ajax({
    type: "POST",
    url: url,
    data: "user_email=" + user_email,
  }).done(function(result) {
    var resObj = JSON.parse(result);
    //{"status":"failure","message":"E-Mail \u683c\u5f0f\u932f\u8aa4\u3002(Invalid Email)"}
    //{"status":"success","site":null,"message":"\u9810\u8a3b\u518a\u767b\u9304\u6210\u529f\uff01(OK)"}
    if (resObj.status=="success")
    {
      document.cookie = "h55_user_email=" + user_email;
      $("#user_email").attr('readonly', true);
      $('button[type="submit"]').hide();

      $("#event_info").removeClass();
      $("#event_info").addClass("alert alert-success" );
      $("#event_info").show();
      $("#event_info").text("預註冊登錄成功，幫我們忙粉絲頁按個讚喔!")

    }
    else {
      alert(resObj.message);
      $("#event_info").removeClass();
      $("#event_info").addClass("alert alert-danger" );
      $("#event_info").show();
      $("#event_info").text(resObj.message)
    }
    //console.log("request done",result);

  })
  .fail(function( jqXHR, textStatus ) {
    //console.log( "Request failed: " + textStatus );

  })
  .always(function() {
    //alert( "complete" );
    //console.log("complete")
  });;
}
