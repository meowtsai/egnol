<!DOCTYPE HTML>
<html lang="zh-tw">
<head>
<title><?=$meta["title"]?></title>
<meta charset="utf-8"> 
<meta http-equiv="X-UA-Compatible" content="IE=EmulateIE9" /> 
<? 
	unset($meta["title"]);
	foreach($meta as $key => $content) {
		echo "<meta name='{$key}' content='{$content}'>";
	}
	echo $css_link;
	echo $js_include;
?>
<!-- <meta property="qc:admins" content="2316335423637726375647" /> -->
<meta property="qc:admins" content="2601272605637726375647" />
</head>
<body>
<!--<div id="fb-root"></div>
<script>(function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) return;
  js = d.createElement(s); js.id = id;
  js.src = "//connect.facebook.net/zh_TW/all.js#xfbml=1&appId=338080079868";
  fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));</script>-->

<div id="container">

	<? $this->load->view("templates/_top_bar")?>
	
	<div id="header">		
		<div id="header2">
				<ul id="pikame" class="jcarousel-skin-pika">
					<? foreach($highlight->result() as $row):?>
					<li><img ref="/p/img/game/<?=$row->game_id?>_01.jpg" src="/p/img/game/<?=$row->game_id?>_02.png" alt="<?=$row->name?>" >
						<span>
							<span style="position:absolute; margin:-108px 0 0 31px; display:inline-block;">		
								<p style="display:inline-block; text-align:center; width:250px;">						
									<a href="http://<?=$row->game_id?>.long_e.com.tw/" target="_blank"><span class="btn01 index_items"></span></a>								
									<? if (strpos($row->tags, "手遊")===false):?>
									<a href="/play_game/<?=$row->game_id?>" target="_blank"><span class="btn02 index_items"></span></a>
									<? endif;?>
								</p>
							</span>
						</span>			
					</li>
					<? endforeach;?>
				</ul>	
		</div>
	</div>

	<div id="spt" class="boundary">
	
		<div class="spt01">
			
			<? $this->load->view("templates/_login");?>
			
			<div id="news" class="box">
				<div class="hd" style="background:url('/p/img/p02.png'); width:230px; height:36px;">
					<div style="text-align:right; padding:12px 8px 0 0;">
						<a href="<?=site_url("bulletin/get_list")?>" style="color:#eee;">more</a>
					</div>
				</div>
				<div class="bd">
					<ul>
						<? foreach($news->result() as $row):?>
						<li title="<?=$row->title?>">
							<a href="<?=site_url("bulletin/detail/{$row->id}")?>">
								<span style="font-size:13px; margin-right:3px;"><?=date("m/d", strtotime($row->create_time)) ?></span>
								<?=mb_strimwidth($row->title, 0, 20, '...', 'utf-8')?>
							</a>
						</li>
						<? endforeach;?>
					</ul>
				</div>
			</div>
			
			<img src="/p/img/line.png" style="margin-bottom:6px; ">
			
			<div id="fb">

				<div class="fb-like-box" data-href="http://www.facebook.com/long_e.inc" data-width="240" data-show-faces="true" data-stream="false" data-header="false"></div>
				<!--  
				<div class="fb-like" data-href="http://www.facebook.com/long_e.inc" data-send="false" data-width="230" data-show-faces="true"></div>
				-->
			</div>
			
			<img src="/p/img/line.png" style="margin:12px 0 6px; ">
			<a href="<?=site_url("service")?>"><img src="/p/img/p03.png"></a>
			
		</div>		
		<div class="spt02">
		
			<div id="recommendation" class="box index">
				<div class="title">龍邑推薦</div>
				<ul>
					<? foreach($commendation->result() as $row):?>
					<li>
						<div class="pic"><img src="/p/img/game/<?=$row->game_id?>_03.png"></div>
						<div class="func">
							<? if (strpos($row->tags, "手遊")===false):?>
							<a href="/play_game/<?=$row->game_id?>" target="_blank"><span class="btn01 index_items"></span></a>
							<? endif;?>
							<a href="http://<?=$row->game_id?>.long_e.com.tw" target="_blank"><span class="btn02 index_items"></span></a>
						</div>
					</li>
					<? endforeach;?>
				</ul>
				<div style="clear:both"></div>
			</div>
			
			<div id="mobile" class="box index">
				<div class="title">手機遊戲</div>
				<ul>
					<? foreach($mobile->result() as $row):?>
					<li>
						<div class="pic">
							<img src="/p/img/game/<?=$row->game_id?>_03.png">
							<div class="hot index_items"></div>
						</div>
						<div class="right">
							<div class="name"><?=$row->name?></div>
