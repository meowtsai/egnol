<?
$warning = "";
$status = $this->config->item("case_status");
$mediation_status = $this->config->item("mediation_status");
?>
<style>
#question {}
#question table.officail {background:#e7efe5;}
#content {width:600px;}
.fa-trash-alt{
	color: #d03f3f;
}
.plus-sign {
	size:16px;
	color:green;
	padding:0 0 0 10px;
	cursor: pointer;
}
.form_div {
	padding:10px;
	display:none;
}
</style>


<legend>消保案件 #<?=$case->id?></legend>

<div id="func_bar">

</div>


<div id="case">
	<div id="content">
	<table class="table table-bordered">
		<tr class="<?=$warning?>">
			<th style="width:120px;">狀態：</th>
			<td colspan="3">
			<?	echo $status[$case->status];	?>
			<?if ($case->status=='4'):?>
			(結案日期:<?=$case->close_date?>)
			<?endif;?>


			</td>
		</tr>
    <tr class="<?=$warning?>">
			<th>發文字號：</th>
			<td colspan="3" id="td_o_case_id"><?=($case->o_case_id)?$case->o_case_id:""?></td>
		</tr>
    <tr class="<?=$warning?>">
			<th>申訴人：</th>
			<td id="td_appellant"><?=($case->appellant)?$case->appellant:""?></td>
      <th>申訴原因：</th>
      <td><?=($case->reason)?$case->reason:""?></td>
		</tr>
		<tr class="<?=$warning?>">
			<th>遊戲角色：</th>
			<td colspan="3">
				【<?=$case->game_name?>】<?=$case->role_name?><span style="font-size:60%">(<?=$case->server_name?>)</span>
			</td>
		</tr>
		<tr class="<?=$warning?>">
			<th>處理人員：</th>
			<td><?=$case->admin_name?></td>
      <th>建立時間：</th>
			<td><?=$case->create_time?></td>
		</tr>
		<tr>
			<td colspan="4"></td>
		</tr>
		<tr>
			<th>相關案件 <i class="fas fa-folder-open"></i>

				<span class="plus-sign"><i title="添加相關案件" class="fas fa-plus-circle"></i></span>

			</th>
			<td colspan="3">

				<div style="padding:20px;">


					<? if ($ref_cases->num_rows() == 0):?>
					尚未設定相關案件，有需要請點選左方綠色+號開始新增。
					<?else:?>
						<ul id="ul_ref_cases">
						<? foreach($ref_cases->result() as $ref_case_row):?>
							<li key="<?=$ref_case_row->ref_id?>">
								<a href="<?=site_url("cpl_case/view/{$ref_case_row->ref_id}")?>"># <?=$ref_case_row->ref_id?> - <?=$ref_case_row->o_case_id?></a>
								<a href='#' class='remove-row' title='移除' data-qid='<?=$ref_case_row->ref_id?>' onclick="removeItem(<?=$ref_case_row->ref_id?>)"><i   class='far fa-trash-alt'></i></a>
							</li>
						<? endforeach;?>
						</ul>
					<?endif;?>

				</div>
				<div id="add_ref_div" style="background-color:#BDBDBD;" class="form_div">
		      <form id="add_ref_form" method="post" action="<?=site_url("cpl_case/add_ref_case_json")?>">
		          <input type="hidden" name="case_id" value="<?=$case->id?>">

							<select name="ref_case_list" style="width:200px;" id="ref_case_list"  class="required">
                  <option value="">--案件列表--</option>
                  <? foreach($ref_case_list->result() as $ref_row):?>
                  <option value="<?=$ref_row->id;?>" > # <?=$ref_row->id;?> - <?=$ref_row->o_case_id?>- <?=$ref_row->appellant?> (<?=date('Y-m-d', strtotime($ref_row->o_case_date))?>)</option>
                  <? endforeach;?>
              </select>

							<button type="submit" class="btn btn-small btn-inverse">加入</button>
		      </form>
		    </div>
			</td>
		</tr>
		<tr>
			<td colspan="4"></td>
		</tr>
		<tr>
			<th>相關附件 <i class="fas fa-paperclip"></i>
				<span class="plus-sign"><i title="上傳相關附件" class="fas fa-plus-circle"></i></span>
			</th>
			<td colspan="3">
				<div style="padding:20px;">
					<? $num=1;?>
					<? if ($attachments->num_rows() == 0):?>
					尚未設定相關附件，有需要請點選左方綠色+號開始新增。
					<?else:?>
						<ul id="ul_attach">
						<? foreach($attachments->result() as $attachment):?>
							<li key="<?=$attachment->id?>">
								<a href="<?=$attachment->pic_path?>"><?=$attachment->title?></a>
								<a href='#' class='remove-row' title='移除' onclick="removeAttach(<?=$attachment->id?>)" data-qid='<?=$attachment->id?>' ><i   class='far fa-trash-alt'></i></a>
							</li>
							<? $num++;?>
						<? endforeach;?>
						</ul>
					<?endif;?>
				</div>
				<div style="background-color:#E1F5A9"  class="form_div">
					<form id="add_attachment_form" method="post" action="<?=site_url("cpl_case/add_attachment_json")?>">
							<input type="hidden" name="case_id" value="<?=$case->id?>">

							<label>附件名稱</label>
							<input type="text" name="attach_title" id="attach_title" class="required" value="附件<?=$num?>" autocomplete="off">
							<label>選擇附件</label>
							<input type="file" name="file01" class="required">
							<button type="submit" class="btn btn-small btn-inverse">上傳附件</button>
					</form>
				</div>
			</td>
		</tr>
	</table>


	<?
	$no = 1;
	foreach($replies->result() as $row):?>


        <table class="table table-bordered" style="position:relative;">
            <tr>
                <td  style="width:120px; text-align:center;">
                    #<?=$no++?><br>
                    <?=date('Y-m-d H:i', strtotime($row->contact_time))?>
										<div class="align-bottom">
											 編輯者:<?=$row->admin_uname?> <br /> <br />
										<a href="<?=site_url("cpl_case/edit_reply/{$row->id}")?>"><i class="far fa-edit text-default" title='編輯歷程'></i> 編輯</a>



										</div>
                </td>
								<td style="word-break:break-all">
									<span class="badge badge-warning">歷程紀錄</span>
									<?=$row->note?>
								</td>

            </tr>


        </table>

	<? endforeach;?>



