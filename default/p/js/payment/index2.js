$(function(){
    
	var validation_option = {
		onfocusout: false,
		onkeyup: false,
		onclick: false,
		messages: {
			game: "尚未選擇遊戲",
			server: "尚未選擇伺服器"
		},
		showErrors: function(errorMap, errorList) {
		   var err = '';
		   $(errorList).each(function(i, v){
			   err += v.message + "\n";
		   });
		   if (err) alert(err);
		}				
	 }
	
    $("#choose_form").validate(validation_option);    
    $("#choose_other_form").validate(validation_option);
    $("#mycard_ingame_form").validate(validation_option); 
    
	var server_pool = $("#server_pool");
	var game = $("select[name='game']");	
	game.on('change', function(){
		
		if (game.val() == 'ry') {
			var arr = ['50', '500', '1000', '5000', '10000'];
			$(".amount_block label").each(function() {
				if ($.inArray($(this).find("input").val(), arr) === -1) {
					$(this).hide();
				};
			});
			$('.amount_block input').eq(3).click();
		}
		else {
			$(".amount_block label").show();
		}
		
		var server = $(this).parents("form").find("select[name='server']");
		server.empty().append("<option value=''>--請選擇--</option>");
		if (typeof $(this).val() !== 'undefined' && $(this).val() !== '') {
			server_pool.find("option."+$(this).val()).clone().appendTo(server);
		}
		update_gain_tip(this);
	});
	game.trigger("change");
	
	$("input[name='type']").on("change", function(event){
		var frm = $(this).parents("form:first");
		if ($(this).val() == 'game') {
			frm.find(".game_option").show();
		} else frm.find(".game_option").hide();
		update_gain_tip(this);
	});
	
    $("input[name='gash_channel']").on("click", function(event){
		var html = '';
		var $this = $(this);
		$.each(gash_amount, function(key,val){			
			var amount = (val*($this.attr("convert_rate")*1000)/1000);
			//if ($this.attr("CUID") == 'IDR' && amount > 964800) return;
			if ($this.attr("CUID") == 'IDR' && amount > 46000) return;
			else if ($this.attr("CUID") == 'PHP' && amount > 4070) return;
			else if ($this.attr("CUID") == 'THB' && amount > 2980) return;
			//else if ($this.attr("CUID") == 'VND' && amount > 2082500) return;
			else if ($this.attr("CUID") == 'VND' && amount > 88000) return;
			else if ($this.attr("CUID") == 'MYR' && amount > 305) return;
			else if ($this.attr("CUID") == 'KRW' && amount > 106650) return;
			html += '<label><input type="radio" name="payment_amount" class="required" value="'+amount+'" >'+amount+'</label><br> ';
		});		
		if ($this.attr("PAID") !== 'COPGAM02') {
			$('#choose_other_form .amount_row').show();
			$('#choose_other_form .amount_block').html(html);
			$('#choose_other_form .amount_block input').eq(3).click();
		}
		else {
			$('#choose_other_form .amount_row').hide();
		}
    	$("input[name='PAID']").val($this.attr("PAID"));
    	$("input[name='CUID']").val($this.attr("CUID"));
    	$("input[name='ERP_ID']").val($this.attr("ERP_ID"));		
    });	
    $("input[name='gash_channel']").eq(0).click();
    
    $(".amount_block").on("change", "input", function(event){
    	$("input[name='service_id']").val($(this).attr("service_id"));
   		update_gain_tip(this);
    });
    
    $(".leftside a").on("click", function(){
		$(".leftside a").removeClass("active");
		$(this).addClass("active");
	});
    $(".leftside a:first").click();
    
});

function update_gain_tip(obj) {
	var frm = $(obj).parents("form:first");
	if (frm.find("select[name='game']").val() && frm.find("input[name='type']:checked").val() == 'game') {
		var game = frm.find("select[name='game'] option:selected");
		var amount = frm.find("input[name='payment_amount']:checked").val()*game.attr("rate");
		if (frm.attr('id') == 'choose_other_form') {
			amount = (amount*1000) / (frm.find("input[name='gash_channel']:checked").attr("convert_rate")*1000); 
		}
		frm.find("#gain_tip").text("您將可以獲得 "+amount+game.attr("goldname"));
	}
	else frm.find("#gain_tip").text('');
}

function choose(type, def) {
	$('#choose_form').show();
	$('#mycard_ingame_form').hide();	
	$('#choose_other_form').hide();
	$("#choose_title").text(type);	
	$.get("http://www.long_e.com.tw/mycard/get_product/"+encodeURI(def), function(data){
		var html = '';
		$.each(data, function(key,val){
			html += '<label style="display:block;"><input type="radio" name="payment_amount" class="required" service_id="'+val+'" value="'+key+'" >'+key+'</label> ';
		});		
		$('#choose_form .amount_block').html(html);
		$('#choose_form .amount_block input').eq(3).click();
	}, 'json');
}

function choose_other(type) {
	$('#choose_form').hide();
	$('#mycard_ingame_form').hide();
	$('#choose_other_form').show();
	$("#choose_title").text(type);	
}

function choose_mycard_ingame() {
	$('#choose_form').hide();
	$('#mycard_ingame_form').show();	
	$('#choose_other_form').hide();
	$("#choose_title").text('Mycard實體卡');	
}

function switch_pay_type(id) {
	if (typeof id == 'undefined' || id == 'b') {
		$("#pay_type_block").hide();
	} else $("#pay_type_block").show();
	$(".pay_type").hide();
	$(".pay_type_"+id).show().find("input").eq(0).click();
}