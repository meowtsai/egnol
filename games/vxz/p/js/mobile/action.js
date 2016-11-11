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

	//導覽列錨點
	$(".menu li .anchor").click(function () {
	$("html,body").animate({
	scrollTop: $($(this).attr("href")).offset().top
	}, 1000);
	//$(".box").hide();	
	});


	$(".menu li").click( function(){
	var NN = $(this).index();
	$(this).siblings().removeClass("Hovertab").addClass("Nowtab");
	//$(".box").show();
	$(".menu li").eq(NN).removeClass().addClass("Hovertab");


	});
	$(document).ready(function(){
	$("#goTop").hide()
	$(function(){
	$(window).scroll(function(){
	if($(this).scrollTop()>1){
	$("#goTop").fadeIn();
	} else {
	$("#goTop").fadeOut();
	}
	});
	});

	$("#goTop a").click(function(){
	$("html,body").animate({scrollTop:0},1000);
	//$(".menu li .anchor").removeClass("Hovertab").addClass("Nowtab");
	return false;
	});
	});	

	// 特色切換
	$(function(){
	var _showTab = 0;
	var $defaultLi = $("ul.tabs li").eq(_showTab).addClass("active");
	$($defaultLi.find("a").attr("href")).siblings().hide();

	$("ul.tabs li").click(function() {
	var $this = $(this),
	_clickTab = $this.find("a").attr("href");

	$this.addClass("active").siblings(".active").removeClass("active");

	$(_clickTab).stop(false, true).fadeIn().siblings().hide();

	return false;
	}).find("a").focus(function(){
	this.blur();
	});
	});	 


	// 人物切換
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

	// AD輪播
	jQuery(document).ready(function($) {   
	$('#slider').cycle({   
	fx:    'fade',  //特效           
	speed:  500,
	timeout:  3000,
	random:  1
	});
	}); 

	// ABOUT輪播
	jQuery(document).ready(function($) {   
	$('#slider02').cycle({   
	fx:    'scrollLeft',  //特效           
	speed:  500,
	timeout:  3000,
	random:  1
	});
	}); 


	})

	$(document).ready(function(){
	$("#btn_DOWNLOAD").click(function(){
	$(".nav2").fadeIn();
	return false;
	});

	$(".menu .anchor, .back a, #SLIDERBOX, #TOP, #HEROS, #footer").not("#TOP a, #HEROS a").click(function(){
	$(".nav2").fadeOut();
	return false;
	});
	});