<? if ($case->status == '1' || $case->status == '2') : ?>
    <div style="background-color:#F6CED8;padding:10px;">
      <label><h4>添加聯絡或事件歷程</h4></label>
      <form id="reply_form" method="post" action="<?=site_url("cpl_case/modify_reply_json")?>">
          <input type="hidden" name="case_id" value="<?=$case->id?>">

          <label>聯絡時間</label>
					<input type="text" name="contact_time" id="contact_time" class="" value="<?=date('Y-m-d H:i', strtotime(now()))?>" autocomplete="off">


					<label>歷程紀錄</label>
          <textarea name="note" rows="5" style="width:98%" class="required"></textarea>



					<p style="margin:20px;"/>
          <button type="submit" class="btn ">確認送出</button>
      </form>
    </div>

<? endif;?>

</div>


<? if ($mediations->num_rows() > 0):?>
<table class="table table-striped table-bordered" style="width:auto;">
	<thead>
		<tr>
			<th style="width:60px;"> #</th>
			<th style="width:80px">結案日期</th>
			<th style="width:80px">發文日期</th>
			<th style="width:80px">發文字號</th>
			<th style="width:80px">申訴人姓名</th>
			<th style="width:120px">申訴原因</th>
			<th style="width:120px" >出席日期</th>
			<th style="width:120px">出席地點</th>
			<th style="width:120px">主持人</th>
			<th style="width:120px">聯絡人姓名</th>
			<th style="width:80px;">連絡人電話</th>
			<th style="width:80px;">我方出席人員</th>
			<th style="width:80px;">結果</th>
			<th style="width:100px;">狀態</th>
			<th style="width:110px;">建立時間</th>
		</tr>
	</thead>
	<tbody>
		<?foreach($mediations->result() as $row):?>
		<tr class="<?=($row->status==1 && $row->admin_uid==$_SESSION['admin_uid'])?"warning":""?>">
			<td><a href="<?=site_url("cpl_case/edit_mediation/{$row->id}")?>"><?=$row->id?></a></td>
			<td><?=$row->close_date?></td>
			<td><?=$row->o_case_date?></td>
			<td><?=$row->o_case_id?></td>
			<td><?=$case->appellant?></td>
			<td><?=$case->reason?></td>
			<td><?=$row->req_date?></td>
			<td><?=$row->req_place?></td>
			<td><?=$row->o_staff?></td>
			<td><?=$row->o_contact?></td>
			<td><?=$row->o_phone?></td>
			<td><?=$row->representative?></td>
			<td><?=$row->note?></td>
			<td><?=$mediation_status[$row->status]?></td>
			<td><?=date("Y-m-d H:i", strtotime($row->create_time))?></td>
		</tr>
		<? endforeach;?>



	</tbody>
