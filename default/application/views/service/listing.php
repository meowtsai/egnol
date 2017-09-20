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
			<a href="<?=$game_url?>" title="首頁" rel="v:url" property="v:title">首頁</a> > <a href="<?=$longe_url?>service?site=<?=$site?>" title="客服中心" rel="v:url" property="v:title">客服中心</a> > <a href="<?=$longe_url?>service/listing?site=<?=$site?>" title="回報紀錄" rel="v:url" property="v:title">回報紀錄</a>
		</div>
		<div class="login-form">
			<p class="p1">案件資訊(含編號、回報內容、時間、處理狀態)<br>▼點擊查看案件▼</p>
		</div>
		<ul class="server_case">
			<? $no = $query->num_rows();
				foreach($query->result() as $row):?>
				<a href="<?=$longe_url?>service/view/<?=$row->id?>?site=<?=$site?>">
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
		</ul>
	</div>
</div>