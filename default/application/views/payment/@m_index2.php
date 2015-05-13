<style type="text/css">
body {background:#e6e6e6; font-family:"微軟正黑體"; font-size:15px;}
#container {padding:0;}
h3 {border-bottom:1px solid #ccc; border-top:3px solid #493; padding:6px 16px; background:#d2d2d2; color:#261;}
#pay_type {}
#pay_type li {border-top:1px solid #fff; border-bottom:1px solid #ccc;}
#pay_type li a {display:block; padding:12px 16px; color:#003;}

#pay_type .sub {display:none;}
#pay_type .sub li {background:#fff; border-bottom:1px solid #fff; border-top:1px solid #d6d6d6;}
#pay_type .sub li a {padding:9px 16px;}
</style>

<script type="text/javascript">
$(function(){
	$('#pay_type').on('click', '.switch', function(){
		var sub = $(this).parents("li:first").find(".sub");
		if (sub.css('display') == 'none') sub.show();
		else sub.hide();
	});	
});
</script>

<div id="pay_type">
	<h3>請選擇儲值方式</h3>
	<ul>
		<li><a href="<?=site_url("payment/m_google_choose?partner=".$this->input->get("partner")."&game=".$this->input->get("game"))?>">Google電子錢包</a></li>
		
		<? if ($this->input->get("eg")):?>
		<li><a href="<?=site_url("payment/m_choose?type=中國信託實體ATM&sid={$sid}")?>">ATM</a></li>
		<li><a href="<?=site_url("payment/m_choose?type=台灣地區信用卡付款&sid={$sid}")?>">信用卡</a></li>
		<li><a href="javascript:;" class="switch">固網支付</a>
			<ul class="sub">
				<li><a href="<?=site_url("payment/m_choose?type=中華電信HiNet&sid={$sid}")?>">中華電信HiNet</a></li>
				<li><a href="<?=site_url("payment/m_choose?type=Seednet&sid={$sid}")?>">Seednet</a></li>
				<li><a href="<?=site_url("payment/m_choose?type=So-Net&sid={$sid}")?>">So-Net</a></li>
			</ul>
		</li>
		<li><a href="javascript:;" class="switch">手機支付</a>
			<ul class="sub">
				<li><a href="<?=site_url("payment/m_choose?type=中華電信839&sid={$sid}")?>">中華電信837</a></li>
				<li><a href="<?=site_url("payment/m_choose?type=中華電信市內電話輕鬆付&sid={$sid}")?>">中華電信市內電話輕鬆付</a></li>
				<li><a href="<?=site_url("payment/m_choose?type=台灣大哥大電信&sid={$sid}")?>">台灣大哥大電信</a></li>
				<li><a href="<?=site_url("payment/m_choose?type=亞太電信&sid={$sid}")?>">亞太電信</a></li>
				<li><a href="<?=site_url("payment/m_choose?type=威寶電信&sid={$sid}")?>">威寶電信</a></li>
				<li><a href="<?=site_url("payment/m_choose?type=遠傳電信&sid={$sid}")?>">遠傳電信</a></li>
			</ul>
		</li>
		<li><a href="<?=site_url("payment/m_choose?type=支付寶AliPay&sid={$sid}")?>">支付寶AliPay</a></li>
		<li><a href="<?=site_url("mycard/ingame")?>">Mycard實體卡</a></li>
		<? endif;?>
		
	</ul>
</div>
