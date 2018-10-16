<div id="func_bar">

</div>

<form class="form-search" method="get">
  <input type="text" name="player_id" value="<?=$this->input->get("player_id")?>" class="input-medium required" placeholder="玩家id">
	<button type="submit" class="btn"><i class="icon-search"></i> 查詢</button>

</form>
<? if ($query):?>
<? if ($query->num_rows() == 0):?>

<div class="none">尚無資料</div>

<? else:?>

<div>
查詢結果
</div>


<table class="table table-striped" style="width:300px">
	<thead>
		<tr>
            <th style="width:60px;text-align:center">player_id</th>
            <th style="width:20px;text-align:center">season_5v5cnt</th>
            <th style="width:20px;text-align:center">punish_cnt</th>
            <th style="width:30px;text-align:center">punish_ag</th>
            <th style="width:20px;text-align:center">tag</th>
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
		</tr>
	<? endforeach;?>
	</tbody>
</table>

<? endif;?>
<? endif;?>
