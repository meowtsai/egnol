<? 
	$pepay_conf = $this->config->item("pepay");
	
	if ($game == 'zj') {
		$amounts = $pepay_conf["m_amount"];
	}
	else {
		$amounts = $pepay_conf["amount"];
	}
?>
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
		$('input[name=payment_amount]').val($(this).attr('payment_amount'));
		$('#choose_form').submit();
	});
});
</script>

<form id="choose_form" class="choose_form" method="post" action="/pepay/order">
		<input type="hidden" name="pay_type" value="<?=$this->input->get('"pay_type"')?>">
		<input type="hidden" name="subpay_type" value="<?=$this->input->get('subpay_type')?>">
		<input type="hidden" name="prod_id" value="<?=$this->input->get('prod_id')?>">
		<input type="hidden" name="server" value="<?=$this->input->get('sid')?>">
		<input type="hidden" name="payment_amount">
</form>
		
<div id="pay_choose">
	<h3><b><?=$type?></b>, 請選擇金額</h3>
	<ul>
		<!-- <li><a href="<?=site_url("payment/m_index")?>"> 《 返回</a></li> -->
		<? foreach($amounts as $amount):
			if ($this->input->get("maximum") && $this->input->get("maximum") < $amount) continue;
			if ($this->input->get("minimue") && $this->input->get("minimue") > $amount) continue; 
		?>
		<li><a href="javascript:;" payment_amount="<?=$amount?>">NT <?=$amount?></a></li>
		<? endforeach;?>
	</ul>
</div>

