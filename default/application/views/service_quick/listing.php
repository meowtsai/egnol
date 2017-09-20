<?
	$question_status = $this->config->item("question_status");
?>
<style>
.field {
	display:inline-block;
}
</style>
<div id="content-login">
	<div class="login-ins">
		<div class="bread cf" typeof="v:Breadcrumb">
			<a href="<?=$game_url?>" title="首頁" rel="v:url" property="v:title">首頁</a> > <a href="<?=$longe_url?>service_quick?site=<?=$site?>" title="客服中心" rel="v:url" property="v:title">客服中心</a> > <a href="<?=$longe_url?>service_quick/listing?site=<?=$site?>" title="回報紀錄" rel="v:url" property="v:title">回報紀錄</a>
		</div>
        <? if($check_id || $partner_uid):?>
		<div class="login-form">
			<p class="p1">案件資訊(含編號、回報內容、時間、處理狀態)<br>▼點擊查看案件▼</p>
		</div>
		<ul class="server_case">
            <?if($query):?>
			<? $no = $query->num_rows();
				foreach($query->result() as $row):?>
				<a href="<?=$longe_url?>service_quick/view/<?=$row->id?>?site=<?=$site?>">
					<li style="margin-top:10px;">
						<table style="width:100%;">
							<tr>
								<div class="wrap_text" style="font-size: 11px;max-width:48%;min-width:48%;"><b>#<?=$row->id?></b></div>
						        <div style="float:right; font-size: 9px; color: #A8A8A8; font-style: italic;overflow:visible;max-width:48%;min-width:48%;">
							        <?=date('Y-m-d H:i', strtotime($row->create_time))?>
						        </div>
							</tr>
							<tr>
						        <td class="button">
									<div class="wrap_text" style="max-width:72%;min-width:72%;"><span style="color:#e6290f"><?=$row->content?></span></div>
								    <div class="wrap_text" style="max-width:3%;min-width:3%;">|</div>
								    <div class="wrap_text" style="float:right; max-width:18%; width:18%;">
									    <?=$question_status[$row->status]?>
                                        <? if ($row->status=='2' && $row->is_read=='0') echo '<span class="field" style="color:red">(未讀)</span>'?>
								    </div>
							    </td>
							</tr>
						</table>
					</li>
				</a>
			<? endforeach;?>
			<? endif;?>
		</ul>
        <? else:?>
        <form id="listing_form" enctype="multipart/form-data" method="post" action="<?=$longe_url?>service_quick/listing_ajax?site=<?=$site?>">
			<div class="login-form">
				<table class="member_info">
					<tr>
						<th>E-mail</th>
						<td><input type="text" name="email" class="email" id="email" size="33"></td>
					</tr>
					<tr>
						 <th>手機號碼</th>
						 <td><input type="text" name="mobile" class="mobile isMobile" id="mobile" size="33"></td>
					<tr>
						 <th></th>
						 <td>E-mail與手機號碼至少需填寫其中一個</td>
					</tr>
					<tr>
						 <th>客服代碼</th>
						 <td><input type="text" name="check_id" class="required" id="check_id" size="100"></td>
					<tr>
				</table>
				<div class="login-button">
					<p>
						<input name="doSubmit" type="submit" id="doSubmit" value="" style="display:none;" />
                        <img style="cursor:pointer;" src="<?=$longe_url?>p/image/server/server-back-btn1.png" class="button_submit" onclick="javascript:$('#doSubmit').trigger('click')" />&nbsp;
						<img style="cursor:pointer;" src="<?=$longe_url?>p/image/server/server-back-btn2.png" class="button_submit" onclick="javascript:history.back();" />
					</p>
				</div>
			</div>
		</form>
		<? endif;?>
	</div>
</div>
