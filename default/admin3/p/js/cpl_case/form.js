$(function() {
  var o_date = $("#o_case_date").val();
  $("#o_case_date").datepicker({
		changeMonth: true,
    changeYear: true
	});
	$("#o_case_date").datepicker( "option", "dateFormat", "yy-mm-dd");
  $("#o_case_date").val(o_date);


  
  $( "#game_id" ).change(function() {
  	var select_game_id = $(this).find(":selected").val();
		let url =  ($("input[name=case_id]").val()?"../":"./") + "get_servers_json/" + select_game_id;
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
      if ($("input[name=case_id]").val())
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
