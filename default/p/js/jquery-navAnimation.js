// JavaScript Document
$(function(){
	
    orginNav(0);
	$('#nav li').click(function(){
     if($(this).index()!=1){window.location=urArray[$(this).index()];/*window.open(urArray[$(this).index()],'_blank');*/}
	 /*else if($(this).index()==2){return false;}
	 else{window.location=urArray[$(this).index()];}*/	
		})
	$('#nav li').hover(function(){
		orginNav(100);	
		var $ID=$(this).attr('id');
		//alert($ID);		
		$(this).stop().animate({
		backgroundPositionY:'-46px'
		},300);
		
		if($ID!='Na6'){
	    $(this).find('div').fadeIn(0).css('left','0px').animate({
	     left:18
	     },400,"easeOutElastic");
		}
		else{
		$(this).find('div').fadeIn(0).css('left','-165px').animate({
	     left:-175
	     },400,"easeOutElastic");
		}
	   $(this).mouseleave(function(){
		orginNav(300);
		});
		});
})

function orginNav(time){
	$('#nav li').stop(false,false).animate({
		backgroundPositionY:'0px'
		},time+100);
    $('#nav .navSubAll').stop().fadeOut(time);



}