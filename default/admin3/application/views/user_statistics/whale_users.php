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
            <option value="is_added asc" <?=($orderby=="is_added asc" ? 'selected="selected"' : '')?>>Line?</option>
            <option value="days_inserted asc" <?=($orderby=="days_inserted asc" ? 'selected="selected"' : '')?>>New?</option>
        </select>

		<input type="submit" class="btn btn-small btn-inverse" name="action" value="鯨魚用戶統計">

	</div>

</form>

<? if ($query):?>
	<? if ($query->num_rows() == 0): echo '<div class="none">查無資料</div>'; else: ?>
	<table class="table table-bordered" style="width:auto;">
		<thead>
			<tr>
				<th nowrap="nowrap" rowspan="2">排名</th>
				<th style="width:80px" rowspan="2">帳號</th>
				<th style="width:140px" rowspan="2">角色</th>
                <th style="width:70px" rowspan="2">原廠ID</th>
				<th style="width:110px" rowspan="2">所在伺服器</th>
				<th style="width:70px" rowspan="2">儲值累積</th>
				<th style="width:140px" rowspan="2">最後訂單時間</th>
				<th style="width:140px" rowspan="2">最後訂單登入地區</th>
        <th style="width:70px" rowspan="2">距今</th>
        <th style="width:50px" rowspan="2">Line?</th>
        <th style="width:50px" rowspan="2">New?</th>
				<th style="width:50px" rowspan="2"></th>

			</tr>
		</thead>
		<tbody>
		<? $color = array('00aa00', '448800', '776600', 'aa4400', 'dd2200', 'ff0000');
			$seq = 1;
			foreach($query->result() as $row):

		?>
			<tr bgcolor="<?=vipcolor($row->deposit_total)?>">
				<td nowrap="nowrap"><?=$seq++?></td>
				<td style="text-align:right">

                    <a href="<?=($game_id=='h35naxx1hmt' ? site_url("trade/h35vip_orders/{$row->uid}") :site_url("member/view/{$row->uid}"))?>"><?=$row->uid?>  </a>
        </td>
				<td style="text-align:center">
                    <?=$row->character_name?>
                </td>
                <td style="text-align:right"><?=$row->character_in_game_id?></td>
				<td style="text-align:right"><?=$row->server_name?></td>
				<td style="text-align:right"><?=number_format($row->deposit_total)?></td>
				<td><?=$row->last_login?></td>
				<td>
            <? if (isset($row->ip)):?>
            <?=geoip_country_name_by_name($row->ip)?>
            <? endif;?>
        </td>
        <td style="text-align:right"><?=$row->days_since?></td>
        <td style="text-align:right">
            <? if ($row->is_added==1):?>
            V
            <? endif;?>
        </td>
        <td style="text-align:right">
            <? if ($row->days_inserted==0):?>
            New
            <? endif;?>
        </td>

        <td>
            <? if ($row->is_added==0):?>
            <div class="btn-group">
                <a href="#" class="btn btn-mini">修改</a>
                    <a class="btn btn-mini dropdown-toggle" data-toggle="dropdown"><span class="caret"></span></a>
                    <ul class="dropdown-menu pull-right" role="menu" aria-labelledby="dropdownMenu">
                        <li><a href="javascript:;" class="json_post" url="<?=site_url("user_statistics/whale_users_set_status/{$row->uid}/1")?>">設為已經加入Line</a></li>
                    </ul>
            </div>


            <? endif;?>

        </td>


			</tr>
		<? endforeach;?>
		</tbody>
	</table>
	<? endif;?>
<? endif;?>

<?
function vipcolor($deposit_number){
	if ($deposit_number>=1000000)
	{
		return "#FFFFFF";
	}
	else if ($deposit_number>=600000 and $deposit_number<1000000)
	{
		return "#E5E4E2";
	}
	else if ($deposit_number>=300000 and $deposit_number<600000)
	{
		return "#D4AF37";
	}
	else if ($deposit_number>=100000 and $deposit_number<300000)
	{
		return "#C0C0C0";
	}
	else {
		return "#FFFFFF";
	}
}
?>
