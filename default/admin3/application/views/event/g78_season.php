<div id="func_bar">

</div>

<form class="form-search" method="get">
  <input type="text" name="player_id" value="<?=$this->input->get("player_id")?>" class="input-medium required" placeholder="玩家id">
	<button type="submit" class="btn"><i class="icon-search"></i> 查詢</button>

<div>獲獎條件為 60 場戰鬥與處罰比例小於 0.025 </div>
</form>
<? if ($query):?>
<? if ($query->num_rows() == 0):?>

<div class="none">尚無資料</div>

<? else:?>

<div>
查詢結果
</div>


<table class="table table-striped" style="width:600px">
	<thead>
		<tr>
            <th style="width:60px;text-align:center">player_id</th>
            <th style="width:20px;text-align:center">season_5v5cnt</th>
            <th style="width:20px;text-align:center">punish_cnt</th>
            <th style="width:30px;text-align:center">punish_ag</th>
            <th style="width:20px;text-align:center">tag</th>
            <th style="width:50px;text-align:center">結果</th>
        </tr>
	</thead>
	<tbody>
	<? foreach($query->result() as $row):?>
		<tr>
			<td style="text-align:center;"><?=$row->player_id?></td>
			<td style="text-align:center;"><?=$row->season_5v5cnt?></td>
			<td style="text-align:center;"><?=$row->punish_cnt?></td>
			<td style="text-align:center;"><?=$row->punish_ag?></td>
      <td style="text-align:center;"><?=$row->tag?></td>

      <td style="text-align:center;">
        <?if($row->season_5v5cnt >= 60 && $row->punish_ag < 0.025):?>
            獲獎
        <?else:?>
            未獲獎
        <?endif;?>
      </td>
		</tr>
	<? endforeach;?>
	</tbody>
</table>

<? endif;?>
<? endif;?>