</table>
<? endif;?>

<? if ($case->status == '3' && $case->admin_uid==$_SESSION['admin_uid']): ?>
<div style="background-color:LemonChiffon;padding:20px;">
<fieldset>
	<legend>新增協調會紀錄</legend>

	<form method="post" action="<?=site_url("cpl_case/modify_mediation_json")?>" id="form_mediation">
		<input type="hidden" name="case_id" value="<?=$case ? $case->id : ''?>">
		<input type="hidden" id="back_url" value="<?=site_url("cpl_case/get_list?action=%E6%9F%A5%E8%A9%A2")?>">
		<table cellspacing="0" cellpadding="0" style="width:660px;">
			<tr>
				<th align="right">發文字號：</th>
				<td >
					<input type="text" name="o_case_id" class="required" maxlength="20"  value="" placeholder="例:府建行二字第1073906069號" autocomplete="off">
				</td>
			</tr>
			<tr>
				<th align="right">發文日期：</th>
				<td>
					<input type="text" name="o_case_date" value="" id="o_case_date"  autocomplete="off">
				</td>
			</tr>
			<tr>
				<th align="right">出席時間：</th>
				<td >
					<input type="text" name="req_date" class="required" value="" id="req_date" >
				</td>
			</tr>
			<tr>
				<th align="right">出席地點：</th>
				<td >
					<input type="text" name="req_place" class="required"  maxlength="30"  value="" placeholder="例:桃園市桃園區縣府路一號七樓">
				</td>
			</tr>
			<tr>
				<th align="right">主持人：</th>
				<td >
					<input type="text" name="o_staff" class="required"  class="required" maxlength="10"  value="" placeholder="例:彭新樹">
				</td>
			</tr>
			<tr>
				<th align="right" valign="top">連絡人：</th>
				<td >
					<input type="text" name="o_contact" class="required" maxlength="10"  value="" placeholder="連絡人姓名, 例:孔繁凱"><br />
					<input type="text" name="o_phone" maxlength="50"  value="" placeholder="連絡人電話, 例:0922******">
				</td>
			</tr>
			<tr>
				<th align="right">我方出席人員：</th>
				<td >
					<input type="text" name="representative"  maxlength="10"  value="" placeholder="例:張小華">
				</td>

			</tr>
			<tr>
				<th align="right">協調結果：</th>
				<td>
					<textarea name="note" rows="5"  ></textarea>
				</td>
			</tr>

			<tr>
				<td align="right"></td>
				<td>
					<button type="submit" class="btn">確認送出</button>
				</td>
			</tr>
		</table>
  	</form>
	</fieldset>
</div>
<? endif;?>

