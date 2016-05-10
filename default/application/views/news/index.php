<div id="content-login">
	<div class="login-ins">
		<div class="bread cf" typeof="v:Breadcrumb">
			<a href="<?=$game_url?>" title="首頁" rel="v:url" property="v:title">首頁</a> > <a href="<?=$longe_url?>news?site=<?=$site?>" title="公告" rel="v:url" property="v:title">公告</a>
		</div>
		<div class="login_box">
			<div class="login_member">
				<div class="login_info">
					<ul id="news_tab">
						<li><a href="/news?site=<?=$site?>">所有公告</a></li>
						<li><a href="/news?site=<?=$site?>&type=2">活動公告</a></li>
						<li><a href="/news?site=<?=$site?>&type=3">系統公告</a></li>
						<li><a href="/news?site=<?=$site?>&type=1">江湖新聞</a></li>
					</ul>

					<ul id="news_content">
					<?
						foreach($news->result() as $row)
						{
							echo "<li><img src='/p/image/news/";
							if($row->type == 3)
								echo "sys";
							else if($row->type == 1)
								echo "news";
							else if($row->type == 2)
								echo "event";
							echo "-icn.gif' /><a class='iframe' href='/news/detail/{$row->id}?site={$site}'><span class='date'>";
							echo date("Y-m-d", strtotime($row->start_time));
							echo "</span>";
							echo $row->title;
							echo "</a></li>";
						}
					?>
					</ul>
				</div>
				<div>
                    <?=$this->pagination->create_links();?>
                </div>
			</div>
			<div class="button"><a href="<?=$game_url?>"><img src="/p/image/news/back-btn.gif"></a></div>
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
