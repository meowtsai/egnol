<div id="content-login">
	<div class="login-ins">
		<div class="bread cf" typeof="v:Breadcrumb">
			<a href="<?=$game_url?>" title="首頁" rel="v:url" property="v:title">首頁</a> > <a href="<?=$longe_url?>service?site=<?=$site?>" title="公告" rel="v:url" property="v:title">公告</a>
		</div>
		<div class="login_box">
			<div class="login_member">
				<div class="login_info">
					<ul id="news_tab">
						<li><a href="#">所有公告</a></li>
						<li><a href="#">活動公告</a></li>
						<li><a href="#">系統公告</a></li>
						<li><a href="#">江湖新聞</a></li>
					</ul>

					<ul id="news_content">
					<?
						foreach($news->result() as $row)
						{
							echo "<li><img src='/p/image/news/";
							if($row->type == 0)
								echo "sys";
							else if($row->type == 1)
								echo "news";
							else if($row->type == 2)
								echo "event";
							echo "-icn.gif' /><a class='iframe' href=''><span class='date'>";
							echo date("Y-m-d", strtotime($row->create_time));
							echo "</span>";
							echo $row->title;
							echo "</a></li>";
						}
					?>
					</ul>
					<a href="#"><img class="more_btn" src="/p/image/news/more-btn.png" /></a>
				</div>
			</div>
			<div class="button"><a href="#" title="login"><img src="/p/image/news/back-btn.gif"></a></div>
		</div>
	</div>
</div>
<script>
	(function($){
		$(window).load(function(){
			$(".scrollbar").mCustomScrollbar();
		});
	})(jQuery);
</script>
