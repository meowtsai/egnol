$(function()
{
	if (typeof $.validator != "undefined")
	{
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
		$.validator.addMethod("twId", function( value, element)
		{
			if(value.length==0) return true;
			if(value.length!=10) return false;
			IDN = value;
			IDTable = {A:10, B:11, C:12, D:13, E:14, F:15, G:16, H:17, I:34, J:18, K:19, M:21, N:22, O:35, P:23, Q:24, T:27, U:28, V:29, W:32, X:30, Z:33, L:20, R:25, S:26, Y:31};
			LocalDigit = IDTable[IDN[0].toUpperCase()];
			return /^[A-Za-z][1,2][\d]{8}/.test(value) && ((Math.floor(LocalDigit/10) + (LocalDigit%10)*9 + IDN[1]*8 + IDN[2]*7 + IDN[3]*6 + IDN[4]*5 + IDN[5]*4 + IDN[6]*3 + IDN[7]*2 + IDN[8]*1 + IDN[9]*1)%10==0);
		}, '請輸入有效的身分證字號');

		// 手機號碼驗證
		jQuery.validator.addMethod("isMobile", function(value, element)
		{
            return this.optional(element) || (checkMobileRegion(value) != 'undefined');
			/*
			var reg = /^\d{10,12}$/;
			return this.optional(element) || (reg.test(value));
			*/
		}, "請輸入正確的手機號碼(港澳地區請加上國際碼)");

		// 電話號碼驗證
		jQuery.validator.addMethod("isPhone", function(value, element)
		{
			var tel = /^(\d{2,4}-?)?\d{7,9}$/g;
			return this.optional(element) || (tel.test(value));
		}, "請輸入正確的電話號碼");
	}

	if (typeof $.blockUI != "undefined")
	{
		$.extend($.blockUI, {
			wait: function()
			{
				$.blockUI({ 
					message: '<h1 style="padding:6px;">請稍候...</h1>',
					fadeIn: 50,
					fadeOut: 200,
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
					}
				});
			}
		});
	}
	
	$.json_post = function(url, data, callback)
	{
		if (typeof data == "function")
		{
			callback = data; data = {}; 
		};
		$.blockUI.wait();			
		$.post(url, data, function(json)
		{
			if (json.status == 'success')
			{
				callback(json);
			}
			else
				show_error(json.message);
		}, 'json')
		.error(function(e)
		{
			alert(e.status + " " + e.statusText);
		})
		.complete($.unblockUI);	
	};
	
	$.fn.json_ajaxSubmit = function(callback)
	{
		return this.each(function()
		{
			$.blockUI.wait();
			$(this).ajaxSubmit({
				success: function(json)
				{
					$.unblockUI();
					if (json.status == 'success')
					{
						callback(json);
					}
					else
						show_error(json.message);
				},
				error: function(e)
				{
					$.unblockUI();
					alert(e.status + " " + e.statusText + "\nResponse:" + e.responseText);
				},
				dataType: 'json'
			});			
	    });
	};

	$(".json_post").click(function()
	{	
		$.json_post($(this).attr("url"), function()
		{
			location.reload();
		});	
	});	
	
	$(".json_del").click(function()
	{
		if (confirm("確定要刪除嗎?"))
		{
			$.json_post($(this).attr("url"), function()
			{
				location.reload();
			});	
		}
	});		
	
	if ($('.json_form').length > 0)
	{
		$(".json_form").validate({
			submitHandler: function(form)
			{
				$(form).json_ajaxSubmit(function(json)
				{
					if (typeof json.message != 'undefined')
					{
						alert(json.message);
					}
					if (typeof json.back_url != 'undefined')
					{
						location.href = json.back_url;
					}
					else
						location.reload();
				});
			}
		});
	}
		
	if ($('form.validation').length > 0)
	{
		$("form.validation").validate();
	}
	
    $("#login_form input").keypress(function(event)
	{       
        if (event.keyCode == 13) $("#login_form").submit();
    });
	
    if ($('#captcha_area').length > 0)
	{
    	refresh_captcha();
    }

    $('#top_menu li').hover(function()
	{
		$(this).find(".sub_menu").show();
	}, function()
	{
		$(this).find(".sub_menu").hide();
	});
});

function show_error(msg)
{
	alert(msg ? msg : '失敗');
}

function refresh_captcha()
{
	$('#captcha_area').each(function()
	{
		$.get("/common/create_captcha", function(data)
		{
			$("#captcha_area").html(data);
			$("#captcha_area img").attr("title", "重整驗證碼").click(function()
			{
				refresh_captcha();
			});
		});
	});
}

