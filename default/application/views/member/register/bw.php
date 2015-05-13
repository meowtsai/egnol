<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>:::龍邑 Web Game:::霸王</title>
<link href="/p/register/bw/css/index.css" rel="stylesheet" type="text/css" />

<script type="text/javascript" src="/p/register/bw/js/jquery-1.8.3.min.js"></script>
<script type="text/javascript" src="/p/js/jquery.validate.min.js"></script>
<script type="text/javascript" src="/p/js/jquery.form.js"></script>
<script type="text/javascript" src="/p/register/default.js"></script>
<script type="text/javascript" src="/p/register/bw/js/swfobject_modified.js"></script>

<script>	
var flashvars = {},
params = {wmode:"transparent"},
attributes = {};
swfobject.embedSWF("/p/register/bw/swf/flash.swf", "flashCon", "1400", "900", "9.0.0","/p/register/bw/swf/expressInstall.swf", flashvars, params, attributes);	

	function showForm(){
	  $("#form").fadeIn(500);
	}
	
	function hideForm(){
		$("#form").fadeOut(500);
	}


</script>

<? if (ENVIRONMENT == "production") $this->load->view("templates/analyticstracking")?>

<? if ($ad == 'google'):?>

				
<? endif;?>


<style type="text/css">
body {
	background-color: #000;
	background-repeat: no-repeat;
	margin-left: 0px;
	margin-top: 0px;
	margin-right: 0px;
	margin-bottom: 0px;
}
#wrap {
	width: 100%;
}
#content {
	height: 586px;
	width: 630px;
	margin-top: 0px;
	margin-right: auto;
	margin-bottom: 0px;
	margin-left: auto;
	position: relative;
	background-image: url(/p/register/bw/img/bg.png);
	background-repeat: no-repeat;
	background-position: center center;
}
#X {
	position: absolute;
	height: 35px;
	width: 34px;
	left: 531px;
	top: 117px;
}
#icon_all {
	position: absolute;
	height: 31px;
	width: 269px;
	left: 272px;
	top: 353px;
}
#enter {
	position: absolute;
	height: 49px;
	width: 136px;
	left: 389px;
	top: 408px;
}
#panel {
	position: absolute;
	height: 111px;
	width: 217px;
	left: 319px;
	top: 234px;
}
#panel ul {
	margin:0;
	padding:0;
	list-style-type:none;
}
#panel ul li {
	margin-top:4px;
	margin-bottom:8px;
	height:20px;
}
input {border:0; background:none; color:#fff; vertical-align:top;}
img {border:0;}
</style>

</head>

<body style="background:#000;">
<div id="main" style="margin:0 auto; width:1400px;">
	<div id="form" style="display:none; position:absolute; margin:27px 0px 0px 158px;">
	<form id="register_form" class="register-form" method="post" action="<?=site_url("gate/register_json/bw")?>">
		<input type="hidden" value="<?=$redirect_url?>" id="redirect_url">
		
<div id="wrap">
  <div id="content">
    <div id="X"><a href="javascript:;" onclick="hideForm()"><img src="/p/register/bw/img/X.png" width="34" height="35" border="0" /></a></div>
     <div id="panel">
    <ul>
    <li>
        <label for="textfield"></label>
        <input type="text" name="account" size="20" maxlength="35" class="form-1 required" minlength="6" value="" />
    </li>
    <li>
        <label for="textfield2"></label>
        <input type="password" name="pwd" size="20" maxlength="35" class="form-1 required" value="" />
      </li>
    <li>
        <label for="textfield3"></label>
        <input type="password" name="pwd2" size="20" maxlength="35" class="form-1 required" value="" />
    </li>
    <li>
        <label for="textfield4"></label>
        <input type="text" name="captcha" size="5" maxlength="4" minlength="4" class="form-1 required" value="" />
	  <span class="verification" style="display:inline-block; margin-left:30px;"><div id="captcha_area"></div></span>
    </li>
    </ul>
</div>
    <div id="icon_all">

<?php 
	$items = get_channel_items("bw", "../");
	foreach($items as $channel => $name):
?>
	<a href="http://www.long_e.com.tw/gate/login/bw?channel=<?=$channel?>&ad=<?=$ad?>"><img src="http://www.long_e.com.tw/img/login/<?=$channel?>.png" width="30" title="<?=$name?> 帳號登入"/></a>
<? endforeach;?>  
</div>

    <div id="enter"><a href="javascript:;" onclick="$('#register_form').submit();"><img src="/p/register/bw/img/enter.png" width="136" height="49" border="0" /></a></div>
  </div>
</div>


    
    </form>
  </div>  

  <div id="flashCon">
    <p><a href="http://www.adobe.com/go/getflashplayer"><img src="http://www.adobe.com/images/shared/download_buttons/get_flash_player.gif" alt="Get Adobe Flash player" /></a></p>
  </div>
</div>
</body>
</html>



