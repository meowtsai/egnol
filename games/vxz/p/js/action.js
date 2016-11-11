// JavaScript Document
	
$(function(){
	
	//啟動wow.js
	var wow = new WOW({
		boxClass: 'wow',
		animateClass: 'animated',
		offset: 0,
		mobile: true,
		live: true
	});
	wow.init();
	
	
	
	// AD輪播
	jQuery(document).ready(function($) {   
		$('#slider').cycle({   
			fx:    'fade',  //特效           
			speed:  500,
			timeout:3000,
			random: 1
		});
	}); 
	 
	//導覽列錨點
	$(".menu li .anchor").click(function(e){
		e.preventDefault();

		var this_offset = $(this).offset();
		var that_id     = $(this).attr("href");
		var that_offset = $(that_id).offset();
		var offset_diff = Math.abs(that_offset.top - this_offset.top);

		var base_speed  = 1000; // Time in ms per 1,000 pixels
		var speed       = (offset_diff * base_speed) / 1000;

		$("html,body").animate({
		scrollTop: that_offset.top
		}, speed);
	});

	$(".menu li .anchor").click(function(){
		$(".menu li .anchor").removeClass('active');
		$(this).addClass('active');
		return false;
	});
	
	
		
	// 特色切換
	$(function(){
		var _showTab = 0;
		var $defaultLi = $("ul.tabs li").eq(_showTab).addClass("active");
		$($defaultLi.find("a").attr("href")).siblings().hide();
		
		$("ul.tabs li").click(function(){
			var $this = $(this),
				_clickTab = $this.find("a").attr("href");

			$this.addClass("active").siblings(".active").removeClass("active");

			$(_clickTab).stop(false, true).fadeIn().siblings().hide();

			return false;
		}).find("a").focus(function(){
			this.blur();
		});
	});	
	
	// 英雄切換
	$(document).ready(function() {	 
		$("#owl-demo").owlCarousel({
		jsonPath : 'json/customData.json',
		jsonSuccess : customDataSuccess
		});	 
		function customDataSuccess(data){
			var content = "";
			for(var i in data["items"]){		   
			var img = data["items"][i].img;
			var alt = data["items"][i].alt;	 
			content += "<img src=\"" +img+ "\" alt=\"" +alt+ "\">"
			}
		$("#owl-demo").html(content);
		}	 
	});
	
	// 下拉選單
	$(document).ready(function(){
		$(".download").click(function(){
			$(".nav2").fadeIn();
			return false;
		});
		$(".menu li .anchor, .new a, .charater a, .back a, #TOP, #HEROS, #footer").not("#TOP a, #HEROS a").click(function(){
			$(".nav2").fadeOut();
			return false;
		});
	});
	
	// 遊戲特色ABOUT輪播
	$(window).load(function(){
		$('.flexslider').flexslider({
			animation: "slide",
            controlNav: false,
			start: function(slider){
			$('body').removeClass('loading');
			}
		});
	}); 

})