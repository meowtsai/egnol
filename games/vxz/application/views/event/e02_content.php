<?php header('X-Frame-Options: SAMEORIGIN'); ?>
<style>
*{
	margin:auto 0;}
.content{
	width:885px;
	height:368px;
	}
a{
	position:relative;
	top:146px;
	left:350px;
	}
.box1, .box2{
	width:500px;
	height:50px;
	font-size:20px;
	font-family:"微軟正黑體", Arial;
	text-align:center;
	color:#8f8c8c;
	}
.box1{
	position:relative;
	top:80px;
	left:190px;
	}	
.box2{
	position:relative;
	top:114px;
	left:190px;
	}
</style>
	
<div class="content">
	<form id="login_form" class="login-form" method="post" action="<?=$longe_url?>member/login_json?site=<?=$site?>">
		<input type="hidden" id="redirect_url" value="<?=base_url()?>event/e02_content">
    	<input type="name" name="account" id="name" class="box1 required" placeholder="請輸入帳號">
        <input type="password" name="pwd" id="name" class="box2 required" placeholder="請輸入密碼">
		<input name="doLogin" type="submit" id="doSubmit" value="" style="display:none;" />
    </form>
	<div class="login-button"><a href="#" title="login"><img src="<?=base_url()?>p/img/event/e02/btn.png"></a></div>
</div>
