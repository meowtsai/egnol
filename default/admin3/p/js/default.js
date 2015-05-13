$(function(){
		
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
				} });
			}
		});
	}
	
	$.json_post = function(url, data, callback) {
		if (typeof data == "function") {
			callback = data; data = {}; 
		};
		$.blockUI.wait();			
		$.post(url, data, function(json) {
			if (json.status == 'success') {
				callback(json);
			} else show_error(json.message);
		}, 'json')
		.error(function(e){
			alert(e.status + " " + e.statusText);
		})
		.complete($.unblockUI);	
	};
	
	$.fn.json_ajaxSubmit = function(callback) {
		return this.each(function() {
			$.blockUI.wait();
			$(this).ajaxSubmit({
				success: function(json) {
					$.unblockUI();
					if (json.status == 'success') {
						callback(json);
					} else show_error(json.message);
				},
				error: function(e) {
					$.unblockUI();
					alert(e.status + " " + e.statusText + "\nResponse:" + e.responseText);
				},
				dataType: 'json'
			});			
	    });
	};

	//** long_e

	/*if ($('.cz_more').length > 0) {
		$('.cz_more').each(function(){
			var block = $(this);
			var a = block.find("a");
			var ul = block.find("ul");
			
			a.click(function(evt){			
				$('.cz_more ul:visible').hide();
				var os = $(a).offset();
				ul.show().css('left', os.left-5).css('top', os.top+$(a).height());
			});
			block.click(function(evt){evt.stopPropagation();});			
		});
		
		$("html").click(function(){
			$('.cz_more ul:visible').hide();
		});
	}*/
	
	$(".json_post").click(function(){	
		$.json_post($(this).attr("url"), function(){
			location.reload();
		});	
	});	
	
	$(".json_post_confirm").click(function(){
		if (confirm("確定要執行嗎?")) {
			$.json_post($(this).attr("url"), function(json){
				location.reload();
			});	
		}
	});	
	
	$(".json_post_alert").click(function(){	
		$.json_post($(this).attr("url"), function(json){
			alert(json.message);
			location.reload();
		});	
	});		
	
	$(".json_del").click(function(){
		if (confirm("確定要刪除嗎?")) {
			$.json_post($(this).attr("url"), function(){
				location.reload();
			});	
		}
	});		
	
	if ($('.table .all_cb').length > 0) {
		$('.table .all_cb').click(function(){
			$('.table .cb').attr("checked", $(this).prop("checked"));			
		}).attr("title", "全選");
		
		$('.table .muti_json_post').click(function(){
			var choose = Array();
			$('.table .cb').each(function(k,v){
				if ($(v).prop("checked")) {
					choose.push($(v).attr("id"));
				}
			});			
			if (choose.length>0) {
				$.json_post($(this).attr("url"), {ids: choose}, function(){
					location.reload();
				});	
			}
			else alert("請先選擇");
		});
	}

	if ($('.category').length > 0) {
		$('.category').find("a").on('click', $.blockUI.wait);
	}
	
	if ($('form.validation').length > 0) {
		$("form.validation").validate();
	}
	
	$('input, textarea').placeholder();
	
	$('table.table tr').on("mouseenter", function(){$(this).find(".edit").show();});
	$('table.table tr').on("mouseleave", function(){$(this).find(".edit").hide();});
	
	$('.clear_date').on("click", function(){$("input[name='start_date']").val(''); $("input[name='end_date']").val('');});
	
	$('a.dropdown-toggle').on("mouseenter", function(){$(this).click();});
});

function show_error(msg) {
	alert(msg ? msg : '失敗');
}


