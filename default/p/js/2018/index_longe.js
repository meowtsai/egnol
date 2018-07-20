
$(function(){
     var $mb_menubutton=$(".mb_menubutton "),
		 $menu=$(".menu"),
		 $mb_menubutton_x=$(".mb_menubutton_x"),
		 $webmenubutton=$(".menu>ul>li"),
		 $weblist=".nav_row_inbox";
	 //手機板選單
	  $mb_menubutton.click(function(){  
      $menu.fadeToggle(200); 
	  $mb_menubutton.hide();
	  $mb_menubutton_x.show();
      });
      $mb_menubutton_x.click(function(){
	   $menu.fadeToggle(200);  
	  $mb_menubutton.show();
	  $mb_menubutton_x.hide();
	});
	 
    
	//電腦版選單
	$webmenubutton.mouseover(function(){
	 	$(this).find($weblist).show();
	});
	$webmenubutton.mouseout(function(){
		$(this).find($weblist).hide();		
	});
	
	
	$(".home").attr("title","前往官網");
	$(".fb").attr("title","前往粉絲團");
	$(".service").attr("title","前往客服中心");
	
	




	
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