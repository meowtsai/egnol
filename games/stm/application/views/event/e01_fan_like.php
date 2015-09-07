<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>《時空前線》 同萌大亂鬥</title>
<link href="/p/evt/01/css/reset.css" rel="stylesheet" type="text/css">
<link href="/p/evt/01/css/style.css" rel="stylesheet" type="text/css">
<link href="/p/evt/01/css/animate.css" rel="stylesheet" type="text/css">
<script>
window.onload = function()
{
	FB.getLoginStatus(function(response)
	{
        if(response.status === 'connected')
		{
			location.href = '<?=$game_url?>event/e01_get_reward?token=' + response.authResponse.accessToken;
		}
		else
		{
            document.getElementById("content").style.display = 'block';

			document.getElementById("btnContinue").addEventListener("click", function()
			{
				FB.login(function(response)
				{
			        if(response.status === 'connected')
					{
						location.href = '<?=$game_url?>event/e01_get_reward?token=' + response.authResponse.accessToken;
					}
				}, {scope: 'public_profile,email'});
			});

			document.getElementById("btnSkip").addEventListener("click", function()
			{
				FB.login(function(response)
				{
			        if(response.status === 'connected')
					{
						location.href = '<?=$game_url?>event/e01_get_reward';
					}
				}, {scope: 'public_profile,email'});
			});
		}
	});
}
</script>
</head>

<body>
<div id="fb-root"></div>
<script>(function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) return;
  js = d.createElement(s); js.id = id;
  js.src = "//connect.facebook.net/zh_TW/sdk.js#xfbml=1&version=v2.4&appId=468528283307804";
  fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));</script>
<div id="content" style="display:none;">
<div id="wrap">
<div id="optionallike">
<div class="skip">Skip</div> <span class="divide">or</span> Click Like above then <div id="btnContinue" target="_self" class="btn continue"><i></i> Continue</div>
</div>
<div class="step01">
<div class="box01 animated swing"></div>
</div>
</div>
</div>
</body>
</html>
