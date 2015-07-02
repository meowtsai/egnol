<div style="padding:20px 0;width:80%; max-width:480px; margin: 0 auto; text-align: center;">
	<ul class="le_form">
		<li>回報紀錄</li>
		<li>
			<div class="field_name">問題狀態
			</div><div class="field_input">
				<select id="filter" style="width:85%;">
					<option value="">--</option>
					<?
					$question_status = $this->config->item("question_status");
					foreach($question_status as $id => $status):?>
					<option value="<?=$id?>" <?=$this->input->get("status")==$id ? 'selected="selected"' : ""?>><?=$status?></option>
					<? endforeach;?>
				</select>
			</div>
		<li>
		</li>
			<table style="width:100%">
			  <thead>
			  	<tr>
			  		<th style="width:30%">時間</th><th style="width:15%">編號</th><th style="width:40%">內容</th><th style="width:15%">狀態</th>
			  	</tr>
			  </thead>
			  <tbody>
			  <? $no = $query->num_rows();
			  	foreach($query->result() as $row):?>
			  	<tr class="item" status="<?=$row->status?>">
			  		<td><?=$row->create_time?></td>
			  		<td><a href="<?=site_url("service/view/{$row->id}")?>"><?=$no--?></a></td>
			  		<td><a href="<?=site_url("service/view/{$row->id}")?>"><?=mb_strimwidth(strip_tags($row->content), 0, 28, '...')?></a></td>
			  		<td><?=$question_status[$row->status]?>
			  			<? if ($row->status=='2' && $row->is_read=='0') echo '<span style="color:red">(未讀)</span>'?>
			  		</td>
			  	</tr>
			  <? endforeach;?>
			  </tbody>
			</table>
			</div>
		</li>
	</ul>
</div>

<script type="text/javascript">
$(function(){
	$('#filter').on("change", function(){
		$('.item').show();
		if ($(this).val()) {
			$('.item[status!='+$(this).val()+']').hide();
			console.log($(this).val());
		}
	});
});
</script>
