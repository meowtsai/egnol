
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
				<img style="cursor:pointer;" src="<?=$longe_url?>p/image/server/server-btn.png" class="button_submit" onclick="javascript:location.href='<?=$longe_url?>service_quick/question?site=<?=$site?>'" />&nbsp;
				<img style="cursor:pointer;" src="<?=$longe_url?>p/image/server/server-btn2.png" class="button_submit" onclick="javascript:location.href='<?=$longe_url?>service_quick/listing?site=<?=$site?>'" />
			</p>

		</div>
		<?
		$user_ip = $_SERVER['REMOTE_ADDR'];
		if ($user_ip=="61.220.44.200"):?>
		<div class="alert" style="text-align:center;padding:5px;">＊ 發問前也可以參考我們的<a href="<?=$longe_url?>game_faq?site=<?=$site?>"><b>[常見問題]</b></a>喔!</div>
		<?endif;?>



        <? if ($is_ingame):?>
            <div class="notes" style="text-align:center;padding:5px;">提醒您：需附檔案回報時，請直接利用官網線上提問，謝謝。</div>
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
