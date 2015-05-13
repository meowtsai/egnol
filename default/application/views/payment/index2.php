<? 
	$gash_conf = $this->config->item("gash");
?>
			
<style type="text/css">
.leftside {position:absolute;  left:9px; top:67px; }
.leftside li {width:157px; margin-bottom:1px; }
.leftside li a {display:block; padding:10px; background:#aeaeae;}
.leftside li a:hover {background:#ececec}
.leftside li a.active {background:#ccc}
.content {margin:0 30px 0 190px;}
.content li {margin-bottom:5px;}
.content label {color:#a63939}
.field {width:150px; text-align:right; padding:0 9px 0 0; display:inline-block; vertical-align:top;}
</style>

<script type="text/javascript">
<? $amounts = array("100", "300", "500", "1000", "3000", "5000", "10000");?>
var gash_amount = ['<?= implode("','", $amounts)?>'];
</script>

<div style="background:url('/p/img/payment/bg.png') repeat-y">
	<div style="background:url('/p/img/payment/title.png') no-repeat; width:697px; height:64px; margin-top:-2px;">
		<div style="padding:21px 0 0 190px; font-weight:bold;">您目前的選擇儲值方式是 
			<span id="choose_title" style="color:red;">ATM</span>
		</div>
	</div>

	<div class="leftside">
		<ul>
			<li><a href="javascript:;" onclick="choose('ATM', '第一銀行WebATM'); switch_pay_type('a')">ATM</a></li>
			<li><a href="javascript:;" onclick="choose('信用卡', '台灣地區信用卡付款'); switch_pay_type('b');">信用卡</a></li>
			<li><a href="javascript:;" onclick="choose('固網支付', '中華電信HiNet'); switch_pay_type('c')">固網支付</a></li>
			<li><a href="javascript:;" onclick="choose('手機支付', '中華電信市內電話輕鬆付'); switch_pay_type('d');">手機支付</a></li>
			<li><a href="javascript:;" onclick="choose('支付寶AliPay', '支付寶AliPay'); switch_pay_type();">支付寶AliPay</a></li>
			<li><a href="javascript:;" onclick="choose_mycard_ingame();">Mycard實體卡</a></li>
			<? //if (IN_OFFICE):?>
			<li><a href="javascript:;" onclick="choose_other('其它國家'); ">其它國家</a></li>
			<? //endif;?>
		</ul>
	</div>
	
	
	<div class="content">
	
	<form id="choose_form" class="choose_form" method="post" action="/mycard/redirect_mycard_billing" target="_blank">
		<input type="hidden" name="service_id">
		
	  <ul style="min-height:300px;">
	  	<li class="line_row">
			<span class="field">儲值幣別</span>
			<span class="line_field">
				<label><input type="radio" name="type" value="game" checked="checked">遊戲幣</label>
				<label><input type="radio" name="type" value="long_e">龍邑平台點數</label>
			</span>
		</li>
		<li class="game_option line_row">
			<span class="field">請選擇儲值的遊戲</span>
			<span class="line_field">
				<select name="game" class="required">
					<option value="">--請選擇--</option>
					<? foreach($games->result() as $row):?>
					<option value="<?=$row->game_id?>" rate="<?=$row->exchange_rate?>" goldname="<?=$row->currency?>" <?=($this->input->get("game")==$row->game_id ? 'selected="selected"' : '')?>><?=$row->name?></option>
					<? endforeach;?>
				</select>	
			</span>
		</li>
		<li class="game_option line_row">
			<span class="field">請選擇儲值的伺服器</span>
			<span class="line_field">
				<select name="server" class="required">
					<option value="">--請先選擇遊戲--</option>
				</select>
			</span>
		</li>
		<li id="pay_type_block" class="line_row">
			<span class="field">請選擇支付類型</span>
			
			<span class="line_field" style="width:300px; display:inline-block;"> 
				<span class="pay_type pay_type_a" style="display:none">
					<label><input type="radio" name="payment_type" onclick="choose('ATM', '第一銀行WebATM')">線上即時ATM</label><br>
					<label><input type="radio" name="payment_type" onclick="choose('ATM', '中國信託實體ATM')">臨櫃匯款ATM</label>
				</span>
				<span class="pay_type pay_type_c" style="display:none">
					<label><input type="radio" name="payment_type" onclick="choose('固網支付', '中華電信HiNet')">中華電信HiNet</label><br>
					<label><input type="radio" name="payment_type" onclick="choose('固網支付', 'Seednet')">Seednet</label><br>
					<label><input type="radio" name="payment_type" onclick="choose('固網支付', 'So-Net')">So-Net</label>
				</span>
				<span class="pay_type pay_type_d" style="display:none">
					<label><input type="radio" name="payment_type" onclick="choose('手機支付', '中華電信839')">中華電信839</label><br>
					<label><input type="radio" name="payment_type" onclick="choose('手機支付', '中華電信市內電話輕鬆付')">中華電信市內電話輕鬆付</label><br>
					<label><input type="radio" name="payment_type" onclick="choose('手機支付', '台灣大哥大電信')">台灣大哥大電信</label><br>
					<label><input type="radio" name="payment_type" onclick="choose('手機支付', '亞太電信')">亞太電信</label><br>
					<label><input type="radio" name="payment_type" onclick="choose('手機支付', '威寶電信')">威寶電信</label><br>
					<label><input type="radio" name="payment_type" onclick="choose('手機支付', '遠傳電信')">遠傳電信</label>
				</span>			
			</span>
		</li>				
		<li class="line_row">
			<span class="field">請選擇儲值金額</span>
			<span class="line_field">
				<span class="amount_block"></span>
				<span id="gain_tip"></span>
			</span>
		</li>
		<li class="line_row" style="height:25px;"></li>
	  </ul>	  
	  <div style="text-align:center" class="line_row">
	  	<a href="javascript:;" onclick="$('#choose_form').submit()"><img src="/p/img/payment/btn.png"></a>
	  </div>
	</form>
	
	<form id="mycard_ingame_form" class="choose_form" method="post" action="/mycard/ingame" target="_blank" style="display:none;">
		
	  <ul style="min-height:300px;">
	  	<li class="line_row">
			<span class="field">儲值幣別</span>
			<span class="line_field">
				<label><input type="radio" name="type" value="game" checked="checked">遊戲幣</label>
				<label><input type="radio" name="type" value="long_e">龍邑平台點數</label>
			</span>
		</li>
		<li class="game_option line_row">
			<span class="field">請選擇儲值的遊戲</span>
			<span class="line_field">
				<select name="game" class="required">
					<option value="">--請選擇--</option>
					<? foreach($games->result() as $row):?>
					<option value="<?=$row->game_id?>" rate="<?=$row->exchange_rate?>" goldname="<?=$row->currency?>" <?=($this->input->get("game")==$row->game_id ? 'selected="selected"' : '')?>><?=$row->name?></option>
					<? endforeach;?>
				</select>	
			</span>
		</li>
		<li class="game_option line_row">
			<span class="field">請選擇儲值的伺服器</span>
			<span class="line_field">
				<select name="server" class="required">
					<option value="">--請先選擇遊戲--</option>
				</select>
			</span>
		</li>
		<li class="line_row" style="height:25px;"></li>
	  </ul>	  
	  <div style="text-align:center" class="line_row">
	  	<a href="javascript:;" onclick="$('#mycard_ingame_form').submit()"><img src="/p/img/payment/btn.png"></a>
	  </div>
	</form>	

	<form id="choose_other_form" class="choose_form" method="post" action="/gash/order?country=global" target="_blank" style="display:none;">
		<input type="hidden" name="PAID">
		<input type="hidden" name="CUID">
		<input type="hidden" name="ERP_ID">
		
	  <ul style="min-height:300px;">
	  	<li class="line_row">
			<span class="field">儲值幣別</span>
			<span class="line_field">
				<label><input type="radio" name="type" value="game" checked="checked">遊戲幣</label>
				<label><input type="radio" name="type" value="long_e">龍邑平台點數</label>
			</span>
		</li>
		<li class="game_option line_row">
			<span class="field">請選擇儲值的遊戲</span>
			<span class="line_field">
				<select name="game" class="required">
					<option value="">--請選擇--</option>
					<? foreach($games->result() as $row):?>
					<option value="<?=$row->game_id?>" rate="<?=$row->exchange_rate?>" goldname="<?=$row->currency?>" <?=($this->input->get("game")==$row->game_id ? 'selected="selected"' : '')?>><?=$row->name?></option>
					<? endforeach;?>
				</select>	
			</span>
		</li>
		<li class="game_option line_row">
			<span class="field">請選擇儲值的伺服器</span>
			<span class="line_field">
				<select name="server" class="required">
					<option value="">--請先選擇遊戲--</option>
				</select>
			</span>
		</li>
		<li id="pay_type_block" class="line_row">
			<span class="field">請選擇支付管道</span>			
			
			<span class="line_field" style="width:300px; display:inline-block;">				
				<? foreach($gash_conf["items"] as $name => $item):?>
				<label><input type="radio" name="gash_channel" PAID="<?=$item["PAID"]?>" CUID="<?=$item["CUID"]?>" ERP_ID="<?=$item["ERP_ID"]?>" convert_rate="<?=$gash_conf["converter"][$item["CUID"]]?>">
					<?=$name?></label><br>
				<? endforeach;?>		
			</span>
		</li>				
		<li class="line_row amount_row">
			<span class="field">請選擇儲值金額</span>
			<span class="line_field">
				<span class="amount_block"></span>
				<span id="gain_tip"></span>
			</span>
		</li>
		<li class="line_row" style="height:25px;"></li>
	  </ul>	  
	  <div style="text-align:center" class="line_row">
	  	<a href="javascript:;" onclick="$('#choose_other_form').submit()"><img src="/p/img/payment/btn.png"></a>
	  </div>
	</form>
<!--         
	<div style="margin:3px 0 0 -29px;">
		<img src="/p/img/payment/line.png">
	</div>
	 -->	
	
  <div style="padding:12px 0;">
		<? $this->load->view("payment/_note")?>  
  </div>             
  
  <select id="server_pool" style="display:none;">
		<? foreach($servers->result() as $row):
			if ( IN_OFFICE == false && in_array($row->server_status, array("private", "hide"))) continue;?>
		<option value="<?=$row->id?>" <?=($this->input->get("server")==$row->id ? 'selected="selected"' : '')?> class="<?=$row->game_id?>"><?=$row->name?></option>
		<? endforeach;?>
	</select>	
</div>

    
        </div>