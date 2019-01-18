<div id="nav">
   <div class="nav_container">

    <a href="/"><div class="nav_logo"></div></a>
     <div class="mb_menubutton"></div>
     <div class="mb_menubutton_x"></div>
     <div class="menu">

    <ul>
    <li id="nav_game" class="nav_row">遊戲<img src="/p/image/2019/slidedown.png" style="margin-left:10px" alt="">
      <ul id="nav_game_list">

        <?foreach($games->result() as $row):
          if (empty($row->site) || is_null($row->site)) continue;?>
          <a href="<?=$row->site?>"><li><?=$row->name?></li></a>
        <? endforeach;?>

      </ul>
    </li>
    <a href="#"><li class="nav_row">客服</li></a>

    <a href="/"><li class="nav_row">首頁</li></a>
      </ul>

     </div>
  </div>
</div>
<div id="customer_content_outer">
<div id="customer_banner">
<div class="banner_fix">
<div class="cs_title" style="margin-bottom:15px;color:white;">客服中心</div>
</div>
</div>
<div class="customer_container">
  <div id="cs_box_outer">
    <?foreach($games->result() as $row):
      if (empty($row->logo_path) || is_null($row->logo_path)) continue;?>

      <a href="https://game.longeplay.com.tw/service_quick?param_game_id=<?=$row->game_id?>&site=long_e"><div class="cs_box"><img src="<?=$row->logo_path?>" alt=""><span><?=$row->name?></span> </div></a>
    <? endforeach;?>
  </div>

</div>

</div>
