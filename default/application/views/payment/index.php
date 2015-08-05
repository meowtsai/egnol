<?
	$gash_conf = $this->config->item("gash");
	$options = $this->config->item("payment_options");
?>

<script type="text/javascript">
var gash_amount = ['<?= implode("','", $gash_conf["amount"])?>'];
</script>

<form id="choose_form" class="choose_form" method="post" action="" target="_blank" >
	<input type="hidden" name="PAID">
	<input type="hidden" name="CUID">
	<input type="hidden" name="ERP_ID">

	<input type="hidden" name="pay_type">
	<input type="hidden" name="subpay_type">
	<input type="hidden" name="prod_id">

	<ul class="le_form">
		<li>儲值中心</li>
		<li class="game_option line_row">
			<div class="field_line">
				<select name="game" class="required" style="width:85%;">
					<option value="">--請選擇遊戲--</option>
					<? foreach($games->result() as $row): ?>
					<option value="<?=$row->game_id?>" rate="<?=$row->exchange_rate?>" goldname="<?=$row->currency?>" <?=($this->input->get("game")==$row->game_id ? 'selected="selected"' : '')?>><?=$row->name?></option>
					<? endforeach;?>
				</select>
			</div>
			<input type="hidden" id="cur_game_id" value="<?=$site?>">
		</li>
		<li class="game_option line_row">
			<div class="field_line">
				<select name="server" class="required" style="width:85%;">
					<option value="">--請先選擇遊戲--</option>
				</select>

				<select id="server_pool" style="display:none;">
					<? foreach($servers->result() as $row):
					if ( IN_OFFICE == false && in_array($row->server_status, array("private", "hide"))) continue;?>
					<option value="<?=$row->server_id?>" <?=($this->input->get("server")==$row->server_id ? 'selected="selected"' : '')?> class="<?=$row->game_id?>"><?=$row->name?></option>
					<? endforeach;?>
				</select>
			</div>
		</li>
		<li>
			<div class="field_line">
				<select name="character" class="required" style="width:85%;">
					<option value="">--請選擇角色--</option>
				</select>

				<select id="character_pool" style="display:none;">
					<? foreach($characters->result() as $row): ?>
					<option value="<?=$row->id?>" class="<?=$row->server_id?>"><?=$row->name?></option>
					<? endforeach;?>
				</select>
			</div>
		</li>
		<li>
			<div style="height:10px;"></div>
		</li>
		<li>
			<div class="field_line">
				<select name="billing_type" class="required" style="width:85%;">
                    <option value=''>--請選擇儲值方式--</option>

					<? foreach($options as $tab => $arr):
						if (array_key_exists("trade", $arr)):
							$attr_str = '';
							foreach($arr['trade'] as $attr => $val) $attr_str .= " {$attr}='{$val}'";
					?>
					<option pay_type="" maximum="<?=$arr['maximum']?>" minimum="<?=$arr['minimum']?>" <?=$attr_str?>><?=$tab?></option>
					<? else:?>
					<option pay_type="<?=$tab?>"><?=$tab?></option>
					<?
						endif;
					endforeach;?>
				</select>
			</div>
		</li>
		<li id="pay_type_block" class="line_row" style="display:none;">
			<div class="field_line" style="display:inline-block;">
				<? foreach($options as $tab => $arr): ?>
				<select name="billing_channel"  class="pay_type pay_type_<?=$tab?> required" style="width:85%;">
                    <option value=''>--請選擇支付管道--</option>

					<? foreach($arr as $opt => $arr2):
						if (array_key_exists("trade", $arr)) continue;
						$attr_str = '';
						foreach($arr2['trade'] as $attr => $val) $attr_str .= " {$attr}='{$val}'";
					?>
					<option value="<?=$opt?>" name="gash_channel" class="gash_option" maximum="<?=$arr2['maximum']?>" minimum="<?=$arr2['minimum']?>" <?=$attr_str?>><?=$opt?></option>
					<? endforeach;?>
				</select>
				<? endforeach;?>
			</div>
		</li>
		<li class="line_row amount_row" style="display:none;">
			<div class="field_line" style="display:inline-block;">
				<select name="billing_money"  class="amount_block required" style="width:85%;">
                    <option value=''>--請選擇儲值金額--</option>

				</select>
			</div>
		</li>
		<li>
			<input tabindex="3" name="send" type="submit" id="send" value="確定" />
		</li>
	</ul>
</form>
