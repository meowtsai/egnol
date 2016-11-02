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
			//character: "尚未選擇角色",
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

		//var character = $(this).parents("form").find("select[name='character']");
		//character.empty().append("<option value=''>--請選擇角色--</option>");

        var option = $("option:selected", this);
		var pay_msg = $('#payment_msg');
		if(typeof option.attr('rate') !== typeof undefined && option.attr('rate') !== false)
		{
			var exchange_rate = parseFloat(option.attr('rate'));
			var msg_html = '『' + option.html() + '』台幣兌換遊戲中『' + option.attr('goldname') + '』比值為 <span style="color:#c00">1:' + exchange_rate;

			msg_html += '</span>。<br />(每 <span style="color:#c00">100</span> 台幣可獲得 <span style="color:#c00">' + (exchange_rate * 100) + '</span> ' + option.attr('goldname') + ')';
			msg_html += '<br />儲值成功後，重新登入遊戲即可獲得' + option.attr('goldname') + '。';

			pay_msg.html(msg_html);
		}
		else
			pay_msg.html('請先選擇遊戲。');
	});
	game.trigger("change");

	/*
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
	*/
    
    $("select[name='currency']").on("change", function ()
	{
        var currency = $("select[name='currency']").val();
		
        onBillingOptionSelected();
		
		return;
	});
    
    $("select[name='billing_money']").on("change", function ()
	{
        $("input[name='amount']").val($(this).val());
		
		return;
	});
    
    onBillingOptionSelected();

	function onBillingOptionSelected()
	{
        var html = '';

        $.each(mycard_amount, function(key, val)
        {
            //if (eval(val) > opt.attr("maximum")) return;
            //if (eval(val) < opt.attr("minimum")) return;

            var currency = $("select[name='currency']").val();

            var amount = (val*(convert_rate[currency]*1000)/1000);
            //if (opt.attr("CUID") == 'IDR' && amount > 964800) return;

            html += '<option name="payment_amount" value="'+amount+'" nt='+val+' >'+amount+'</option> ';
        });

        $('#choose_form .amount_block').html(html);

        var idx = $('#choose_form .amount_block option').length-1;
        $('#choose_form .amount_block option').each(function(i,n)
        {
            if ($(this).val() == 1000) idx = i;
        });
        if (idx > 3) idx = 3;
        $('#choose_form .amount_block option').eq(idx).click();
        
        if ($("input[name='billing_money']").val() == null) $("input[name='amount']").val($("select[name='billing_money']").val());
    }

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
