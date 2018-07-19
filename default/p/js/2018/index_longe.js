
$(function(){


 //手機板選單
		$(".mb_menubutton ").click(function(){
     $(".menu").toggle();
	 if($(".menu").css("display")==="block"){
		$(".mb_menubutton").hide();
		$(".mb_menubutton_x").show();
      }
	else{
		$(".mb_menubutton").show();
		$(".mb_menubutton_x").hide();
	}
		});
$(".mb_menubutton_x").click(function(){
     $(".menu").toggle();

	 if($(".menu").css("display")==="block"){
		$(".mb_menubutton").hide();
		$(".mb_menubutton_x").show();
      }
	else{
		$(".mb_menubutton").show();
		$(".mb_menubutton_x").hide();
	}
		});
//手機板選單end



if ($(window).width() < 760) {
	$(".gametext").hide();
		$(".footer_container>a").hide();
		$(".nav_row>ul>li").show();

	}
else {
	$(".gametext").show();
	$(".footer_container>a").show();
	$(".nav_row").mouseover(function(){
		$(this).find("ul>li").show();
	});
	$(".nav_row").mouseout(function(){
		$(this).find("ul>li").hide();
	});
}


$(window).resize(function() {
        var wdth=$(window).width();
        if(wdth<760){

		$(".gametext").hide();
		$(".footer_container>a").hide();
		$(".nav_row>ul>li").show();

		}

	    else{

	$(".menu").show();
	$(".gametext").show();
	$(".footer_container>a").show();
	$(".nav_row").mouseover(function(){
		$(this).find("ul>li").show();
	});
	$(".nav_row").mouseout(function(){
		$(this).find("ul>li").hide();
	});

		}
    });




	//輪播

	  var swiper = new Swiper('.swiper-container', {
      spaceBetween: 0,
      centeredSlides: true,

		autoplay: {
        delay: 8000,
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
