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
		update_gain_tip();
	});
	game.trigger("change");
	
	$("input[name='type']").on("change", function(event){
		var frm = $(this).parents("form:first");
		if ($(this).val() == 'game') {
			frm.find(".game_option").show();
		} else frm.find(".game_option").hide();
		update_gain_tip();
	});
	
    $(".gash_option").on("click", function(event){
		var html = '';
		var $this = $(this);
		$.each(gash_amount, function(key,val){
			if (eval(val) > $this.attr("maximum")) return;
			if (eval(val) < $this.attr("minimum")) return;
			
			var amount = (val*($this.attr("convert_rate")*1000)/1000);
			//if ($this.attr("CUID") == 'IDR' && amount > 964800) return;
			
			if ($this.attr("CUID") == 'IDR' && amount > 46000) return;
			else if ($this.attr("CUID") == 'PHP' && amount > 4070) return;
			else if ($this.attr("CUID") == 'THB' && amount > 2980) return;
			//else if ($this.attr("CUID") == 'VND' && amount > 2082500) return;
			else if ($this.attr("CUID") == 'VND' && amount > 88000) return;
			else if ($this.attr("CUID") == 'MYR' && amount > 305) return;
			else if ($this.attr("CUID") == 'KRW' && amount > 106650) return;
			else if ($this.attr("PAID") == 'BNK82201' && amount > 5000) return;
			else if ($this.attr("PAID") == 'TELCHT05' && amount > 3000) return;
			else if ($this.attr("PAID") == 'TELCHT06' && amount > 2000) return;
			else if ($this.attr("PAID") == 'TELCHT07' && amount > 3000) return;
			else if ($this.attr("PAID") == 'TELTCC01' && amount > 3000) return;
			else if ($this.attr("PAID") == 'TELFET01' && amount > 3000) return;
			else if ($this.attr("PAID") == 'TELSON04' && amount > 2000) return;
						
			html += '<label style="display:block;"><input type="radio" name="payment_amount" class="required" value="'+amount+'" nt='+val+' >'+amount+'</label> ';
		});		
		if ($this.attr("PAID") == 'COPGAM02') {
			$('#choose_form .amount_row').hide();
		}
		else {
			$('#choose_form .amount_row').show();
			$('#choose_form .amount_block').html(html);
			
			var idx = $('#choose_form .amount_block input').length-1;
			$('#choose_form .amount_block input').each(function(i,n){
				if ($(this).val() == 1000) idx = i;
			});			
			if (idx > 3) idx = 3;
			$('#choose_form .amount_block input').eq(idx).click();
			//.prop('checked', true);			
		}
    	$("input[name='PAID']").val($this.attr("PAID"));
    	$("input[name='CUID']").val($this.attr("CUID"));
    	$("input[name='ERP_ID']").val($this.attr("ERP_ID"));
    	
    	$("input[name='pay_type']").val($this.attr("pay_type"));
    	$("input[name='subpay_type']").val($this.attr("subpay_type"));
    	$("input[name='prod_id']").val($this.attr("prod_id"));
    	
    	$('#choose_form').attr('action', $this.attr("action"));
    });	
    $(".gash_global").on("click", function(event){
    	$('#choose_form').attr('action', $('#choose_form').attr('action').replace("tw", "global"));
    });
    
    //$("input.gash_channel']").eq(0).click();
    
    $(".amount_block").on("change", "input", function(event){
    	$("input[name='service_id']").val($(this).attr("service_id"));
   		update_gain_tip(this);
    });
    
    $(".leftside a").on("click", function(){
		$(".leftside a").removeClass("active");
		$(this).addClass("active");
		$("#choose_title").text($(this).text());
	});
    $(".leftside a:first").click();
    
});

function update_gain_tip(obj) {
	var frm = $('#choose_form');
	if (frm.find("select[name='game']").val() && frm.find("input[name='type']:checked").val() == 'game') {
		var game = frm.find("select[name='game'] option:selected");
		var amount = frm.find("input[name='payment_amount']:checked").attr('nt')*game.attr("rate"); 
		frm.find("#gain_tip").text("您將可以獲得 "+amount+game.attr("goldname"));
	}
	else frm.find("#gain_tip").text('');
}

function switch_pay_type(id) {
	if (typeof id == 'undefined') {
		$("#pay_type_block").hide();
	} else $("#pay_type_block").show();
	$(".pay_type").hide();
	$(".pay_type_"+id).show().find("input").eq(0).prop("checked", true).click();
}

