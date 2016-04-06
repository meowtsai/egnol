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
		<div class="login-form">
			<p class="p1">案件資訊(含編號、回報內容、時間、處理狀態)<br>▼點擊查看案件▼</p>
		</div>
		<ul class="server_case">
			<? $no = $query->num_rows();
				foreach($query->result() as $row):?>
				<a href="<?=$longe_url?>service/view/<?=$row->id?>?site=<?=$site?>")">
					<li style="margin-top:10px;">
						<table style="width:100%;">
							<tr>
						        <td class="button">
								    <div class="wrap_text" style="max-width:70%;min-width:70%;"><?=$row->content?></div>
								    <div class="wrap_text" style="max-width:3%;min-width:3%;">|</div>
								    <div class="wrap_text" style="float:right; max-width:23%; width:23%;">
									    <?=$question_status[$row->status]?>
                                        <? if ($row->status=='2' && $row->is_read=='0') echo '<span class="field" style="color:red">(未讀)</span>'?>
								    </div>
							    </td>
							</tr>
							<tr>
						        <td style="float:right; font-size: 8px; color: #D8D8D8; font-style: italic;overflow:visible;">
							        <?=date('Y-m-d H:i', strtotime($row->create_time))?>
						        </td>
							</tr>
						</table>
					</li>
				</a>
			<? endforeach;?>
		</ul>
		<div class="login-button">
			<p>
				<input name="doSubmit" type="submit" id="doSubmit" value="" style="display:none;" />
				<img style="cursor:pointer;" src="<?=$longe_url?>p/image/server/server-back-btn2.png" class="button_submit" onclick="javascript:history.back();" />
			</p>
		</div>
	</div>
</div>