// JavaScript Document
	
$("#tabs li").click(function(){
		
		NOW = $(this).index();
		$("#all_tab .BOXS").eq(NOW).siblings().fadeOut();
		$("#all_tab .BOXS").eq(NOW).fadeIn(); 
		$("#tabs li").find("a").removeClass().eq(NOW).addClass("active");		
	});	
	
	$(function(){
	$("#btn_1").click(function(){
		$("#btn_2 a").removeClass();
		$("#btn_1 a").addClass("active");
		$("#btn_1").fadeIn();
		$("#btn_2").fadeOut();		
	});
	
	$("#btn_2").click(function(){
		$("#btn_1 a").removeClass();
		$("#btn_2 a").addClass("active");
		$("#btn_2").fadeIn();
		$("#btn_1").fadeOut();		
	});
})

	$(function(){
    	$("#gotop").click(function(){
      	  jQuery("html,body").animate({
        	    scrollTop:0
       	 },500);
  	 	 });
 		$(window).scroll(function() {
        if ( $(this).scrollTop() > 300){
            $('#gotop').fadeIn("fast");
        } else {
            $('#gotop').stop().fadeOut("fast");
        }
   		});
	});
	
	// 下拉選單
	$(document).ready(function(){
		$(".download").click(function(){
			$(".nav2").fadeIn();
			return false;
		});
		$(".header, .menu, .new a, .back a, #TOP, #HEROS, #service, #footer").click(function(){
			$(".nav2").fadeOut();
			return false;
		});
	});
	