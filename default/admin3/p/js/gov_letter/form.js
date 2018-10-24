$(function() {
  var o_date = $("#o_letter_date").val();
  $("#o_letter_date").datepicker({
		changeMonth: true,
    changeYear: true
	});
	$("#o_letter_date").datepicker( "option", "dateFormat", "yy-mm-dd");
  $("#o_letter_date").val(o_date);

  var deadline = $("#deadline").val();
  $("#deadline").datepicker({
		changeMonth: true,
    changeYear: true
	});
	$("#deadline").datepicker( "option", "dateFormat", "yy-mm-dd");
  $("#deadline").val(deadline);

  $( "#game_id" ).change(function() {
    //console.log("game_id changed");
  	var select_game_id = $(this).find(":selected").val();
		let url =  ($("input[name=letter_id]").val()?"../":"./") + "get_servers_json/" + select_game_id;
    $.ajax({
			type: "POST",
			url: url,
      dataType: "json",
			contentType: 'application/json; charset=UTF-8',
		}).done(function(result) {
      if (result.status=="success")
			{
        $( "#server_id" ).empty();
				var servers = result.message;
				for (var i = 0; i < servers.length; i++) {
				      $( "#server_id" ).append("<option value='"+ servers[i].server_id +"'>"+ servers[i].name + "(" + servers[i].server_id +")</option>")
				}
			}
		})
		.fail(function( jqXHR, textStatus ) {
			//console.log( "Request failed: " + textStatus );
		})
		.always(function() {
			//console.log("complete")
      if ($("input[name=letter_id]").val())
      {
        var originalSelected = $("input[name=hidden_server_id]").val();
        $('#server_id option[value="'+ originalSelected+'"]').prop('selected', true);
      }

		});
  });
  //console.log("selected" ,$("#game_id option:selected").index());
  if ($("#game_id option:selected").index()>0){
      $("#game_id").trigger( "change" );
  }
  $("form").validate({
    submitHandler: function(form) {
      $(form).json_ajaxSubmit(function(json){
        location.href = json.redirect_url;
      });
    }
  });
});
