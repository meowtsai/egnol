<? 
	$gash_conf = $this->config->item("gash");
	if ($game == 'zj') {
		$amounts = $gash_conf["m_amount"];
	}
	else {
		$amounts = $gash_conf["amount"];
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
	if ('<?=$this->input->get('PAID')?>' == 'COPGAM02' || '<?=$this->input->get('PAID')?>' == 'COPGAM05') {
		$('#choose_form').submit();
	}	

});
</script>
<? 
	if ($this->input->get("CUID") == 'TWD') $country = 'tw';
	else $country = 'global';
?>
<form id="choose_form" class="choose_form" method="post" action="/gash/order?country=<?=$country?>">
		<input type="hidden" name="PAID" value="<?=$this->input->get('PAID')?>">
		<input type="hidden" name="CUID" value="<?=$this->input->get('CUID')?>">
		<input type="hidden" name="ERP_ID" value="<?=$this->input->get('ERP_ID')?>">
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
			
// 			if ($this->input->get('PAID') == 'BNK82201' && $amount > 3000) continue;
// 			else if ($this->input->get('PAID') == 'TELCHT05' && $amount > 3000) continue;
// 			else if ($this->input->get('PAID') == 'TELCHT06' && $amount > 2000) continue;
// 			else if ($this->input->get('PAID') == 'TELCHT07' && $amount > 3000) continue;
// 			else if ($this->input->get('PAID') == 'TELTCC01' && $amount > 3000) continue;
// 			else if ($this->input->get('PAID') == 'TELFET01' && $amount > 3000) continue;
// 			else if ($this->input->get('PAID') == 'TELSON04' && $amount > 3000) continue;
			
			$payment_amount = $amount;
			if ($this->input->get("convert_rate")) $payment_amount=$amount*$this->input->get("convert_rate");
		?>
		<li><a href="javascript:;" payment_amount="<?=$payment_amount?>">NT <?=$amount?></a></li>
		<? endforeach;?>
	</ul>
</div>

