
$(function(){
/*	
window.onload = function(){
  var insertDiv = $("#nav");
  insertDiv.html("<div class=nav_container><a href=https://game.longepaly.com.tw><div class=nav_logo></div></a><div class=mb_menubutton></div><div class=mb_menubutton_x></div><div class=menu><ul><a href=customer_service.html><li class=nav_row>客服</li></a> <a href=game.html><li class=nav_row>遊戲</li></a> <a href=index.html><li class=nav_row>首頁</li></a></ul></div></div>");
};
*/	
 $("#nav_game").click(function(){
		 $("#nav_game_list").slideToggle("fast");
	 });	

	
	var mb_menubutton=$(".mb_menubutton "),
	menu=$(".menu"),
	mb_menubutton_x=$(".mb_menubutton_x");	
	
 
	

	

	 //手機板選單
	var mb_btset =0;
	  mb_menubutton.click(function(){  
      menu.fadeToggle(200); 
	  mb_menubutton.hide();
	  mb_menubutton_x.show();
      });
      mb_menubutton_x.click(function(){
	  menu.fadeToggle(200);  
	  mb_menubutton.show();
	  mb_menubutton_x.hide();
	});
	
	
 


	//輪播

	var swiper = new Swiper('.swiper-container', {
      spaceBetween: 0,
      centeredSlides: true,

		autoplay: {
        delay: 5000,
        disableOnInteraction: false,
      },
	 loop:true,
      pagination: {
        el: '.swiper-pagination',
        clickable: true,
      },
      navigation: {
        nextEl: '.swiper-button-next',
        prevEl: '.swiper-button-prev',
      },
    });
	
});