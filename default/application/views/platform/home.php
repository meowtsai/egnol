<div class="nav">
	   <div class="nav_container">

			<a href="https://game.longeplay.com.tw"><div class="nav_logo"></div></a>
		   <div class="mb_menubutton"></div>
		   <div class="mb_menubutton_x"></div>
		   <div class="menu">

			<ul>
					<li class="nav_row">遊戲<img src="/p/image/2018/slidedown.png" style="margin-left:10px" alt="">
					  <div class="nav_row_inbox">
						<ul>
              <?
              foreach($games->result() as $row):
              if ( empty($row->logo_path)) continue;
              ?>
              <li><div class="nav_game">
							<span style="background-image:url(<?=$row->logo_path?>)"></span>
							<i><?=$row->name?></i>
							<a href="https://game.longeplay.com.tw/service_quick?param_game_id=<?=$row->game_id?>&site=long_e" class="service"></a>
              <?if ($row->fanpage):?>
							<a href="<?=$row->fanpage?>" class="fb"></a>
              <?else:?>
							<a href="javascript: void(0)" class="fb" style="background:url();cursor:default" ></a>

              <?endif;?>
              <?if ($row->site):?>
							<a href="<?=$row->site?>" class="home"></a>
              <?else:?>
              <a href="javascript: void(0)" class="home" style="background:url();cursor:default" ></a>
              <?endif;?>
							</div>
							</li>
              <? endforeach;?>
						</ul>
					   </div>
				</li>



		    </ul>

		   </div>
		</div>
	</div>
<!--輪播-->
<div class="swiper-container">
    <div class="swiper-wrapper">
      <?
      foreach($games->result() as $row):
      if (empty($row->bg_path)) continue;
      ?>
      <div class="swiper-slide" >
		  <div class="slidebg" style="background-image:url(<?=$row->bg_path?>)">
		     <div class="homebox homebox_black">
				 <ul>
				 	<li><span class="gametitle"><?=$row->name?></span></li>
				 	<li><span class="gametext"><?=$row->slogan?></span></li>
				 	<li><a href="<?=$row->site?>"  target="_blank" class="homebutton"></a></li>
				 </ul>
			 </div>
		  </div>
	  </div>
    <? endforeach;?>


    </div>
    <!-- Add Pagination -->
       <div class="swiper-pagination"></div>
    <!-- Add Arrows -->
    <div class="swiper-button-next "></div>
    <div class="swiper-button-prev "></div>
 </div>