var browser = {    
	versions: function()
	{            
		var u = navigator.userAgent, app = navigator.appVersion;            
		return {                
			trident: u.indexOf('Trident') > -1, //IE內核                
			presto: u.indexOf('Presto') > -1, //opera內核                
			webKit: u.indexOf('AppleWebKit') > -1, //蘋果、谷歌內核                
			gecko: u.indexOf('Gecko') > -1 && u.indexOf('KHTML') == -1, //火狐內核                
			mobile: !!u.match(/AppleWebKit.*Mobile.*/), //是否為移動終端                
			ios: !!u.match(/\(i[^;]+;( U;)? CPU.+Mac OS X/), //ios終端                
			android: u.indexOf('Android') > -1 || u.indexOf('Linux') > -1, //android終端或者uc瀏覽器                
			iPhone: u.indexOf('iPhone') > -1 || u.indexOf('Mac') > -1, //是否為iPhone或者QQHD瀏覽器                
			iPad: u.indexOf('iPad') > -1, //是否iPad                
			webApp: u.indexOf('Safari') == -1 //是否web應該程序，沒有頭部與底部           
		};
	}()
} 

//--------------------------------------------------
function checkMobileRegion(value)
{
	var phone = value.trim().replace(/-/g, "").replace(/\+/g, "");

	// 886 台
	if(/^8869\d{8}$/.test(phone) || /^09\d{8}$/.test(phone))
		return 'tw';

	// 852 港
	if(/^852[5-9]\d{7}$/.test(phone))
		return 'hk';

	// 853 澳
	if(/^8536\d{7}$/.test(phone))
		return 'mo';

	return 'undefined';
}

function mobileNormalize(value)
{
	var phone = value.trim().replace(/-/g, "").replace(/\+/g, "");

	switch(checkMobileRegion(value))
	{
	case 'tw':  // 09XX-XXX-XXX
		if(phone.indexOf('886') == 0)
			phone = phone.replace('886', '0');

		phone = phone.slice(0, 4) + '-' + phone.slice(4, 3) + '-' + phone.slice(7);
		break;

	case 'hk':  // 852-XXXX-XXXX
	case 'mo':  // 853-XXXX-XXXX
		phone = phone.slice(0, 3) + '-' + phone.slice(3, 4) + '-' + phone.slice(7);
		break;

	default:
		phone = '';
		break;
	}

	return phone;
}

//--------------------------------------------------
function leNew(n, i)
{
	if("undefined" == typeof n)
		n = "div";

	var e = document.createElement(n);
	if("undefined" == typeof i)
		return e;

	for(var o in i)
		$(e).attr(o, i[o]);

	return e;
}

function leAppend(n, i, e)
{
	if("undefined" == typeof i)
		i = "div";

	var o = n.appendChild(document.createElement(i));
	if("undefined" == typeof e)
		return o;

	for(var t in e)
		$(o).attr(t, e[t]);

	return o;
}

function leSeparator(n, i)
{
	return "undefined" == typeof i && (i =! 1), i ? leAppend(n,"div",{"class":"le_separator_v"}):leAppend(n,"div",{"class":"le_separator"});
}

function leShowScreenMask(n)
{
	if(null != n)
	{
		var i = document.getElementById("le_screen_mask");
		if(null == i)
			i = leAppend(document.body,"div",{id:"le_screen_mask"});
		$(i).css("opacity",n), $(i).css("filter","Alpha(opacity="+Math.floor(100*n)+")"),$(i).css("display","inline");
		$(i).css("height",$(document).height());
	}
	var e = document.getElementById("le_window_viewport");
	return null == e && (e = leAppend(document.body,"div",{id:"le_window_viewport"})),$(e).css("display","inline"),e;
}

function leHideScreenMask()
{
	$("#le_window_viewport").css("display","none"),$("#le_screen_mask").css("display","none"),$("#le_window_viewport").empty();
}

function leOpenDocumentViewer(doc, cb)
{
    var t = leShowScreenMask(.3);
	var vw = leAppend(t, "div", {"class":"le_viewer_frm"});

	$(vw).css("top", 4);
	$(vw).css("height", $(window).height() - 8 - 10 - 2);
	$(vw).css("z-index", 988);

	var s = ($(window).width() - 8 - $(vw).width()) / 2;
	$(vw).css("left", s);

	var content = leAppend(vw, "div", {id:"le_viewer_content"});
	$(content).css("width", $(vw).width() - 10);
	$(content).css("height", $(vw).height() - 50);

	content.innerHTML = doc;

	var d = leAppend(vw,"div",{id:"le_dialog_button_list"});
	var p = leAppend(d, "div", {"class":"le_dialog_button"});

    p.innerHTML = '關閉';
	$(p).click(function()
	{
		leHideScreenMask();
		if(null != cb)
			cb();
	});
}

var leDialogType = { MESSAGE: 0, CONFIRM: 1 };

function leOpenDialog(n, i, e, o)
{
    var t = leShowScreenMask(.3);
	var u = leAppend(t,"div",{"class":"le_window_frm"});

	leAppend(u, "div",{"class":"le_window_title"}).innerHTML = "&nbsp;" + n;
	leSeparator(u);

	var d = (leAppend(u, "div", {id:"le_dialog_content"}).innerHTML = i,leAppend(u,"div",{id:"le_dialog_button_list"}));
	var p = leAppend(d, "div", {"class":"le_dialog_button"});
	switch(p.innerHTML='確定',$(p).click(function(){leHideScreenMask(),null!=o&&o(!0)}),e)
	{
	case leDialogType.CONFIRM:
		var a=leAppend(d,"div",{"class":"le_dialog_button"});
		a.innerHTML='取消',$(a).click(function()
		{
			leHideScreenMask(),null!=o&&o(!1);
		});
		break;
	}
	var s = ($(window).width()-$(u).width())/2,r=($(window).height()-$(u).height())/3+$(document).scrollTop();
	$(u).css("left",s),$(u).css("top",r);
}
