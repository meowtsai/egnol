<style type="text/css">
body {background:#e6e6e6; font-family:"微軟正黑體"; font-size:15px;}
#container2 {padding:0;}
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

	$("a").click(function(){
		if (typeof(window.CoozSDK.openCoozPage) !== "undefined" && $(this).attr('op') == '1') {
			window.CoozSDK.openCoozPage($(this).attr('href'));
			return false;
		}
		else if (typeof(window.CoozSDK) !== "undefined" && typeof $(this).attr('openurl') !== 'undefined') {
			window.CoozSDK.openUrl($(this).attr('openurl'));		
			return false;	
		}
		return true;
	});	
});

</script>

<div id="pay_type">
	<h3>請選擇儲值方式</h3>
	<ul>		
		<? if ($this->input->get("eg")):?>
		
		<li><a href="<?=site_url("payment/m_choose3?type=ATM&pay_type=TY-ATM&subpay_type=ST-ATM&prod_id=PD-ATM-CTCB&maximum=30000&&minimue=1000&sid={$sid}&game={$game}")?>">ATM</a></li>
		<? 
				//PD-ATM-CTCB 中國
				//PD-ATM-SCSB 上海商銀
		?>		
		<li><a href="<?=site_url("payment/m_choose3?type=信用卡&pay_type=TY-CREDIT&subpay_type=&prod_id=PD-CREDIT-TSCB&maximum=10000&sid={$sid}&game={$game}")?>" op="1">信用卡</a></li>
		<li><a href="javascript:;" class="switch">固網支付</a>
			<ul class="sub">
				<li><a href="<?=site_url("payment/m_choose2?type=中華電信HiNet&PAID=TELCHT06&CUID=TWD&ERP_ID=J990001&maximum=2000&sid={$sid}&game={$game}")?>">中華電信HiNet</a></li>
				<li><a href="<?=site_url("payment/m_choose2?type=中華電信市話&PAID=TELCHT07&CUID=TWD&ERP_ID=J990001&maximum=3000&sid={$sid}&game={$game}")?>">中華電信市話</a></li>
			</ul>
		</li>		
		<li><a href="javascript:;" class="switch">手機支付</a>
			<ul class="sub">				
				<li><a href="<?=site_url("payment/m_choose2?type=中華電信839&PAID=TELCHT05&CUID=TWD&ERP_ID=J990001&maximum=3000&sid={$sid}&game={$game}")?>">中華電信839</a></li>
				<li><a href="<?=site_url("payment/m_choose2?type=台灣大哥大&PAID=TELTCC01&CUID=TWD&ERP_ID=J990001&maximum=3000&sid={$sid}&game={$game}")?>">台灣大哥大</a></li>
				<li><a href="<?=site_url("payment/m_choose2?type=遠傳電信&PAID=TELFET01&CUID=TWD&ERP_ID=J990001&maximum=3000&sid={$sid}&game={$game}")?>">遠傳電信</a></li>
				<li><a href="<?=site_url("payment/m_choose2?type=亞太電信&PAID=TELSON04&CUID=TWD&ERP_ID=J990001&maximum=3000&sid={$sid}&game={$game}")?>">亞太電信</a></li>		
			</ul>
		</li>
		<li><a href="<?=site_url("payment/m_choose2?type=Gash+實體卡(台灣)&PAID=COPGAM02&CUID=TWD&ERP_ID=PINHALL&sid={$sid}&game={$game}")?>">Gash+實體卡(台灣)</a></li>
		<li><a href="<?=site_url("payment/m_choose2?type=Gash+實體卡(海外 - 香港、菲律賓、馬來西亞)&PAID=COPGAM02&CUID=PIN&ERP_ID=PINHALL&sid={$sid}&game={$game}")?>">Gash+實體卡(海外 - 香港、菲律賓、馬來西亞)</a></li>
		<li><a href="javascript:;" class="switch">其它國家</a>
			<ul class="sub">				
				<li><a href="<?=site_url("payment/m_choose2?type=支付寶&PAID=BNK80804&CUID=TWD&ERP_ID=J990001&sid={$sid}&game={$game}")?>">支付寶</a></li>
				<li><a href="<?=site_url("payment/m_choose2?type=PayPal&PAID=COPPAL01&CUID=TWD&ERP_ID=J990001&sid={$sid}&game={$game}")?>">PayPal</a></li>
				<li><a href="<?=site_url("payment/m_choose3?type=全球信用卡(台幣)&pay_type=TY-CREDIT&subpay_type=&prod_id=PD-CREDIT-TSCB&maximum=10000&sid={$sid}&game={$game}")?>">全球信用卡(台幣)</a></li>
				<li><a href="<?=site_url("payment/m_choose2?type=全球信用卡(歐元)&PAID=BNKRBS01&CUID=EUR&ERP_ID=J990001&convert_rate=0.028&maximum=10000&&sid={$sid}&game={$game}")?>">全球信用卡(歐元)</a></li>
				
				<li><a href="<?=site_url("payment/m_choose2?type=Indonesia untuk membayar(印尼盾)&PAID=COPGV01&CUID=IDR&ERP_ID=J990001&convert_rate=460&sid={$sid}&game={$game}&maximum=100")?>">Indonesia untuk membayar(印尼盾)</a></li>				
				<li><a href="<?=site_url("payment/m_choose2?type=Pilipinas upang bayaran(菲國比索)&PAID=COPMOZ01&CUID=PHP&ERP_ID=J990001&convert_rate=1.7&sid={$sid}&game={$game}&maximum=1000")?>">Pilipinas upang bayaran(菲國比索)</a></li>
				<li><a href="<?=site_url("payment/m_choose2?type=ธนาคารไทยการชำระเงิน(泰銖)&PAID=COPPSB01&CUID=THB&ERP_ID=J990001&sid={$sid}&game={$game}&convert_rate=1.5&maximum=1000")?>">ธนาคารไทยการชำระเงิน(泰銖)</a></li>				
				<li><a href="<?=site_url("payment/m_choose2?type=Thanh Toán Việt Nam(越南幣)&PAID=COPPST01&CUID=VND&ERP_ID=J990001&sid={$sid}&game={$game}&convert_rate=880&maximum=100")?>">Thanh Toán Việt Nam(越南幣)</a></li>
				
				<li><a href="<?=site_url("payment/m_choose2?type=Malaysia Bank untuk Bayar(馬來西亞令吉)&PAID=COPWBC02&CUID=MYR&ERP_ID=J990001&sid={$sid}&game={$game}&convert_rate=0.14&maximum=1000")?>">Malaysia Bank untuk Bayar(馬來西亞令吉)</a></li>
				<li><a href="<?=site_url("payment/m_choose2?type=한국 통신 결제(韓元)&PAID=TELDANAL01&CUID=KRW&ERP_ID=J990001&sid={$sid}&game={$game}&convert_rate=44&maximum=1000")?>">한국 통신 결제(韓元)</a></li>

			</ul>
		</li>			
		
		<? endif;?>
				
		<li><a href="<?=site_url("payment/m_google_choose?partner=".$this->input->get("partner")."&game=".$this->input->get("game"))?>">Google電子錢包</a></li>
	</ul>
</div>