<? if ($case->admin_uid==$_SESSION['admin_uid']): ?>
<div class="form-actions">
	<input type="hidden" id="back_url" value="<?=site_url("cpl_case/get_list?action=%E6%9F%A5%E8%A9%A2")?>">
	<? if ($case->status == '2'): ?>
			<a href="javascript:;" url="<?=site_url("cpl_case/move_case/{$case->id}?status=3")?>" class="json_post pull-left btn btn-warning">進入消保協調開會程序</a>
				<button type="button" class="btn btn-success" data-toggle="modal" data-target="#caseCloseModal" onclick="open_modal('<?=$case->id?>')">結案</button>

	<? elseif ($case->status == '3'): ?>

				<button type="button" class="btn btn-success" data-toggle="modal" data-target="#caseCloseModal" onclick="open_modal('<?=$case->id?>')">結案</button>

	<? endif;?>

	<a href="javascript:;" class="del btn btn-danger" url="<?=site_url("cpl_case/delete_case_json/{$case->id}")?>">
		<i class="icon icon-remove"></i>  刪除本案
	</a>
</div>
<? endif;?>
</div>



<!-- Modal -->
<div class="modal fade" id="caseCloseModal" tabindex="-1" role="dialog" aria-labelledby="caseCloseModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="caseCloseModalLabel">Modal title</h5>
      </div>
      <div class="modal-body">
				<div id="modal-alert" role="alert" class="alert alert-error">

					請注意結案後無法再新增協商和調解紀錄!
				</div>
        <form id="caseClose_form">
          <input type="hidden" id="complaint_id" name="complaint_id">
          <div class="form-group">
            <label for="caseClose" class="col-form-label">請輸入結案日期:</label>
            <input type="text" class="form-control" id="txt_caseCloseDate" name="txt_caseCloseDate"  class="required">
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">取消</button>
        <button type="button" class="btn btn-success" onclick="caseClose()">確定結案</button>
      </div>
    </div>
  </div>
</div>

<script type="text/javascript">
var formDiv = $( ".form_div" );
$("span[class=plus-sign]").click(function(e)
{
	var actDiv = $(this).parent().parent().find(formDiv);
	//console.log(actDiv);
	actDiv.is(":visible")?actDiv.hide():actDiv.show();
	//actDiv.is(":visible")?actDiv.show():actDiv.hide();


		//
		// //console.log($(this).val());
		// //var batch_id = $(this).val();
		// if (document.getElementById('add_ref_div').style.display=='none')
		// {document.getElementById('add_ref_div').style.display = 'block';}
		// else
		// {
		// 	document.getElementById('add_ref_div').style.display='none'}
});


function removeItem(ref_id_remove){
	if (!confirm("確定要取消關聯案件"+ref_id_remove+"嗎?")) {
		return;
	}
	let url = "../remove_case_reference/<?=$case->id?>/" + ref_id_remove;
	$.ajax({
		type: "POST",
		url: url,
		dataType: "json",
		contentType: 'application/json; charset=UTF-8',
	}).done(function(result) {
		//console.log( "Request done: " + JSON.stringify(result) );

		if (result.status=="success")
		{
			$("#ul_ref_cases li[key='"+ ref_id_remove +"']").remove();
		}
	})
	.fail(function( jqXHR, textStatus ) {
		console.log( "Request failed: " + textStatus );
	})
	.always(function() {
		console.log("complete")
	});
}
function removeAttach(attach_id_remove){
	if (!confirm("確定要刪除這個附件嗎?")) {
		return;
	}
	console.log("attach_id_remove",attach_id_remove);
	console.log("case_id",<?=$case->id?>);
	//return;
	let url = "../remove_case_attachment/<?=$case->id?>/" + attach_id_remove;
	$.ajax({
		type: "POST",
		url: url,
		dataType: "json",
		contentType: 'application/json; charset=UTF-8',
	}).done(function(result) {
		//console.log( "Request done: " + JSON.stringify(result) );

		if (result.status=="success")
		{
			$("#ul_attach li[key='"+ attach_id_remove +"']").remove();
		}
	})
	.fail(function( jqXHR, textStatus ) {
		console.log( "Request failed: " + textStatus );
	})
	.always(function() {
		console.log("complete")
	});
}

</script>