<!-- 							<div class="category">益智休閒</div> -->
							<? if (in_array($row->game_id, array("eya"))) $facebookid = 'eya';
								else $facebookid = 'inc';
							?>							
							<div class="fb_like">
								<div class="fb-like" data-href="https://www.facebook.com/long_e.<?=$facebookid?>".facebook.com/long_e.<?=$row->game_id?>" data-width="The pixel width of the plugin" data-height="The pixel height of the plugin" data-colorscheme="light" data-layout="button_count" data-action="like" data-show-faces="true" data-send="false"></div>
							</div>
							
							<div class="func">
								<? if ($row->game_id <> 'zj') {
									$url = "https://play.google.com/store/apps/details?id=com.long_e.$row->game_id";
								}
								else {
									$url = "javascript:alert('敬請期待');";
								}
								?>
								<a href="<?=$url?>" target="_blank"><span class="btn_android_download index_items"></span></a>
								<a href="http://<?=$row->game_id?>.long_e.com.tw" target="_blank"><span class="btn_go_web index_items"></span></a>
							</div>
						</div>
					</li>
					<? endforeach;?>
				</ul>
				<div style="clear:both"></div>
			</div>			
	
			<? if ($cooperation->num_rows() > 0):?>
			<div id="recommendation" class="box index">
				<div class="title">龍邑好朋友</div>
				<ul>
					<? foreach($cooperation->result() as $row): //fb($row);?>
					<li>
						<div class="pic"><img src="/p/img/game/<?=$row->game_id?>_03.png"></div>
						<div class="func">
							<? if (strpos($row->tags, "手遊")===false):?>
							<a href="/play_game/<?=$row->game_id?>" target="_blank"><span class="btn01 index_items"></span></a>
							<? endif;?>
							<a href="http://<?=$row->game_id?>.long_e.com.tw" target="_blank"><span class="btn02 index_items"></span></a>
						</div>
					</li>
					<? endforeach;?>
				</ul>
				<div style="clear:both"></div>
			</div>
			<? endif;?>
			
			<div id="game_list" class="box index" style="width:740px; margin-bottom:10px;">
				<div class="right">
					<span class="index_items icon_n_game"></span> 最新遊戲
<!-- 					<span class="index_items icon_b_game"></span> 測試遊戲 -->
					<span class="index_items icon_h_game"></span> 熱門遊戲
				</div>
				<div class="title">全部遊戲</div>
				<div style="text-align:center;">
					<? $this->load->view("platform/_game_list", array("game_a"=>$game_a, "game_b"=>$game_b, "game_c"=>$game_c, "game_d"=>$game_d));?>
				</div>
			</div>
			
			<div id="other" class="box index">
				<div class="title">龍邑趴趴造</div>		
				<!--div style="text-align:center;">	
					<? foreach($banners->result() as $row):?>
					<span style="max-width:250px; max-height:100px; overflow:hidden; display:inline-block;">
					<?=make_pic_item($row)?>
					</span>
					<? endforeach;?>
				</div-->
				<div style="clear:both"></div>
			</div>			
		
		</div>
		
	</div>
	


			
<script type="text/javascript">
/* <![CDATA[ */
var google_conversion_id = 978906751;
var google_custom_params = window.google_tag_params;
var google_remarketing_only = true;
/* ]]> */
</script>
<script type="text/javascript" src="//www.googleadservices.com/pagead/conversion.js">
</script>
<noscript>
<div style="display:inline;">
<img height="1" width="1" style="border-style:none;" alt="" src="//googleads.g.doubleclick.net/pagead/viewthroughconversion/978906751/?value=0&amp;guid=ON&amp;script=0"/>
</div>
</noscript>	

	<? $this->load->view("g_copyright")?>
		
</div>

</body>
</html>
		