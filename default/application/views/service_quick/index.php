
<style media="screen">
.alert {
		position: relative;
		padding: .75rem 1.25rem;
		margin-bottom: 1rem;
		border: 1px solid transparent;
		border-radius: .25rem;
		color: #004085;
    background-color: #cce5ff;
    border-color: #b8daff;
}
#clear_copyright{ clear:both; height:50px;}
.copyright {text-align:center; width:100%; margin:10px 0 40px 0; display:block; z-index:9999;}
.copyright p{ font-size:9px; width:80%; margin:-7px auto; color:#666;}

</style>
<div id="content-login">
	<div class="login-ins">
		<div class="bread cf" typeof="v:Breadcrumb">
			<a href="<?=$longe_url?>service_quick?site=<?=$site?>" title="客服中心" rel="v:url" property="v:title">客服中心</a>

		</div>


		<div class="login-button">

			<p>
				<div class="item">
				<img style="cursor:pointer;" src="<?=$longe_url?>p/image/server/server-btn.png" class="button_submit" onclick="javascript:location.href='<?=$longe_url?>service_quick/question?site=<?=$site?>'" />&nbsp;
				<? if ($not_read_cnt >0):?>
				<span class="notify-badge"><?=$not_read_cnt?></span>
				<? endif;?>
				<img style="cursor:pointer;" src="<?=$longe_url?>p/image/server/server-btn2.png" class="button_submit" onclick="javascript:location.href='<?=$longe_url?>service_quick/listing?site=<?=$site?>'" />
				</div>
			</p>

		</div>
        <? if ($is_ingame):?>
            <div class="notes" style="text-align:center;padding:5px;">提醒您：若無法選取檔案回報，請直接利用官網線上提問，謝謝。</div>
        <? endif;?>
	</div>
	<div class="copyright">
		<p>Long E Co., Ltd © 2018 Copyrights Reserved.</p>
		<br>
		<p>

			<a href="https://game.longeplay.com.tw/member/complete_agreement?site=long_e" style="vertical-align:text-bottom;color:#a50000">會員服務暨個資隱私權同意書</a>

		</p>
	</div>
</div>
