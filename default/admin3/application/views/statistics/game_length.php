<?php 
	$c_game_query = $this->db->from("games")->order_by("rank")->get();

	$c_game = array();
	$c_game_menu["獨代"] = array();
	$c_game_menu["聯運"] = array();
	$c_game_menu["關閉"] = array();
	
	foreach($c_game_query->result() as $row) {
		$c_game[$row->game_id] = $row;		
		if ($row->is_active == 0) {$c_game_menu["關閉"][] = $row; continue;}
		if (strpos($row->tags.",", "聯運,") !== false) {$c_game_menu["聯運"][] = $row; continue;}
		$c_game_menu["獨代"][] = $row;
	}
	
	$channels = $this->config->item('channels');
	$game_id = ($this->input->get("game_id_1")?$this->input->get("game_id_1"):($this->input->get("game_id_2")?$this->input->get("game_id_2"):($this->input->get("game_id_3")?$this->input->get("game_id_3"):'')));
?>
<div id="func_bar">
	
</div>

<form method="get" action="<?=site_url("statistics/game_length")?>" class="form-search">
	<!--input type="hidden" name="game_id" value="<?=$this->game_id?>"-->
	<div class="control-group">
		
		<? $i = 1; 
		foreach($c_game_menu as $category => $c_menu):?>
	        <?=$category?>
	        <select name="game_id_<?=$i?>">
		        <option value="">--</option>
		        <? foreach($c_menu as $key => $row):?>
		        <option value="<?=$row->game_id?>" <?=($game_id==$row->game_id ? 'selected="selected"' : '')?>><?=$row->name?>	</option>
		        <? endforeach;?>
	        </select>
        <? $i++;  
		endforeach;?>	
		
		時間
		<input type="text" name="start_date" class="date required" value="<?=$this->input->get("start_date")?>" style="width:120px"> 至
		<input type="text" name="end_date" class="date" value="<?=$this->input->get("end_date")?>" style="width:120px" placeholder="現在">
		<a href="javascript:;" class="clear_date"><i class="icon-remove-circle" title="清除"></i></a>
		
		<input type="submit" class="btn btn-small btn-inverse" name="action" value="在線時長">	
	
	</div>
	
	<p class="text-info">
			<span class="label label-info">欄位說明</span>
			廣告參數是使用like, 規則為 like '廣告%'
		</p>	
		
</form>

