<? 
	$enable = $this->config->item("enable");
	$enable[0]['color'] = "#600";
	$enable[1]['color'] = "#006";
	
	$ad_channels = $this->config->item('ad_channels');
	$extra_ad_channels = $this->config->item('extra_ad_channels');
	
	foreach($extra_ad_channels as $arr) {
		$ad_channels = array_merge($ad_channels, $arr);
	}
	
	if (!$user->email && !$user->mobile) {
		$ex_id = explode("@",$user->external_id); 
		if ('device' == $ex_id[1]) $account = "快速登入";
		else $account = $ex_id[1];
	} else {
		if ($user->email) $account = $user->email;
		$account = $user->mobile;
	}
    
	$this->load->config("g_service");
	$question_type = $this->config->item('question_type');
	$question_status = $this->config->item('question_status');
    
    $result_table = array(
        "0" => array("name" => "初始", "class" => "error"), 
        "1" => array("name" => "成功", "class" => "success"), 
        "2" => array("name" => "失敗", "class" => "warning"),
        "3" => array("name" => "交易逾時", "class" => "error"),
        "4" => array("name" => "其它", "class" => "info"),
        "5" => array("name" => "等候入點", "class" => "success"),
    );
?>

<legend><?=$account?> (<?=$user->uid?>)&nbsp;基本資料</legend>

<div id="func_bar" style="text-align:right;">

<? if ( ! empty($user->bind_uid)):
	$u = $this->db->where("uid", $user->bind_uid)->from("users")->get()->row(); 
?>
	<p>此帳號為 <a href="<?=site_url("member/view/{$u->uid}")?>"><?=$u->uid?></a> 的綁定帳號</p>

<? else:?>
	
	<? if ($user->is_banned == 1):?>
		<a href="javascript:;" class="btn btn-small json_post_confirm" url="<?=site_url("member/set_right/{$user->uid}/0")?>"><i class="icon icon-ok-circle"></i> 解除停權</a>
	<? else:?>	
		<input type="text" id="stop_cause" style="width:200px; margin-bottom:0;" placeholder="停權原因">
		<a href="javascript:;" class="btn btn-small" url="<?=site_url("member/set_right/{$user->uid}/1")?>"
			onclick="javascript:if(confirm('確定要執行嗎?')){$.json_post($(this).attr('url'), {cause:$('#stop_cause').val()}, function(json){location.reload();});}"><i class="icon icon-ban-circle"></i> 停權</a>
	<? endif;?>

<? endif;?>

<a href="<?=site_url("member/switch_account/{$user->uid}")?>" class="btn btn-small" target="_blank"><i class="icon icon-retweet"></i> 登入他的帳號</a>

</div>
	
<table class="table table-bordered" style="width:750px">
	<tbody>
		<tr>
            <th>帳號</th>
            <td><?
                if (!$user->email && !$user->mobile) {
                    $ex_id = explode("@",$user->external_id); 
                    if ('device' == $ex_id[1]) echo "快速登入";
                    else echo $ex_id[1];
                } else {
                    if ($user->email) echo $user->email;
                    else echo $user->mobile;
                }
            ?></td>
            <th>Line</th>
            <td><?=$user->line?></td>
		</tr>
		<tr>
            <th>uid</th>
            <td><?=$user->uid?></td>
            <th>euid</th>
            <td><?=$this->g_user->encode($user->uid)?></td>
		</tr>
		<tr>
            <th>姓名</th>
            <td><?=$user->name?>&nbsp;</td>
            <th>身分證</th>
            <td><?=$user->ident?></td>
		</tr>
		<tr>
            <th>性別</th>
            <td><?
            if($user->sex==1) {echo '男';}
            else if($user->sex==2) {echo '女';}
            else {echo '';}
            ?></td>
            <th>生日</th>
            <td><?=$user->birthday?>&nbsp;</td>
		</tr>
		<tr>
            <th>信箱</th>
            <td><?=$user->email?>&nbsp;</td>
            <th>地址</th>
            <td><?=$user->street?>&nbsp;</td>
		</tr>
		<tr>
            <th>手機</th>
            <td><?=$user->mobile?>&nbsp;</td>
            <th>帳號狀態</th>
            <td>
            <? if ($user->is_banned == 1):?>
                <span style="color:red">停權</span> 
                <div><?=$user->ban_date?></div>
                <div><?=$user->ban_reason?></div>
            <? else:?>
                <span style="color:#006900">正常</span><br>
            <? endif;?>
            </td>
		</tr>
		<tr>
            <th>註冊時間</th>
            <td><?=$user->create_time?></td>
            <th>最後登入時間</th>
            <td><?=$user->last_login_date?>&nbsp;</td>
		</tr>
		<tr>
			<th>備註：</th>
			<td colspan="3">
				<form id="note_form" method="post" action="<?=site_url("member/update_note_json")?>" style="margin:0">
					<input type="hidden" name="uid" value="<?=$user->uid?>">	
					<textarea name="note" rows="3" style="width:80%" ><?=$user->note?></textarea>
					<button type="submit" class="btn" style="vertical-align:top;">儲存</button>
				</form>  			
			</td>
		</tr>
	</tbody>
