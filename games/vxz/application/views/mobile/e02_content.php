<?php 
header('ALLOW-FROM https://vxz.longeplay.com/'); 
header('Access-Control-Allow-Origin: *');  
?>
<style>
*{
	margin:auto 0;
	}
.content{
	width:573px;
	height:362px;
	}
a{
	position:relative;
	top:105px;
	left:189px;
	}
.box1, .box2{
	width:495px;
	height:70px;
	font-size:26px;
	font-family:"微軟正黑體", Arial;
	text-align:center;
	color:#8f8c8c;
	}
.box1{
	position:relative;
	top:56px;
	left:31px;
	}	
.box2{
	position:relative;
	top:85px;
	left:31px;
	}
</style>
<div class="content">
	<form id="login_form" class="login-form" method="post" action="<?=base_url()?>mobile/login_json?site=<?=$site?>">
		<input type="hidden" id="redirect_url" value="<?=base_url()?>mobile/e02_content">
    	<input type="name" name="account" id="name" class="box1 required" placeholder="請輸入帳號">
        <input type="password" name="pwd" id="name" class="box2 required" placeholder="請輸入密碼">
		<input name="doLogin" type="submit" id="doSubmit" value="" style="display:none;" />
    </form>
	<div class="login-button"><a href="#" title="login"><img src="<?=base_url()?>p/img/mobile/e02/btn.png"></a></div>
</div>
