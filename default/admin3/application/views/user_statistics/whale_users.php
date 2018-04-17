<?php
	$c_game_query = $this->db->from("games")->order_by("rank")->get();

	$c_game = array();
	$c_game_menu["獨代"] = array();
	$c_game_menu["聯運"] = array();
	$c_game_menu["關閉"] = array();

	foreach($c_game_query->result() as $row) {
		$c_game[$row->game_id] = $row;
		if (!$row->is_active) {$c_game_menu["關閉"][] = $row; continue;}
		if (strpos($row->tags.",", "聯運,") !== false) {$c_game_menu["聯運"][] = $row; continue;}
		$c_game_menu["獨代"][] = $row;
	}

	$channels = $this->config->item('channels');
?>
<div id="func_bar">

</div>

<form method="get" action="<?=site_url("user_statistics/whale_users")?>" class="form-search">

	<div class="control-group">

		<select name="game_id">
		    <option value="">--請選擇遊戲--</option>
			<?
			foreach($c_game_menu as $category => $c_menu):?>
				<option value=""> -------- <?=$category?> --------</option>
				<? foreach($c_menu as $key => $row):?>
				<option value="<?=$row->game_id?>" <?=($game_id==$row->game_id ? 'selected="selected"' : '')?>><?=$row->name?>	</option>
				<? endforeach;?>
			<? $i++;
			endforeach;?>
	    </select>

            排序方法:
        <select name="orderby">
					<option value="deposit_total desc" <?=($orderby=="deposit_total desc" ? 'selected="selected"' : '')?>>儲值金額</option>
					<option value="is_added desc,deposit_total desc" <?=($orderby=="is_added desc,deposit_total desc" ? 'selected="selected"' : '')?>>加入Line</option>
					<option value="last_login desc,deposit_total desc" <?=($orderby=="last_login desc,deposit_total desc" ? 'selected="selected"' : '')?>>確認流失</option>
          <option value="is_added asc,deposit_total desc" <?=($orderby=="is_added asc,deposit_total desc" ? 'selected="selected"' : '')?>>未加入Line</option>
					<option value="days_inserted asc" <?=($orderby=="days_inserted asc" ? 'selected="selected"' : '')?>>3日內新人</option>
					<option value="days_vip_updated asc,deposit_total desc" <?=($orderby=="days_vip_updated asc,deposit_total desc" ? 'selected="selected"' : '')?>>升階</option>
					<option value="uid asc" <?=($orderby=="days_inserted asc" ? 'selected="selected"' : '')?>>帳號</option>
        </select>



		<input type="submit" class="btn btn-small btn-inverse" name="action" value="鯨魚用戶統計">
		<?if ($game_id):?>
		<a class="btn btn-info" href="<?=site_url("vip/requests_report/{$game_id}")?>">
		查看服務列表
		</a>
		<a class="btn btn-warning" href="<?=site_url("vip/inactive_users/{$game_id}")?>">
		查看流失列表
		</a>
	<? endif;?>


	</div>

</form>

