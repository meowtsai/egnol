<?php 
header('ALLOW-FROM https://vxz.longeplay.com/'); 
header('Access-Control-Allow-Origin: *');  
?>
<? 
$r2g_servers = array(
	"r2g01" => "移花宮",
	"r2g02" => "惡人谷",
	"r2g03" => "繡玉谷"
);
?>
<style>
.content {
	width:885px;
	height:368px;
	font-size:24px;
	font-family:Arial,"微軟正黑體";
	text-align:center;
    overflow: scroll;
	color:#FFF;
}	
.datefont{
	font-size:16px;
	color:#DDD;
}	
.t7{
	position:relative;
	top:80px;
	left:0px;	
}		
</style>
<div class="content">
		<div>&nbsp;</div>
		<?if($billing_list->num_rows > 0): ?>
			<?foreach($billing_list->result() as $row):?>
				<div><?=' '.$row->name.'('.$r2g_servers[$row->server_id].') '.$row->amount.'元 ';?></div>
				<div class="datefont"><?=$row->create_time?></div>
			<?endforeach;?>
		<?else:?>
			<div>此帳號無訂單</div>
		<?endif;?>
	<div class="t7"><a href="<?=base_url()?>event/e02_content"><img src="<?=base_url()?>p/img/event/e02/btn.png"></a></div>
</div>