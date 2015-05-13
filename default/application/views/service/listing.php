<style>
<!--
#main table {font-size:14px;}
#main table td {padding:7px; text-align:center; border-bottom:1px dotted #e6e4e4;}
#main table thead th {background:#e2fadc; padding:7px;}
-->
</style>
<h3>提問查詢</h3>

<div style="font-size:14px; line-height:22px; padding-bottom:20px; margin-bottom:20px;">
‧問題進度依照處理情況分為處理中、已回覆及結案。<br>
    - 處理中：指問題尚在查詢，問題提交處理後，依類型不同，約需1~3個工作天處理。<br>
    - 已回覆：指您問題已處理完畢，待您確認；若您確認後無法解答問題，可於該問題內持續提問。<br>
    - 結　案：指您問題已確認無問題或您確認問題後三日內無再次提出問題，則視為結案；結案問題<br>
                  　無法於同問題內提問，若有其他問題需重新<a href="<?=site_url("service/question")?>">填寫提問</a>。<br>
</div>

<div style="margin-bottom:15px;">
	問題狀態 
	<select id="filter" style="width:150px;">
		<option value="">--</option>
		<? 
		$question_status = $this->config->item("question_status");
		foreach($question_status as $id => $status):?>
		<option value="<?=$id?>" <?=$this->input->get("status")==$id ? 'selected="selected"' : ""?>><?=$status?></option>
		<? endforeach;?>
	</select>
</div>

<div>

<table style="width:100%">
  <thead>
  	<tr>
  		<th style="width:15%">編號</th><th style="width:30%">提問時間</th><th style="width:40%">提問描述</th><th style="width:15%">目前狀態</th>
  	</tr>
  </thead>
  <tbody>
  <? $no = $query->num_rows(); 
  	foreach($query->result() as $row):?>
  	<tr class="item" status="<?=$row->status?>">
  		<td><a href="<?=site_url("service/view/{$row->id}")?>"><?=$no--?></a></td>
  		<td><?=$row->create_time?></td>
  		<td><a href="<?=site_url("service/view/{$row->id}")?>"><?=mb_strimwidth(strip_tags($row->content), 0, 28, '...')?></a></td>
  		<td><?=$question_status[$row->status]?>
  			<? if ($row->status=='2' && $row->is_read=='0') echo '<span style="color:red">(未讀)</span>'?>
  		</td>
  	</tr>
  <? endforeach;?>
  </tbody>
</table>

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