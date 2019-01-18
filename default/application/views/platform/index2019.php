
<div id="nav">
   <div class="nav_container">

    <a href="#"><div class="nav_logo"></div></a>
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
    <a href="/platform/support"><li class="nav_row">客服</li></a>

    <a href="#"><li class="nav_row">首頁</li></a>
      </ul>

     </div>
  </div>
</div>
<div id="swiper_outer">
<div class="swiper-container">
    <div class="swiper-wrapper">
      <?foreach($games->result() as $row):
        if (strpos($row->tags,"首頁輪播")===false) continue;
        if (!$row->bg_path) continue;
        ?>
        <div class="swiper-slide">
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

</div>
<div class="game_container">

	<div class="game_title" >所有遊戲</div>
	<div class="allgame_list">


	</div>
</div>
<div class="bottom_box"></div>

<script>
var game_list = <?=json_encode($games->result());?>;

for (var i = 0; i < game_list.length; i++) {
  var game = game_list[i];
  var cls ="img_title_horizontal";
  if (game.bg_path) {
    $(".allgame_list ").append(`
    <a href="${game.site}>"><div class="allgamebox">
      <div class="allgames_img" style="background-image: url(${game.bg_path})">
        <div id="title_${game.game_id}" class="${cls}" style="background-image: url(${game.title_path})"></div>
      </div>
      <span>${game.name}</span>
      <div class="box_detail">
      ${game.site ? `<a href="${game.site}" target="_blank"><div class="allgame_home">前往官網</div></a>` : ''}
      ${game.fanpage ? `<a href="${game.fanpage}" target="_blank"><div class="allgame_fb">facebook</div></a>`:''}
      </div>
    </div>
    </a>`);
  }



}


var src = $('.allgames_img > div');
for (var j = 0; j < src.length; j++) {
  var bg = src[j].style['background-image'];
  bg = bg.replace('url("','').replace('")','');

  var img = new Image();
  img.onload = function(){
    var i = $(this)[0];
    if(i.height > i.width)
    {
      var theImage = $(this).attr('src');
      imageSize(theImage)
    }
  };

  img.src=bg;

}

function imageSize(img){
  var a  = game_list.filter(game => {
    return game.title_path == img;
  })[0];
   $("#title_" +  a.game_id).addClass("img_title_straight");


}
</script>
