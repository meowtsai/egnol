<style type="text/css">
body {background:#e6e6e6; font-family:"微軟正黑體"; font-size:15px;}
#container2 {padding:0;} 
h3 {border-bottom:1px solid #ccc; border-top:3px solid #493; padding:6px 16px; background:#d2d2d2; color:#261;}
#pay_choose {}
#pay_choose li {border-top:1px solid #fff; border-bottom:1px solid #ccc;}
#pay_choose li a {display:block; padding:12px 16px; color:#003;}
</style>

<script type="text/javascript">
$(function(){
	$('#pay_choose').on('click', 'a', function(){
		window.CoozSDK.launchPurchase($(this).attr('sku'));
	});	
});
</script>
		
<div id="pay_choose">
	<h3><b>Google電子錢包</b>, 請選擇金額</h3>
	<ul>
		<!-- <li><a href="<?=site_url("payment/m_index")?>"> 《 返回</a></li> -->
		<? foreach($products as $sku => $money):?>
		<li><a href="javascript:;" sku="<?=$sku?>">NT <?=$money?></a></li>
		<? endforeach;?>
	</ul>
</div>

