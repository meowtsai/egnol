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
	background-image: url('<?=base_url()?>p/img/mobile/e02/carryout.png');
	}
/*a{
	position:relative;
	top:271px;
	left:350px;
}	*/
.sw{
	font-size:16px;
	font-family:Arial,"微軟正黑體";
	text-align:center;
	color:#EEE;
	position:relative;
	top:240px;	
	left:255px;
}
</style>

<div class="content">
	<!--a href="signin.html"><img src="img/btn.png"></a-->
	<a href="#" class="sw" onclick="javascript:location.href='<?=base_url()?>mobile/logout?site=vxz'">切換帳號</a>
</div>
