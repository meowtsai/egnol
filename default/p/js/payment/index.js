
var type='';

$(function()
{
	var validation_option = {
		onfocusout: false,
		onkeyup: false,
		onclick: false,
		messages: {
			game: "尚未選擇遊戲",
			server: "尚未選擇伺服器",
			character: "尚未選擇角色",
			billing_type: "尚未選擇儲值方式",
			billing_channel: "尚未選擇支付管道",
			billing_money: "尚未選擇儲值金額"
		},
		showErrors: function(errorMap, errorList)
		{
		   var err = '';
		   $(errorList).each(function(i, v)
		   {
			   err += v.message + "<br/>";
		   });
		   if (err)
		   {
				leOpenDialog('儲值錯誤', err, leDialogType.MESSAGE);
			}
		}
	 }

    $("#choose_form").validate(validation_option);

	var server_pool = $("#server_pool");
	var game = $("select[name='game']");
	game.on('change', function()
	{
		var server = $(this).parents("form").find("select[name='server']");
		server.empty().append("<option value=''>--請選擇伺服器--</option>");
		if (typeof $(this).val() !== 'undefined' && $(this).val() !== '')
		{
			server_pool.find("option."+$(this).val()).clone().appendTo(server);
		}

		var character = $(this).parents("form").find("select[name='character']");
		character.empty().append("<option value=''>--請選擇角色--</option>");

        var option = $("option:selected", this);
		var pay_msg = $('#payment_msg');
		if(typeof option.attr('rate') !== typeof undefined && option.attr('rate') !== false)
		{
			var exchange_rate = parseFloat(option.attr('rate'));
			var msg_html = '『' + option.html() + '』台幣對換遊戲中『' + option.attr('goldname') + '』比值為 <span style="color:#ff0">1:' + exchange_rate;

			msg_html += '</span>。<br />(每 <span style="color:#ff0">100</span> 台幣可獲得 <span style="color:#ff0">' + (exchange_rate * 100) + '</span> ' + option.attr('goldname') + ')';

			pay_msg.html(msg_html);
		}
		else
			pay_msg.html('請先選擇遊戲。');
	});
	game.trigger("change");

	var character_pool = $("#character_pool");
	$("select[name='server']").on('change', function()
	{
		var character = $(this).parents("form").find("select[name='character']");
		character.empty().append("<option value=''>--請選擇角色--</option>");
		if (typeof $(this).val() !== 'undefined' && $(this).val() !== '')
		{
			character_pool.find("option."+$(this).val()).clone().appendTo(character);
		}
	});

    $("select[name='billing_type']").on("change", function ()
	{
        var option = $("option:selected", this);

		$('#choose_form .amount_row').hide();
		$('#choose_form .amount_block').html('');

		if(option.val() == "")
		{
			$('#pay_type_block').hide();
			return;
		}

		if(option.attr("pay_type") == "")
		{
			$("#pay_type_block").hide();
		}
		else
		{
			$("#pay_type_block").show();
			$(".pay_type").hide();
			$(".pay_type_" + option.attr("pay_type")).show();
			
            var currency = $("select[name='currency']").val();
		    $(".currency").hide();
		    $(".currency_" + currency).show();
			return;
		}

        onBillingOptionSelected($("option:selected", this));
	});

    $("select[name='currency']").on("change", function ()
	{
		$('#pay_type_block').hide();
		
        var currency = $("select[name='currency']").val();
		$(".billing_type_opt").show();
		$(".billing_type_opt").not("." + currency).hide();
		return;
	});

    $("select[name='billing_channel']").on("change", function ()
	{
        var option = $("option:selected", this);

		if(option.val() == "")
			return;

        onBillingOptionSelected($("option:selected", this));
	});

	function onBillingOptionSelected(opt)
	{
		var html = '';

		$.each(gash_amount, function(key, val)
		{
			if (eval(val) > opt.attr("maximum")) return;
			if (eval(val) < opt.attr("minimum")) return;

			var amount = (val*(opt.attr("convert_rate")*1000)/1000);
			//if (opt.attr("CUID") == 'IDR' && amount > 964800) return;

			if (opt.attr("CUID") == 'IDR' && amount > 46000) return;
			else if (opt.attr("CUID") == 'PHP' && amount > 4070) return;
			else if (opt.attr("CUID") == 'THB' && amount > 2980) return;
			//else if (opt.attr("CUID") == 'VND' && amount > 2082500) return;
			else if (opt.attr("CUID") == 'VND' && amount > 88000) return;
			else if (opt.attr("CUID") == 'MYR' && amount > 305) return;
			else if (opt.attr("CUID") == 'KRW' && amount > 106650) return;
			else if (opt.attr("PAID") == 'BNK82201' && amount > 5000) return;
			else if (opt.attr("PAID") == 'TELCHT05' && amount > 3000) return;
			else if (opt.attr("PAID") == 'TELCHT06' && amount > 2000) return;
			else if (opt.attr("PAID") == 'TELCHT07' && amount > 3000) return;
			else if (opt.attr("PAID") == 'TELTCC01' && amount > 3000) return;
			else if (opt.attr("PAID") == 'TELFET01' && amount > 3000) return;
			else if (opt.attr("PAID") == 'TELSON04' && amount > 2000) return;

			html += '<option name="payment_amount" value="'+amount+'" nt='+val+' >'+amount+'</option> ';
		});

		if (opt.attr("PAID") == 'COPGAM02')
		{
			$('#choose_form .amount_row').hide();
		}
		else
		{
			$('#choose_form .amount_row').show();
			$('#choose_form .amount_block').html(html);

			var idx = $('#choose_form .amount_block option').length-1;
			$('#choose_form .amount_block option').each(function(i,n)
			{
				if ($(this).val() == 1000) idx = i;
			});
			if (idx > 3) idx = 3;
			$('#choose_form .amount_block option').eq(idx).click();
		}

    	$("input[name='PAID']").val(opt.attr("PAID"));
    	$("input[name='CUID']").val(opt.attr("CUID"));
    	$("input[name='ERP_ID']").val(opt.attr("ERP_ID"));

    	$("input[name='pay_type']").val(opt.attr("pay_type"));
    	$("input[name='subpay_type']").val(opt.attr("subpay_type"));
    	$("input[name='prod_id']").val(opt.attr("prod_id"));

    	$('#choose_form').attr('action', opt.attr("action"));
    }

    $(".gash_global").on("click", function(event)
	{
    	$('#choose_form').attr('action', $('#choose_form').attr('action').replace("tw", "global"));
    });

	$("select[name='game'] option").each(function()
	{
	    var $this = $(this);
		var curGameId = $('#cur_game_id').val();

	    if ($this.val() == curGameId)
		{
	        $this.prop('selected', true);
			game.trigger("change");
	        return false;
	    }
	});
});
