var serverin = $.url.param("serverin");

$(function(){

	if(top.location!=location) top.location=self.location;
	
	if (serverin && chk_login()) choose_server_form();
	
	$("#register").click(function(){		
		$.get("http://www.longeplay.com.tw/member/register?ajax=1", function(data){
			$.blockUI({ message: data,
				css: { 
					top: '0',
					backgroundColor: '',
					cursor: 'auto',
					border: '0',
					left: '20%',
				}}); 
			$('.blockOverlay').click($.unblockUI);			
			$("#register_form").find('.close').click($.unblockUI).end();
					
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
        
	$("body").on("mouseenter", "img.change", function(){$(this).attr("src", $(this).attr("src").replace('off', 'on'))})
		.on("mouseleave", "img.change", function(){$(this).attr("src", $(this).attr("src").replace('on', 'off'))});	
	
	
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