var serverin = $.url.param("serverin");

$(function(){

	if (serverin && chk_login()) choose_server_form();
	
	$("#register").click(function(){
		
		$.get("/platform/register_form", function(data){
			$.blockUI({ message: data,
				css: { 
					top: '0',
					backgroundColor: '',
					cursor: 'auto',
					border: '0',
					left: '20%',
				}}); 
			$('.blockOverlay').click($.unblockUI);			
			$("#register_form").find('.close').click($.unblockUI).end()
				.validate({
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
									location.href = 'http://www.longeplay.com.tw/play_game/my';
								}
								else alert(json.message);
							}		
						});
					}					
				 });
			refresh_captcha();
		});		
	});
	
	$(".choose_server").click(choose_server_form);
	
	$(".enter_game").click(function(){
		if (chk_login()) choose_server_form();
		else {
			alert("請先登入");			
		}
	});
	
	refresh_captcha();
	
	if (typeof $.validator != "undefined") {
		$.validator.messages = {
		        required: "必填",
		        remote: "請修正該字段",
		        email: "請輸入正確格式的電子郵件",
		        url: "請輸入合法的網址",
		        date: "請輸入合法的日期",
		        dateISO: "請輸入合法的日期 (ISO).",
		        number: "請輸入合法的數字",
		        digits: "只能輸入整數",
		        creditcard: "請輸入合法的信用卡號",
		        equalTo: "請再次輸入相同的值",
		        accept: "請輸入擁有合法後綴名的字符串",
		        maxlength: jQuery.format("請輸入一個長度最多是 {0} 的字符串"),
		        minlength: jQuery.format("請輸入一個長度最少是 {0} 的字符串"),
		        rangelength: jQuery.format("請輸入一個長度介於 {0} 和 {1} 之間的字符串"),
		        range: jQuery.format("請輸入一個介於 {0} 和 {1} 之間的值"),
		        max: jQuery.format("請輸入一個最大為 {0} 的值"),
		        min: jQuery.format("請輸入一個最小為 {0} 的值")
		};	
	}
	if (typeof $.blockUI != "undefined") {
		$.extend($.blockUI, {
			wait: function() {
				$.blockUI({ 
					message: '<h1 style="padding:6px;">請稍候...</h1>',
					css: {
			            border: 	'none', 
			            padding: 	'10px', 
			            margin:		0,
			            width:		'30%',
			            top:		'auto',
			            left:		'auto',
			            bottom:		'20px',
			            right:		'20px',
			            textAlign:	'center',
			            cursor:		'wait',
			            backgroundColor: '#000', 
			            '-webkit-border-radius': '10px', 
			            '-moz-border-radius': '10px', 
			            opacity: .5, 
			            color: '#fff' 
				} });
			}
		});
	}	
	
    $("#login_form input").keypress(function(event){       
        if (event.keyCode == 13) $("#login_form").submit();
    });
    
    $(document).on("keypress", "#register_form input", function(event){       
        if (event.keyCode == 13) $("#register_form").submit();
    });
    
	$("body").on("mouseenter", "img.change", function(){$(this).attr("src", $(this).attr("src").replace('off', 'on'))})
		.on("mouseleave", "img.change", function(){$(this).attr("src", $(this).attr("src").replace('on', 'off'))});	
	
	$("#slideshow").slideshow({ 
		pauseSeconds: 5,
		height:385,
		width:350,
		fadeSpeed:1,
		caption: false
	});	
});

function chk_login() {
	return $("#long_e_account").length == 0 ? false : true;
}

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

function show_error(msg)
{
	alert(msg ? msg : '失敗');
}


function choose_server_form() {
	$.get("/common/choose_server_form?ad="+$("input[name=ad]").val()+"&", function(data){
		$.blockUI({ message: data,
			css: { 
				top: '0',
				backgroundColor: '',
				cursor: 'auto',
				border: '0',
				left: '30%',
			}}); 
		$('.blockOverlay').click($.unblockUI);
		$("#choose_server_form").find('.close').click($.unblockUI);
	});		
}