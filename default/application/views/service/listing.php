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
					<li style="margin-top:10px;">
						<table style="width:100%;">
							<tr>
						        <td class="button">
                                    <div class="wrap_text" style="max-width:12%;min-width:12%;"><b>#<?=$row->id?></b></div>
								    <div class="wrap_text" style="max-width:55%;min-width:55%;"><?=$row->content?></div>
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
	</div>
</div>