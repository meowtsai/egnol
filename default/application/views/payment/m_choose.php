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
		$('input[name=service_id]').val($(this).attr('service_id'));
		$('input[name=payment_amount]').val($(this).attr('payment_amount'));
		$('#choose_form').submit();
	});	
});
</script>

<form id="choose_form" class="choose_form" method="post" action="/mycard/redirect_mycard_billing">
		<input type="hidden" name="service_id">
		<input type="hidden" name="server" value="<?=$sid?>">
		<input type="hidden" name="payment_amount">
</form>
		
<div id="pay_choose">
	<h3><b><?=$type?></b>, 請選擇金額</h3>
	<ul>
		<!-- <li><a href="<?=site_url("payment/m_index")?>"> 《 返回</a></li> -->
		<? foreach($data as $money => $service_id):?>
		<li><a href="javascript:;" service_id="<?=$service_id?>" payment_amount="<?=$money?>">NT <?=$money?></a></li>
		<? endforeach;?>
	</ul>
</div>

