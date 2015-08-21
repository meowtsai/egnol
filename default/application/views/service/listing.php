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
			<p class="p1">案件資訊(含時間、編號、標題、遊戲名稱、處理狀態)<br>▼點擊查看案件▼</p>
		</div>
		<ul class="server_case">
			<? $no = $query->num_rows();
				foreach($query->result() as $row):?>
				<a href="<?=$longe_url?>service/view/<?=$row->id?>?site=<?=$site?>")">
					<li>
						<div class="field" style="width:35%;"><?=$row->create_time?></div>
						<div class="field" style="width:10%;text-align:right;"><?=$no--?></div>
						<div class="field" style="width:25%;"><?=mb_strimwidth(strip_tags($row->content), 0, 28, '...')?></div>
						<div class="field"><?=$question_status[$row->status]?></div>
						<? if ($row->status=='2' && $row->is_read=='0') echo '<span class="field" style="color:red">(未讀)</span>'?>
					</li>
				</a>
			<? endforeach;?>
		</ul>
	</div>
</div>
