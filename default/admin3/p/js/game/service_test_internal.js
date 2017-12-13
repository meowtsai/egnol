$(function(){

	$("#submitForm").click(function(){



	});

  $("#test_service_form").submit(function(e) {
    e.preventDefault();
    var game_id = $("#game_id").val();
    var partner_uid = $("#partner_uid").val();
    var in_game_id = $("#in_game_id").val();
    var server_name = $("#server_name").val();
    var character_name = $("#character_name").val();
    var level = $("#level").val();
    var usr_device = $("#usr_device").val();
    var os_ver = $("#os_ver").val();
    var app_ver = $("#app_ver").val();
    var time_zone = $("#time_zone").val();
    var network = $("#network").val();
    var mykey = $("#mykey").val();

    var querystring = encodeURI(`game_id=${game_id}&partner_uid=${partner_uid}&in_game_id=${in_game_id}&server_name=${server_name}&character_name=${character_name}&level=${level}&usr_device=${usr_device}&os_ver=${os_ver}&app_ver=${app_ver}&time_zone=${time_zone}&network=${network}`);

    var premd5string = `${querystring}&key=${mykey}`;
    var md5string= md5(premd5string);

    querystring = querystring + '&key=' + md5string;

    $("#key").val(md5string);

    var url = $(this).attr('action');
    window.location.href = url + "?" + querystring;


  });
});