<? if ($query):?>
	<? if ($query->num_rows() == 0): echo '<div class="none">查無資料</div>'; else: ?>
	<table class="table table-striped table-bordered" style="width:auto;">
		<thead>
			<tr>
				<th nowrap="nowrap">日期</th>
				<th style="width:70px">新增用戶在線15分鐘以下</th>
				<th style="width:70px">新增用戶在線15~30分鐘</th>
				<th style="width:70px">新增用戶在線30~60分鐘</th>
				<th style="width:70px">新增用戶在線60~90分鐘</th>
				<th style="width:70px">新增用戶在線90~120分鐘</th>
				<th style="width:70px">新增用戶在線120分鐘以上</th>
				<th style="width:70px">登入用戶在線15分鐘以下</th>
				<th style="width:70px">登入用戶在線15~30分鐘</th>	
				<th style="width:70px">登入用戶在線30~60分鐘</th>
				<th style="width:70px">登入用戶在線60~90分鐘</th>
				<th style="width:70px">登入用戶在線90~120分鐘</th>
				<th style="width:70px">登入用戶在線120分鐘以上</th>
				<th style="width:70px">新增儲值用戶在線15分鐘以下</th>
				<th style="width:70px">新增儲值用戶在線15~30分鐘</th>
				<th style="width:70px">新增儲值用戶在線30~60分鐘</th>
				<th style="width:70px">新增儲值用戶在線60~90分鐘</th>
				<th style="width:70px">新增儲值用戶在線90~120分鐘</th>
				<th style="width:70px">新增儲值用戶在線120分鐘以上</th>
				<th style="width:70px">儲值用戶在線15分鐘以下</th>
				<th style="width:70px">儲值用戶在線15~30分鐘</th>
				<th style="width:70px">儲值用戶在線30~60分鐘</th>
				<th style="width:70px">儲值用戶在線60~90分鐘</th>
				<th style="width:70px">儲值用戶在線90~120分鐘</th>
				<th style="width:70px">儲值用戶在線120分鐘以上</th>		 	
			</tr>
		</thead>
		<tbody>
		<? $color = array('00aa00', '448800', '776600', 'aa4400', 'dd2200', 'ff0000');
			foreach($query->result() as $row):
				$startdate = strtotime($row->date);
				$enddate = time();
				$days = round(($enddate-$startdate)/3600/24) ; 
		?>
			<tr>			
				<td nowrap="nowrap"><?=$row->date?></td>
				<td style="text-align:right"><?=number_format(($row->new_login_count)?$row->new_t15*100/$row->new_login_count:0, 2).'%'?></td>
				<td style="text-align:right"><?=number_format(($row->new_login_count)?$row->new_t30*100/$row->new_login_count:0, 2).'%'?></td>
				<td style="text-align:right"><?=number_format(($row->new_login_count)?$row->new_t60*100/$row->new_login_count:0, 2).'%'?></td>
				<td style="text-align:right"><?=number_format(($row->new_login_count)?$row->new_t90*100/$row->new_login_count:0, 2).'%'?></td>
				<td style="text-align:right"><?=number_format(($row->new_login_count)?$row->new_t120*100/$row->new_login_count:0, 2).'%'?></td>
				<td style="text-align:right"><?=number_format(($row->new_login_count)?$row->new_tmore*100/$row->new_login_count:0, 2).'%'?></td>
				<td style="text-align:right"><?=number_format(($row->all_login_count)?$row->all_t15*100/$row->all_login_count:0, 2).'%'?></td>
				<td style="text-align:right"><?=number_format(($row->all_login_count)?$row->all_t30*100/$row->all_login_count:0, 2).'%'?></td>
				<td style="text-align:right"><?=number_format(($row->all_login_count)?$row->all_t60*100/$row->all_login_count:0, 2).'%'?></td>
				<td style="text-align:right"><?=number_format(($row->all_login_count)?$row->all_t90*100/$row->all_login_count:0, 2).'%'?></td>
				<td style="text-align:right"><?=number_format(($row->all_login_count)?$row->all_t120*100/$row->all_login_count:0, 2).'%'?></td>
				<td style="text-align:right"><?=number_format(($row->all_login_count)?$row->all_tmore*100/$row->all_login_count:0, 2).'%'?></td>
				<td style="text-align:right"><?=number_format(($row->new_deposit_login_count)?$row->new_deposit_t15*100/$row->new_deposit_login_count:0, 2).'%'?></td>
				<td style="text-align:right"><?=number_format(($row->new_deposit_login_count)?$row->new_deposit_t30*100/$row->new_deposit_login_count:0, 2).'%'?></td>
				<td style="text-align:right"><?=number_format(($row->new_deposit_login_count)?$row->new_deposit_t60*100/$row->new_deposit_login_count:0, 2).'%'?></td>
				<td style="text-align:right"><?=number_format(($row->new_deposit_login_count)?$row->new_deposit_t90*100/$row->new_deposit_login_count:0, 2).'%'?></td>
				<td style="text-align:right"><?=number_format(($row->new_deposit_login_count)?$row->new_deposit_t120*100/$row->new_deposit_login_count:0, 2).'%'?></td>
				<td style="text-align:right"><?=number_format(($row->new_deposit_login_count)?$row->new_deposit_tmore*100/$row->new_deposit_login_count:0, 2).'%'?></td>
				<td style="text-align:right"><?=number_format(($row->deposit_login_count)?$row->deposit_t15*100/$row->deposit_login_count:0, 2).'%'?></td>
				<td style="text-align:right"><?=number_format(($row->deposit_login_count)?$row->deposit_t30*100/$row->deposit_login_count:0, 2).'%'?></td>
				<td style="text-align:right"><?=number_format(($row->deposit_login_count)?$row->deposit_t60*100/$row->deposit_login_count:0, 2).'%'?></td>
				<td style="text-align:right"><?=number_format(($row->deposit_login_count)?$row->deposit_t90*100/$row->deposit_login_count:0, 2).'%'?></td>
				<td style="text-align:right"><?=number_format(($row->deposit_login_count)?$row->deposit_t120*100/$row->deposit_login_count:0, 2).'%'?></td>
				<td style="text-align:right"><?=number_format(($row->deposit_login_count)?$row->deposit_tmore*100/$row->deposit_login_count:0, 2).'%'?></td>														
			</tr>
		<? endforeach;?>
		</tbody>
	</table>
	<? endif;?>
<? endif;?>