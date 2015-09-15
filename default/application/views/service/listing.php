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
			<a href="<?=$game_url?>" title="首頁" rel="v:url" property="v:title">首頁</a> > <a href="<?=$longe_url?>service?site=<?=$site?>" title="客服中心" rel="v:url" property="v:title">客服中心</a> > <a href="<?=$longe_url?>service/listing?site=<?=$site?>" title="線上回報" rel="v:url" property="v:title">線上回報</a>
		</div>
		<div class="login-form">
			<p class="p1">案件資訊(含編號、回報內容、時間、處理狀態)<br>▼點擊查看案件▼</p>
		</div>
		<ul class="server_case">
			<? $no = $query->num_rows();
				foreach($query->result() as $row):?>
				<a href="<?=$longe_url?>service/view/<?=$row->id?>?site=<?=$site?>")">
					<li>
						<table style="width:100%;">
							<tr>
								<th style="max-width:5%;width:5%;"><?=$no--?></th>
								<td style="max-width:35%;min-width:35%;overflow:visible; text-overflow:clip; white-space:normal; word-wrap: break-word;text-align:left;"><?=$row->content?></td>
								<td style="max-width:35%;min-width:35%;overflow:visible; text-overflow:clip; white-space:normal; word-wrap: break-word;"><?=$row->create_time?></td>
								<td style="max-width:20%;width:20%;overflow:visible; text-overflow:clip; white-space:normal; word-wrap: break-word;">
									<?=$question_status[$row->status]?>
                                    <? if ($row->status=='2' && $row->is_read=='0') echo '<span class="field" style="color:red">(未讀)</span>'?>
								</td>
							</tr>
						</table>
					</li>
				</a>
			<? endforeach;?>
		</ul>
	</div>
</div>