<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>《仙境幻想》龍邑遊戲</title>

<link href="/p/register/xj_winwin/css/index.css" rel="stylesheet" type="text/css" />
<link href="/p/register/xj_winwin/css/site.css" rel="stylesheet" type="text/css" />

<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.8.2/jquery.min.js"></script>
<script type="text/javascript" src="/p/js/jquery.validate.min.js"></script>
<script type="text/javascript" src="/p/js/jquery.form.js"></script>
<script type="text/javascript" src="/p/register/default.js"></script>
<script type="text/javascript" src="/p/register/xj_winwin/js/swfobject.js"></script>

<script>	
var flashvars = {},
params = {wmode:"transparent"},
attributes = {};
swfobject.embedSWF("/p/register/xj_winwin/swf/index.swf", "flashCon", "1000", "720", "9.0.0","/p/register/xj_winwin/swf/expressInstall.swf", flashvars, params, attributes);	

	function showForm(){
	  $("#form").fadeIn(500);
	}

	function getBouns(){$("#getBouns").fadeIn(500);}

	$(function(){
		$('.btn-close').click(function(e){
			e.preventDefault();
			$(this).parent().parent().fadeOut(500);
		})
	})

</script>

</head>

<body>
<div id="main">
	<div id="form">
	<form id="register_form" class="register-form" method="post" action="<?=site_url("gate/register_json/xj")?>" target="_blank">
		<input type="hidden" value="<?=site_url("gate/login_game/xj?ad=winwin")?>" id="redirect_url">
		
        <a class="btn-close" href="#">關閉</a>
    	<div class="rows">
       		<span>龍邑帳號</span>
       		<input type="text" name="account" size="20" maxlength="35" class="form-2 required" minlength="6" value="" />
        </div>
        <div class="rows">
       		<span>龍邑密碼</span>
       		<input type="password" name="pwd" size="20" maxlength="35" class="form-2 required" value="" autocomplete="off" />
        </div>
        <div class="rows">
       		<span>確認密碼</span>
       		<input type="password" name="pwd2" size="20" maxlength="35" class="form-2 required" value="" autocomplete="off" />
        </div>
        <div class="rows">
       		<span>驗證碼</span>
       		<input type="text" name="captcha" size="5" maxlength="4" minlength="4" class="form-2 required" value="" />
       		<em class="verification" style="display:inline-block;" ><div id="captcha_area"></div></em> 
        </div>
        <div class="social-list">
<?php 
	$items = get_channel_items("xj", "../");
	foreach($items as $channel => $name):
?>
	<a href="http://www.long_e.com.tw/gate/login/xj?channel=<?=$channel?>&ad=winwin" target="_blank"><img src="http://www.long_e.com.tw/img/login/<?=$channel?>.png" width="40" title="<?=$name?> 帳號登入"/></a>
<? endforeach;?> 
        </div>
        <a class="btn-start" href="javascript:;" onclick="$('#register_form').submit();">開始遊戲</a>
        </form>
  </div>
  
  <div id="getBouns">
  	<form>
       <a class="btn-close" href="#">關閉</a>
       <div class="rows">
       		<span>創角伺服器</span><select>
              <option>六服「穆拉丁」</option>
       		</select>
      </div>
      <a class="btn-bouns" href="<?=site_url("event/check_xj6_role_exists?guid={$this->input->get("guid")}")?>" target="_blank">確認領取</a>
    </form>
  </div>
    
  	<div id="flashCon">
    <p><a href="http://www.adobe.com/go/getflashplayer"><img src="http://www.adobe.com/images/shared/download_buttons/get_flash_player.gif" alt="Get Adobe Flash player" /></a></p>
  </div>
</div>
</body>
</html>



