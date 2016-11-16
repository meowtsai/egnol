<?php 
header('ALLOW-FROM https://vxz.longeplay.com/'); 
header('Access-Control-Allow-Origin: *');  
?>
<style>
*{
	margin:auto 0;
	}
.content{
	width:885px;
	height:368px;
	background-image: url('<?=base_url()?>p/img/event/e02/carryout.png');
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
	top:222px;
	left:410px;	
}	
</style>

<div class="content">
	<!--a href="signin.html"><img src="img/btn.png"></a-->
	<a href="#" class="sw" onclick="javascript:location.href='<?=base_url()?>event/logout?site=vxz'">切換帳號</a>
</div>