<? if ($query):?>
	<? if ($query->num_rows() == 0): echo '<div class="none">查無資料</div>'; else: ?>
	<table class="table table-bordered" style="width:auto;">
		<thead>
			<tr>
				<th nowrap="nowrap" >排名</th>
				<th style="width:80px" >帳號</th>
				<th style="width:130px" >角色</th>
        <th style="width:60px" >原廠ID</th>
				<th style="width:50px" >伺服器</th>
				<th style="width:70px" >儲值累積</th>
				<th style="width:150px" >最後訂單時間</th>
				<th style="width:100px" >地區</th>
        <th style="width:70px" >未儲值/日</th>
        <th style="width:80px" >3日內新人</th>
				<th style="width:100px" >升階</th>
				<th style="width:80px" >加入Line</th>
				<th style="width:100px" >加入Line日期</th>
				<th style="width:100px" >最後登入日期</th>
				<th style="width:100px" >確認流失</th>
				<th style="width:50px" ></th>

			</tr>
		</thead>
		<tbody>
		<? $color = array('00aa00', '448800', '776600', 'aa4400', 'dd2200', 'ff0000');
			$seq = 1;
			foreach($query->result() as $row):

		?>
			<tr bgcolor="<?=vipcolor($game_id,$row->deposit_total)?>" id="tr<?=$row->character_in_game_id?>">
				<td nowrap="nowrap"><?=$seq++?></td>
				<td style="text-align:right">
					<a href="<?=site_url("trade/h35vip_orders/{$game_id}/{$row->uid}") ?>"><?=$row->uid?>  </a>
        </td>
				<td style="text-align:center">
					<?=$row->character_name?>

					<? if ($row->vip_ranking=='black'):?>
					<i class="fas fa-chess-queen" style="size:16px;color:gold"></i>
					<? endif;?>
				</td>
				<td style="text-align:right">
					<a href="<?=site_url("vip/user_dashboard/{$game_id}/{$row->character_in_game_id}")?>">
					<?=$row->character_in_game_id?>
					</a>
				</td>
				<td style="text-align:right"><?=$row->server_name?></td>
				<td style="text-align:right"><?=number_format($row->deposit_total)?></td>
				<td><?=$row->latest_topup_date?></td>
				<td>
            <? if (isset($row->ip)):?>
            <?=geoip_country_name_by_name($row->ip)?>
            <? endif;?>
        </td>
        <td style="text-align:right"><?=$row->days_since?></td>
        <td style="text-align:right">
            <? if ($row->days_inserted==0):?>
            New
            <? endif;?>
        </td>

				<td>
						<? if ($row->days_vip_updated<7):?>
						<?=$row->vip_ranking_updated?>
						<? endif;?>
				</td>
				<td style="text-align:right">
					<? if ($row->is_added=='1'):?>
					V
					<? endif;?>
        </td>
				<td style="text-align:right">
						<?=$row->line_date?>
				</td>
				<td style="text-align:right">
					<?=$row->last_login?>
				</td>
				<td style="text-align:right">
					<?=$row->inactive_confirm_date?>
				</td>
        <td>

            <div class="btn-group">
                <a href="#" class="btn btn-mini">修改</a>
                    <a class="btn btn-mini dropdown-toggle" data-toggle="dropdown"><span class="caret"></span></a>
                    <ul class="dropdown-menu pull-right" role="menu" aria-labelledby="dropdownMenu">
											<? if ($row->is_added==0):?>
                        <li><a href="javascript:;" class="json_post" url="<?=site_url("user_statistics/whale_users_set_status/{$row->uid}/1")?>">設為已經加入Line</a></li>
											<? else: ?>
											  <li><a href="javascript:;" class="json_post" url="<?=site_url("user_statistics/whale_users_set_status/{$row->uid}/0")?>">設為未加入Line</a></li>
											<? endif;?>
												<li><a data-toggle="modal" data-target="#commentModal" onclick="open_modal('<?=$game_id?>','<?=$row->character_in_game_id?>')">確認流失</a></li>

                    </ul>
            </div>




        </td>


			</tr>
		<? endforeach;?>
		</tbody>
	</table>
	<? endif;?>
<? endif;?>


<!-- Modal -->
<div class="modal fade" id="commentModal" tabindex="-1" role="dialog" aria-labelledby="commentModalLabel" aria-hidden="false">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="commentModalLabel">Modal title</h5>
      </div>
      <div class="modal-body">
				<div id="modal-alert" role="alert">

				</div>
        <form id="comment_form">
          <input type="hidden" id="role_id" name="role_id">
					<input type="hidden" id="game_id" name="game_id">
          <div class="form-group">
            <label for="comment" class="col-form-label">最後登入日期:</label>

						<input type="text" name="end_date" id="end_date" value="<?=$this->input->get("end_date")?>" style="width:120px" placeholder="現在">
          </div>
        </form>
      </div>
      <div class="modal-footer">
				<button type="button" class="btn btn-danger" data-dismiss="modal" onclick="confirm_lastlogin('reset')">重置狀態(清空最後登入和確認流失欄位)</button>
        <button type="button" class="btn btn-secondary" data-dismiss="modal">關閉</button>
        <button type="button" class="btn btn-primary" onclick="confirm_lastlogin('normal')">送出</button>
      </div>
    </div>
  </div>
</div>

<?
function vipcolor($game_id,$deposit_number){
	switch ($game_id) {
		case 'h35naxx1hmt':
			if ($deposit_number>=1000000)
			{
				return "#FFFFFF";
			}
			else if ($deposit_number>=700000 and $deposit_number<1000000)
			{
				return "#E5E4E2";
			}
			else if ($deposit_number>=400000 and $deposit_number<700000)
			{
				return "#D4AF37";
			}
			else if ($deposit_number>=200000 and $deposit_number<400000)
			{
				return "#C0C0C0";
			}
			else if ($deposit_number>=150000 and $deposit_number<200000)
			{
				return "#FAEBD7";
			}
			else {
				return "#FFFFFF";
			}
			break;
		case 'L8na':
			if ($deposit_number>=100000)
			{
				return "#C0C0C0";
			}
			else if ($deposit_number>=50000 and $deposit_number<100000)
			{
				return "#FAEBD7";
			}
			else {
				return "#FFFFFF";
			}
			break;
		default:
			# code...
			break;
	}

}
?>
