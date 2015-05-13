<a href="<?=site_url("payment")?>" style="display:block">
	<span class="hd" style="display:inline-block;"></span>	
</a>
<div class="bd">
	<ul>
		<li><a href="<?=site_url("payment")?>" class="sm1 <?=uri_string()=="payment" ? 'active' : ''?>"></a></li>
		<li><a href="<?=site_url("wallet/transfer")?>" class="sm2 <?=uri_string()=="wallet/transfer" ? 'active' : ''?>"></a></li>
		<li><a href="<?=site_url("member/payment_log")?>" class="sm3 <?=uri_string()=="member/payment_log" ? 'active' : ''?>"></a></li>
		<li><a href="<?=site_url("member/wallet_log")?>" class="sm4 <?=uri_string()=="member/wallet_log" ? 'active' : ''?>"></a></li>
	</ul>
</div>