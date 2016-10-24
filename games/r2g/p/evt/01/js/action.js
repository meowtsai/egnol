function OnClickRegister()
{
	$('#register_form').attr('action', '/event/e01_result?site=r2g');
    $('#doSubmit').trigger('click');
}

function OnClickCheckReward()
{
	$('#register_form').attr('action', '/event/e01_check_reward?site=r2g');
    $('#doSubmit').trigger('click');
}

function OnClickLogo()
{
	leOpenDialog('絕代雙驕', "敬請期待！", leDialogType.MESSAGE);
}

function OnClickGooglePlay()
{
	leOpenDialog('絕代雙驕', "敬請期待！", leDialogType.MESSAGE);
}

var preloadImages = [];

for(var cnt1 = 0; cnt1 < 5; cnt1++)
{
    preloadImages.push(new Image());
    preloadImages[cnt1].src = "/p/evt/01/images/progress-0" + cnt1 + ".png";
}

$(function()
{
	$("#register_form").validate({
		onfocusout: false,
		onkeyup: false,
		onclick: false,
		messages:
		{
			account: {
				required: "E-Mail或手機號碼必填"
			},
			pwd: {
				required: "`密碼`尚未填寫",
				minlength: "`密碼`最少6碼",
				maxlength: "`密碼`最多18碼",
			},
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
               leOpenDialog('錯誤', err, leDialogType.MESSAGE);
               //alert(err);
		   }
		},
		submitHandler: function(form)
		{
			$(form).ajaxSubmit({
				dataType: 'json',
				success: function(json)
				{
					if (json.status == 'success')
					{
						if(json.new == '1')
						{
							// 新增登錄
							ga('send', 'pageview', '/event/e01_register/get_sn');
							ga('r2gTracker.send', 'pageview', '/event/e01_register/get_sn');
							
							fbq('track', 'CompleteRegistration');
						}

						if(json.action == 'register')
						{
							leOpenDialog('註冊成功', '恭喜完成註冊！', leDialogType.MESSAGE, function()
							{
								$('input').prop('disabled', true);
								$('.register_reward').css('display', 'block');
								$('.form_steps').css('display', 'none');
								$('#reward_sn').html(json.sn);
							});
						}
						else if(json.action == 'check_reward')
						{
							$('input').prop('disabled', true);
							$('.register_reward').css('display', 'block');
							$('.form_steps').css('display', 'none');
							$('#reward_sn').html(json.sn);
						}
						else
						{
							leOpenDialog('絕代雙驕', json.message, leDialogType.MESSAGE);
                            //alert(json.message);
						}
					}
					else
					{
						leOpenDialog('錯誤', json.message, leDialogType.MESSAGE);
                        //alert(json.message);
					}
				}
			});
		}
	});
    
    var currentCount = 0;
    var nextCount = 0;
	
	function getCount()
    {
        $.get( "/event/e01_get_count", function(data)
        {
            var json = JSON.parse(data);
            nextCount = json.count;
        });
    }
	
	getCount();
    setInterval(getCount, 5000);
    
    setInterval(function()
    {
        if(!$('#counter').visible() && !$('#register_count').visible() && !$('#progress_image').visible())
            return;
        
		if(nextCount == 0)
		{
			$('.counter').html(currentCount.toString());
			return;
		}
		
        var sub = nextCount - currentCount;
        if(sub > 0)
        {
            var inc = Math.floor(sub / 10);
            if(inc < 1)
                inc = 1;
            
            currentCount += inc;
            if(currentCount > nextCount)
                currentCount = nextCount;
            
            $('.counter').html(currentCount.toString());
            
            if(currentCount < 1000)
                $('#progress_image').attr('src', '/p/evt/01/images/progress-00.png');
            else if(currentCount < 5000)
                $('#progress_image').attr('src', '/p/evt/01/images/progress-01.png');
            else if(currentCount < 10000)
                $('#progress_image').attr('src', '/p/evt/01/images/progress-02.png');
            else if(currentCount < 20000)
                $('#progress_image').attr('src', '/p/evt/01/images/progress-03.png');
            else
                $('#progress_image').attr('src', '/p/evt/01/images/progress-04.png');
        }
    }, 100);
	
	setTimeout(function()
	{
		window.alert('《絕代雙驕》事前登錄活動已順利結束!\n本活動頁僅供活動序號查詢\n雙商店公測已於2016/4/26 中午12:00 正式啟動\n最新活動訊息請大俠至官方粉絲團查閱!');
	}, 1000);
});