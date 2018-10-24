<?
$status = $this->config->item("case_status");
?>
<style>
#question {}
#question table.officail {background:#e7efe5;}
#content {width:600px;}
</style>

<legend>公函 #<?=$letter->id?></legend>

<div id="func_bar">

</div>


<div id="letter">
	<div id="content">
	<table class="table table-bordered">
		<tr >
			<th style="width:80px;">狀態：</th>
			<td colspan="3">
			<?	echo $status[$letter->status];	?>
			<?if ($letter->status=='4'):?>
			(結案日期:<?=$letter->close_date?>)
			<?endif;?>


			</td>
		</tr>
    <tr >
			<th>發文字號：</th>
			<td  id="td_o_letter_id"><?=($letter->o_letter_id)?$letter->o_letter_id:""?></td>
			<th>承辦人：</th>
			<td id="td_contact"><?=($letter->contact)?$letter->contact:""?></td>
		</tr>
    <tr >

			<th>發文日期：</th>
			<td ><?=($letter->o_letter_date)?$letter->o_letter_date:""?></td>
			<th>回覆期限：</th>
			<td ><?=($letter->deadline)?$letter->deadline:""?></td>
		</tr>
		<tr >
			<th>遊戲角色：</th>
			<td colspan="3">
				【<?=$letter->game_name?>】<?=$letter->role_name?><span style="font-size:60%">(<?=$letter->server_name?>)</span>
			</td>
		</tr>
		<tr >
			<th>處理人員：</th>
			<td><?=$letter->admin_name?></td>
      <th>建立時間：</th>
			<td><?=$letter->create_time?></td>
		</tr>
		<tr >
			<th>備註記事：</th>
			<td colspan="3" ><?=($letter->note)?$letter->note:""?></td>
		</tr>

		<tr>
			<th>相關檔案：</th>
			<td colspan="3">
					<?if ($letter->file_path):?>
					<a href="<?=$letter->file_path?>" target="_blank">公函檔案</a>
					<?endif;?>

			</td>
		</tr>

	</table>




</div>




<? if ($letter->admin_uid==$_SESSION['admin_uid']): ?>
<div class="form-actions">
	<input type="hidden" id="back_url" value="<?=site_url("gov_letter/get_list?action=%E6%9F%A5%E8%A9%A2")?>">
	<a class="btn btn-success" href="<?=site_url("gov_letter/edit/{$letter->id}")?>">
		<i class="icon icon-check"></i>  編輯
	</a>

	<a href="javascript:;" class="del btn btn-danger" url="<?=site_url("gov_letter/delete_letter_json/{$letter->id}")?>">
		<i class="icon icon-remove"></i>  刪除本案
	</a>
</div>
<? endif;?>
</div>
