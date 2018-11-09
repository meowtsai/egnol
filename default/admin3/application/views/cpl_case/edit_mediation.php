<div style="padding:20px;">

	<form method="post" action="<?=site_url("cpl_case/modify_mediation_json")?>" id="form_mediation">
		<input type="hidden" name="case_id" value="<?=$row ? $row->case_id : ''?>">
		<input type="hidden" name="mediation_id" value="<?=$row ? $row->id : ''?>">
	<table cellspacing="0" cellpadding="0" style="width:660px;">
		<tr>
			<td>發文字號：</td>
			<td >
				<input type="text" name="o_case_id" class="required" maxlength="20"  value="<?=$row ? $row->o_case_id: ''?>" placeholder="例:府建行二字第1073906069號">
			</td>
		</tr>
		<tr>
			<td>發文日期：</td>
			<td>
				<input type="text" name="o_case_date" value="<?=$row->o_case_date?>" id="o_case_date">
			</td>
		</tr>
		<tr>
			<td>出席日期</td>
			<td >

				<input type="text" name="req_date"  value="<?=$row ? $row->req_date: ''?>" class="required" id="req_date"  >

			</td>
		</tr>
		<tr>
			<td>出席地點</td>
			<td >
				<input type="text" name="req_place" class="required" style="width:400px;" maxlength="30"  value="<?=$row ? $row->req_place: ''?>" placeholder="例:桃園市桃園區縣府路一號七樓">
			</td>
		</tr>
		<tr>
			<td>主持人：</td>
			<td >
				<input type="text" name="o_staff" class="required"  class="required" maxlength="10"  value="<?=$row ? $row->o_staff: ''?>" placeholder="例:彭新樹">
			</td>
		</tr>
		<tr>
			<td>連絡人：</td>
			<td >
				<input type="text" name="o_contact" class="required" maxlength="10"  value="<?=$row ? $row->o_contact: ''?>" placeholder="姓名, 例:孔繁凱">
				<input type="text" name="o_phone" maxlength="50"  value="<?=$row ? $row->o_phone: ''?>" placeholder="電話, 例:0922******">
			</td>
		</tr>
		<tr>
			<td>我方出席人員：</td>
			<td >
				<input type="text" name="representative" maxlength="10"  value="<?=$row ? $row->representative: ''?>" placeholder="例:張小華">
			</td>

		</tr>
		<tr>
			<td>協調結果：</td>
			<td colspan="3">
				<textarea name="note" rows="5" style="width:98%" class="required" ><?=preg_replace('!<br.*>!iU', "", $row->note );?></textarea>
			</td>
		</tr>

		<tr>
			<td></td>
			<td colspan="3">
				<div class="form-actions">
			  		<button type="submit" class="btn">確認送出</button>
						<input type="hidden" id="back_url" value="<?=site_url("cpl_case/view/{$row->case_id}") ?>">

						<a href="javascript:;" class="del pull-right btn btn-danger" url="<?=site_url("cpl_case/delete_mediation_json/{$row->id}")?>">
			        <i class="icon icon-remove"></i>  刪除本篇
			      </a>
						<a href="javascript:;" class="json_post pull-right btn btn-success" url="<?=site_url("cpl_case/move_mediation/{$row->id}?status=4")?>">
			        <i class="icon icon-check"></i>  協調會結案
			      </a>
			  </div>
			</td>
		</tr>
	</table>



  	</form>

</div>
