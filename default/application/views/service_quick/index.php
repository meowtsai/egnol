<div id="content-login">
	<div class="login-ins">
		<div class="bread cf" typeof="v:Breadcrumb">
			<a href="<?=$longe_url?>service_quick?site=<?=$site?>" title="客服中心" rel="v:url" property="v:title">客服中心</a>
		</div>
		<div class="login-button">
			
			<p>
				<img style="cursor:pointer;" src="<?=$longe_url?>p/image/server/server-btn.png" class="button_submit" onclick="javascript:location.href='<?=$longe_url?>service_quick/question?site=<?=$site?>'" />&nbsp;
				<img style="cursor:pointer;" src="<?=$longe_url?>p/image/server/server-btn2.png" class="button_submit" onclick="javascript:location.href='<?=$longe_url?>service_quick/listing?site=<?=$site?>'" />
			</p>
		</div>
        <? if ($is_ingame):?>
            <div class="notes" style="text-align:center;padding:5px;">提醒勇者：需附檔案回報時，請直接利用官網線上提問，謝謝。</div>
        <? endif;?>
	</div>
</div>