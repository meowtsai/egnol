<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>《時空前線》 同萌大亂鬥</title>
<link href="/p/css/e01_fan_like/reset.css" rel="stylesheet" type="text/css">
<link href="/p/css/e01_fan_like/style.css" rel="stylesheet" type="text/css">
<link href="/p/css/e01_fan_like/animate.css" rel="stylesheet" type="text/css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.8.2/jquery.min.js"></script>
<!-- Swap img -->
<script type="text/javascript">
function getParameterByName(name) {
    name = name.replace(/[\[]/, "\\[").replace(/[\]]/, "\\]");
    var regex = new RegExp("[\\?&]" + name + "=([^&#]*)"),
        results = regex.exec(location.search);
    return results === null ? "" : decodeURIComponent(results[1].replace(/\+/g, " "));
}

OnGetReward = function () {}

$(document).ready(function()
{
	$("img.rollover").hover(
	function() { this.src = this.src.replace("_off", "_on");
	},
	function() { this.src = this.src.replace("_on", "_off");
	});

	$.getScript('//connect.facebook.net/zh_TW/sdk.js', function()
	{
		FB.init({
			appId: '468528283307804',
			version: 'v2.4'
		});

		FB.api('/me', { 'access_token': getParameterByName('token') }, function(response)
		{
        	document.getElementById("wrap").style.display = 'block';

			OnGetReward = function()
			{
				location.href = 'https://stm.longeplay.com.tw/event/e01_success?uid=' + response.id;
			}
		});
	});
});
</script>
</head>

<body>
<div id="wrap" style="display:none">
<div class="step02">
<div class="box02 animated bounce">
<a href="javascript:OnGetReward()"><img class="rollover" src="/p/img/e01_fan_like/box_02_btn_on.png"/></a></div>
</div>
</div>
</body>
</html>