</table>

<legend>客服近期詢問記錄</legend>

<table class="table table-striped table-bordered" style="width:750px">
	<thead>
		<tr>
			<th style="width:60px;">#</th>
			<th style="width:80px">伺服器</th>
			<th style="width:400px">描述</th>
			<th style="width:80px;">狀態</th>
			<th style="width:100px;">日期</th>
		</tr>
	</thead>
	<tbody>
		<? if ($questions->num_rows() == 0):?>
				
		<tr>
			<td colspan="10">
				<div style="padding:10px; color:#777;">查無記錄</div>
			</td>
		</tr>

		<? else:?>
		
		<? foreach($questions->result() as $row):?>
		<tr>
			<td><a href="<?=site_url("service/view/{$row->id}")?>"><?=$row->id?></a></td>
			<td><?=$row->server_id?></td>
			<? if ($row->type == '9'):?>
			<td colspan="3">
				<span style="font-size:12px;">【<?=$question_type[$row->type]?>】</span>
				<a href="<?=site_url("service/view/{$row->id}")?>"><?=mb_strimwidth(strip_tags($row->content), 0, 98, '...', 'utf-8')?></a>
			</td>
			<td><?=$question_status[$row->status]?>
				<div style="font-size:11px;"> 
				<?  if ($row->allocate_status == '1'):?>
					<span style="color:#999">(後送中)</span>
				<? elseif ($row->allocate_status == '2'):?>
					<span style="color:#090">(後送完成)</span>
				<? endif;?>
				</div>
			</td>						
			<? else:?>
			<td style="word-break: break-all">
				<span style="font-size:12px;">【<?=$question_type[$row->type]?>】</span>
				<a href="<?=site_url("service/view/{$row->id}")?>"><?=mb_strimwidth(strip_tags($row->content), 0, 66, '...', 'utf-8')?></a>
			</td>
			<td><?=$question_status[$row->status]?>
			</td>			
			<? endif;?>			
			<td><?=date("Y-m-d", strtotime($row->create_time))?></td>
		</tr>
		<? endforeach;?>
		<? endif;?>
		
	</tbody>
</table>

<a href="<?=site_url("service/get_list?uid={$user->uid}&action=查詢")?>" class="btn btn-link btn-small">完整客服記錄</a>

<br><br>

<legend>遊戲資料</legend>

<table style="width:750px">
    <tr>
        <td style="width:250px">
            <form id="choose_form" class="choose_form" method="post" action="<?=site_url("member/view/{$user->uid}")?>">
                <table class="member_info">
                    <tr>
                        <td>
                            <select name="game" class="required" style="">
                                <option value="">--請選擇遊戲--</option>
                                <?$exchange_rate=1;?>
                                <? foreach($games->result() as $row): ?>
                                <?if($this->input->post("game")==$row->game_id) $exchange_rate=$row->exchange_rate?>
                                <option value="<?=$row->game_id?>" rate="<?=$row->exchange_rate?>" goldname="<?=$row->currency?>" <?=($this->input->post("game")==$row->game_id ? 'selected="selected"' : '')?>><?=$row->name?><?=($row->is_active)?"":"(已關閉)"?></option>
                                <? endforeach;?>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <select name="server" class="required" style="">
                                <option value="">--請先選擇伺服器--</option>
                            </select>
                            <select id="server_pool" style="display:none;">
                                <? foreach($servers->result() as $row):
                                if ( IN_OFFICE == false && in_array($row->server_status, array("private", "hide"))) continue;?>
                                <option value="<?=$row->server_id?>" <?=($this->input->post("server")==$row->server_id ? 'selected="selected"' : '')?> class="<?=$row->game_id?>"><?=$row->name?><?=($row->is_transaction_active)?"":"(伺服器金流已關閉)"?></option>
                                <? endforeach;?>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <select name="character" class="required" style="">
                                <option value="">--請選擇角色--</option>
                            </select>

                            <select id="character_pool" style="display:none;">
                                <? foreach($characters->result() as $row): ?>
                                <option value="<?=$row->id?>" <?=($this->input->post("character")==$row->id ? 'selected="selected"' : '')?> class="<?=$row->server_id?>"><?=$row->name?></option>
                                <? endforeach;?>
                            </select>
                        </td>
                    </tr>
                </table>
            </form>

            <?if ($balance):?>
            <table class="table table-striped table-bordered">
                <thead>
                    <tr>
                        <th>累積儲值金額</th>
                        <th>儲值獲得</th>
                        <th>活動贈與</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><?=$balance->aq?></td>
                        <td><?=$exchange_rate*$balance->aq?></td>
                        <td><?=$balance->gq?></td>
                    </tr>
                </tbody>
            </table>
            <?endif;?>
        </td>
        <td style="width:450px;vertical-align:top;">   
            <table class="table table-striped table-bordered">
                <caption>遊戲角色</caption>
                <thead>
                    <tr>
                        <th>遊戲</th>
                        <th>角色名稱</th>
                        <th>原廠角色ID</th>
                        <th>廣告來源</th>
                        <th>創建時間</th>
                        <th>最後登入時間</th>
                    </tr>
                </thead>
                <tbody>
                  <? if ($role->num_rows() > 0):?>
                    <? foreach($role->result() as $row): fb($row)?>
                    <tr>
                        <td><?=$row->game_name?> - <?=$row->server_name?></td>
                        <td><?=$row->name?></td>
                        <td><?=$row->in_game_id?></td>
                        <td><?=array_key_exists($row->ad, $ad_channels) ? $ad_channels[$row->ad] : '無'?></td>
                        <td><?=$row->create_time?></td>
                        <td><?=$row->last_login_time?></td>
                    </tr>
                    <? endforeach;?>
                  <? else:?>
                    <tr>
                        <td colspan="4">尚無角色</td>
                    </tr>
                  <? endif;?>
                </tbody>
            </table>
        </td>
    </tr>
