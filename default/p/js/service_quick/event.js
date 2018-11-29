
var modal = document.getElementById('myModal');
var span = document.getElementsByClassName("close")[0];
var content = document.getElementsByClassName("modal-content")[0].childNodes[5];
span.onclick = function() {
    modal.style.display = "none";
}

window.onclick = function(event) {
    if (event.target == modal) {
        modal.style.display = "none";
    }
}



$(function()
{
	$("#event_form").validate({
		onfocusout: false,
		onkeyup: false,
		onclick: false,
		messages: {
			serial_no: {
				required: "* 必填",
				minlength: "* 序號錯誤",
				maxlength: "* 序號錯誤"
			},
		},
		submitHandler: function(form)
		{
			$(form).ajaxSubmit(
			{
				dataType: 'json',
				success: function(json)
				{
					if (json.status == 'success')
					{
						modal.style.display = "block";
						content.innerText = json.message;
						location.href = '/service_quick/yahoo_event';
						var timer = setTimeout(function() {
							 window.location='/service_quick/yahoo_event'
					 }, 3000);
					}
					else
					{
						modal.style.display = "block";
						content.innerHTML = "<font color='red'>" + json.message + "</font>";
						//leOpenDialog('兌換失敗', json.message, leDialogType.MESSAGE);
					}
				}
			});
		}
	});
});
