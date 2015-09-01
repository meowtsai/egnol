<?
	$gash_conf = $this->config->item("gash");
	$options = $this->config->item("payment_options");
?>

<script type="text/javascript">
var gash_amount = ['<?= implode("','", $gash_conf["amount"])?>'];
</script>

<div id="content-login">
	<div class="login-ins">
		<div class="bread cf" typeof="v:Breadcrumb">
			<a href="<?=$game_url?>" title="首頁" rel="v:url" property="v:title">首頁</a> > <a href="<?=$longe_url?>payment?site=<?=$site?>" title="儲值中心" rel="v:url" property="v:title">儲值中心</a>
		</div>
		<form id="choose_form" class="choose_form" method="post" action="">
			<input type="hidden" name="PAID">
			<input type="hidden" name="CUID">
			<input type="hidden" name="ERP_ID">

			<input type="hidden" name="pay_type">
			<input type="hidden" name="subpay_type">
			<input type="hidden" name="prod_id">

			<input type="hidden" name="api_call" value="false" />

			<div class="login-form">
				<table class="member_info">
					<tr>
						<th>遊戲名稱</th>
						<td>
							<select name="game" class="required" style="width:85%;">
								<option value="">--請選擇遊戲--</option>
								<? foreach($games->result() as $row): ?>
								<option value="<?=$row->game_id?>" rate="<?=$row->exchange_rate?>" goldname="<?=$row->currency?>" <?=($this->input->get("game")==$row->game_id ? 'selected="selected"' : '')?>><?=$row->name?></option>
								<? endforeach;?>
							</select>
						</td>
						<input type="hidden" id="cur_game_id" value="<?=$site?>">
					</tr>
					<tr>
						<th>伺服器</th>
						<td>
							<select name="server" class="required" style="width:85%;">
								<option value="">--請先選擇伺服器--</option>
							</select>

							<select id="server_pool" style="display:none;">
								<? foreach($servers->result() as $row):
								if ( IN_OFFICE == false && in_array($row->server_status, array("private", "hide"))) continue;?>
								<option value="<?=$row->server_id?>" <?=($this->input->get("server")==$row->server_id ? 'selected="selected"' : '')?> class="<?=$row->game_id?>"><?=$row->name?></option>
								<? endforeach;?>
							</select>
						</td>
					</tr>
					<tr>
						<th>角色名稱</th>
						<td>
							<select name="character" class="required" style="width:85%;">
								<option value="">--請選擇角色--</option>
							</select>

							<select id="character_pool" style="display:none;">
								<? foreach($characters->result() as $row): ?>
								<option value="<?=$row->id?>" class="<?=$row->server_id?>"><?=$row->name?></option>
								<? endforeach;?>
							</select>
						</td>
					</tr>
					<tr>
						<th>儲值方式</th>
						<td>
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
						</td>
					</tr>
					<tr id="pay_type_block" style="display:none;">
						<th>支付管道</th>
						<td>
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
						</td>
					</tr>
					<tr>
						<th></th>
						<td>
						</td>
					</tr>
					<tr class="amount_row" style="display:none;">
						<th>儲值金額</th>
						<td>
							<select name="billing_money"  class="amount_block required" style="width:85%;">
			                    <option value=''>--請選擇儲值金額--</option>

							</select>
						</td>
					</tr>
				</table>

				<div class="login-button">
					<input name="doSubmit" type="submit" id="doSubmit" value="" style="display:none;" />
                    <img style="cursor:pointer;" src="<?=$longe_url?>p/image/money/confirm-btn.png" onclick="javascript:$('#doSubmit').trigger('click')" />
				</div>

				<ul class="notes">
					<li id="payment_msg">點數比值與相關訊息...</li>
				</ul>
			</div>
		</form>
	</div>
</div>