</table>

<a href="<?=site_url("log/login?uid={$user->uid}&action=查詢")?>" class="btn btn-link btn-small">平台登入記錄</a>
<a href="<?=site_url("log/game_login?uid={$user->uid}&action=查詢")?>" class="btn btn-link btn-small">遊戲登入記錄</a>

<br><br>

<legend>玩家儲值分析</legend>

<?if ($user_billing):?>
<table style="width:750px">
    <tr>
        <td style="width:250px">
            <table class="table table-striped table-bordered">
                <thead>
                    <tr>
                        <th>儲值等級</th><th>次數</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>LVL1($50~$599)</td><td><?=$user_billing->lvl1?></td>
                    </tr><tr>
                        <td>LVL2($600~$1499)</td><td><?=$user_billing->lvl2?></td>
                    </tr><tr>
                        <td>LVL3($1500~$4999)</td><td><?=$user_billing->lvl3?></td>
                    </tr><tr>
                        <td>LVL4($5000~$19999)</td><td><?=$user_billing->lvl4?></td>
                    </tr><tr>
                        <td>LVL5($20000~$99999)</td><td><?=$user_billing->lvl5?></td>
                    </tr><tr>
                        <td>LVL6($100000+)</td><td><?=$user_billing->lvl6?></td>
                    </tr>
                </tbody>
                <thead>
                    <tr>
                        <th colspan="2">玩家終身價值(LTV)</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td colspan="2"><?=($user_billing->ltv)?$user_billing->ltv:0?></td>
                    </tr>
                </tbody>
            </table>
        </td>
        <td>
        <?if ($user_billing->ltv):?>
            <img src="<?=base_url()?>/p/jpgraphs/deposit_pie_chart_<?=$user->uid?>" alt="">
        <?endif;?>
        <?
            /*$this->load->library('jpgraph');
            $jgraph_data = array($user_billing->lvl1,$user_billing->lvl2,$user_billing->lvl3,$user_billing->lvl4,$user_billing->lvl5,$user_billing->lvl6);
            $jgraph_labels = array();
            
            $region_graph = $this->jpgraph->pie_chart($jgraph_data, "pie");
            */
        ?>
        </td>
    </tr>
</table>
<?endif;?>

儲值記錄(
<a href="<?=site_url("trade/gash?uid={$user->uid}&action=查詢")?>">Gash+</a> |
<a href="<?=site_url("trade/pepay?uid={$user->uid}&action=查詢")?>">Pepay</a> |
<a href="<?=site_url("trade/google?uid={$user->uid}&action=查詢")?>">Google</a> |
<a href="<?=site_url("trade/ios?uid={$user->uid}&action=查詢")?>">IOS</a> |
<a href="<?=site_url("trade/vip?uid={$user->uid}&action=查詢")?>">VIP</a>)
<a href="<?=site_url("trade/transfer?uid={$user->uid}&action=查詢")?>" class="btn btn-link btn-small">轉點記錄</a>