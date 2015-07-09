$(function(){
	
	$("form").validate({
		submitHandler: function(form) {
			var today = new Date();
			$(form).json_ajaxSubmit(function(json){
				if (json.back_url) {
					location.href = json.back_url;
				}
				else {
					location.href = '/admin3/service/question_assign?date='+today.getFullYear()+"-"+append_zero(today.getMonth()+1)+"-"+append_zero(today.getDate());
				}
			});
		}
	});
	
});

function append_zero(obj)
{
	if(obj<10) return "0" +""+ obj;
	else return obj;     
}