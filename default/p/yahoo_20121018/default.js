$(function(){

	$("#register_form").validate({
		onfocusout: false,
		onkeyup: false,
		onclick: false,
		messages: {
			account: {
				required: "`帳號`必填",
				minlength: "`帳號`最少6碼"
			},
			pwd: "`密碼`必填",
			pwd2: "`確認密碼`必填",
			captcha: "`認證碼`必填`"
		},
		showErrors: function(errorMap, errorList) {
		   var err = '';
		   $(errorList).each(function(i, v){
			   err += v.message + "\n";
		   });
		   if (err) alert(err);
		   //this.defaultShowErrors();
		},
		submitHandler: function(form) {
			$(form).ajaxSubmit({
				dataType: 'json',
				success: function(json) {
					if (json.status == 'success') {
						location.href = 'http://www.long_e.com.tw/gate/login_game/mon?ad=mon_yahoo_a';
					}
					else alert(json.message);
				}		
			});
		}					
	 });
	refresh_captcha();
	
    $("#register_form input").keypress(function(event){       
        if (event.keyCode == 13) $("#register_form").submit();
    });
});

function refresh_captcha()
{
	$('#captcha_area').each(function(){
		$.get("/common/create_captcha", function(data){
			$("#captcha_area").html(data);
			$("#captcha_area img").attr("title", "重整驗證碼").click(function() {
				refresh_captcha();
			});
		});
	});
}
