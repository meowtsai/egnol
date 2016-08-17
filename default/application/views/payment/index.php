<?
	$gash_conf = $this->config->item("gash");
	$options = $this->config->item("payment_options");
	
// 判斷是否為行動裝置, 電腦版才要顯示 QR Code
$useragent=$_SERVER['HTTP_USER_AGENT'];
$is_tablet = false;
$is_mobile = false;

if (preg_match('/(tablet|ipad|playbook)|(android(?!.*(mobi|opera mini)))/i', strtolower($_SERVER['HTTP_USER_AGENT'])))
{
    $is_tablet = true;
}
if(preg_match('/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|mobile.+firefox|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows ce|xda|xiino/i',$useragent)||preg_match('/1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i',substr($useragent,0,4)))
{
	$is_mobile = true;
}

$currency_array = array(
    "TWD" => "新台幣TWD",
//    "USD" => "美金USD",
    //"EUR" => "EUR",
//    "HKD" => "港幣HKD",
//    "MYR" => "馬來西亞令吉MYR",
    //"IDR" => "IDR",
    //"THB" => "THB",
    //"THB" => "THB"
);
    
$filename = "./p/payment_disable_list";
$handle = fopen($filename, "r");
$payment_disable_list = fread($handle, filesize($filename));
$payment_disable_array = explode(",", $payment_disable_list);
fclose($handle);
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
				<!--table class="member_info">
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
								if ( IN_OFFICE == false && (in_array($row->server_status, array("private", "hide")) || intval($row->is_transaction_active) != 1)) continue;?>
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
						<th>幣別</th>
						<td>
							<select name="currency"  class="required" style="width:85%;">
								<? foreach($currency_array as $key => $val):?>
			                    <option value='<?=$key?>'><?=$val?></option>
								<?endforeach;?>
							</select>
						</td>
					</tr>
					<tr>
						<th>儲值方式</th>
						<td>
						<? foreach($currency_array as $key => $val):?>
							<select name="billing_type" class="billing_type required <?=$key?>" style="width:85%;<?=($key=='TWD')?"":"display:none;"?>">
			                    <option value=''>--請選擇儲值方式--</option>

								<? foreach($options as $tab => $arr):
									if (array_key_exists("trade", $arr)):
										$attr_str = '';
										foreach($arr['trade'] as $attr => $val) $attr_str .= " {$attr}='{$val}'";
								?>
								<option pay_type="" maximum="<?=$arr['maximum']?>" minimum="<?=$arr['minimum']?>" <?=$attr_str?>><?=$tab?></option>
								<? else:
										$class_str = 'billing_type_opt';
										foreach($arr as $tab2 => $arr2) {
											if($key == $arr2['trade']['cuid']):
								?>
								<option pay_type="<?=$tab?>" class="<?=$class_str?>"><?=$tab?></option>
								<?
												break;
											endif;
										}
									endif;
								endforeach;?>
							</select>
						<?endforeach;?>
						</td>
					</tr>
					<tr id="pay_type_block" style="display:none;">
						<th>支付管道</th>
						<td>
						<? foreach($currency_array as $key => $val):?>
							<? foreach($options as $tab => $arr): ?>
							<select name="billing_channel"  class="pay_type pay_type_<?=$tab?> <?=$key?> required" style="width:85%;">
			                    <option value=''>--請選擇支付管道--</option>

								<? foreach($arr as $opt => $arr2):
                                    if (in_array($opt, $payment_disable_array)) continue;
									if (array_key_exists("trade", $arr)) continue;									
									if ($is_mobile || $is_tablet) {
										if ($arr2['mobile'] == "0") continue;
									} else {
										if ($arr2['mobile'] == "1") continue;
									}
									
									$attr_str = '';
									foreach($arr2['trade'] as $attr => $val) $attr_str .= " {$attr}='{$val}'";
									
									if(($key =='TWD' && !isset($arr2['trade']['cuid'])) || $key == $arr2['trade']['cuid']):
								?>
								<option value="<?=$opt?>" name="gash_channel" class="gash_option currency" maximum="<?=$arr2['maximum']?>" minimum="<?=$arr2['minimum']?>" <?=$attr_str?>><?=$opt?></option>
								<? endif;
								endforeach;?>
							</select>
							<? endforeach;?>
						<?endforeach;?>
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
				</div-->

				<ul class="notes">
					<li id="payment_msg">由於與 Gash 合約即將到期，再請玩家利用 IOS 及 GOOGLE 商店下載包以進行儲值，如造成您的不便，再請見諒。
</li>
				</ul>
			</div>
		</form>
	</div>
</div>
