//Colorbox
$(document).ready(function(){
$(".group1").colorbox({rel:'group1', height:"90%", current:false, previous:false, next:false});
$(".youtube").colorbox({iframe:true, innerWidth:"80%", innerHeight:"80%"});
$(".iframe").colorbox({iframe:true, width:"80%", height:"80%"});
$(".inline").colorbox({inline:true, innerWidth:250});
});

//Slick
$(document).ready(function() {
    $('.responsive').slick({
        dots: true,
        infinite: false,
        speed: 300,
        slidesToShow: 4,
        slidesToScroll: 4,
        responsive: [{
            breakpoint: 1024,
            settings: {
                slidesToShow: 3,
                slidesToScroll: 3,
                infinite: true,
                dots: true
            }
        }, {
            breakpoint: 600,
            settings: {
                slidesToShow: 2,
                slidesToScroll: 2
            }
        }, {
            breakpoint: 480,
            settings: {
                slidesToShow: 1,
                slidesToScroll: 1
            }
        }]
    });

    $('.center').slick({
		autoplay: true,
        autoplaySpeed: 2000,
		dots: true,
        centerMode: true,
        centerPadding: '40px',
        slidesToShow: 2,
        responsive: [{
            breakpoint: 768,
            settings: {
                arrows: false,
                centerMode: true,
                centerPadding: '0px',
                slidesToShow: 2
            }
        }, {
            breakpoint: 640,
            settings: {
                arrows: false,
                centerMode: true,
                centerPadding: '80px',
                slidesToShow: 1
            }
        }, {
            breakpoint: 480,
            settings: {
                arrows: false,
                centerMode: true,
                centerPadding: '40px',
                slidesToShow: 1
            }
        }, {
            breakpoint: 414,
            settings: {
                arrows: false,
                centerMode: true,
                centerPadding: '40px',
                slidesToShow: 1
            }
        }, {
            breakpoint: 376,
            settings: {
                arrows: false,
                centerMode: true,
                centerPadding: '30px',
                slidesToShow: 1
            }
        }, {
            breakpoint: 361,
            settings: {
                arrows: false,
                centerMode: true,
                centerPadding: '20px',
                slidesToShow: 1
            }
        }, {
            breakpoint: 321,
            settings: {
                arrows: false,
                centerMode: true,
                centerPadding: '0px',
                slidesToShow: 1
            }
        }]
    });
    $('.lazy').slick({
        lazyLoad: 'ondemand',
        slidesToShow: 3,
        slidesToScroll: 1
    });
    $('.autoplay').slick({
        dots: true,
        infinite: true,
        speed: 300,
        slidesToShow: 3,
        slidesToScroll: 1,
        autoplay: true,
        autoplaySpeed: 2000
    });

    $('.fade').slick({
        dots: true,
        infinite: true,
        speed: 500,
        fade: true,
        slide: 'div',
        cssEase: 'linear'
    });
});

// scroll to top thing
//plugin
jQuery.fn.topLink = function(settings) {
  settings = jQuery.extend({
    min: 1,
    fadeSpeed: 100
  }, settings);
  return this.each(function() {
    //listen for scroll
    var el = jQuery(this);
    el.hide(); //in case the user forgot
    jQuery(window).scroll(function() {
      if(jQuery(window).scrollTop() >= settings.min)
      {
        el.fadeIn(settings.fadeSpeed);
      }
      else
      {
        el.fadeOut(settings.fadeSpeed);
      }
    });
  });
};

//usage w/ smoothscroll
jQuery(document).ready(function() {
  //set the link
  jQuery('#top-link').topLink({
    min: 500,
    fadeSpeed: 100
  });
  //smoothscroll
  jQuery('#top-link').click(function(r) {    
    jQuery("html, body").animate({ scrollTop: 0 }, 300);
  });
    
	setTimeout(function()
	{
		$(".youtube")[0].click();
		addCloseButton();
	}, 3000);
});

function addCloseButton()
{
	setTimeout(function()
	{
		if($("#cb-close-btn").length)
			return;
		
		$("#cboxWrapper").css("position", "relative");
		$("#cboxWrapper").append("<img id='cb-close-btn' onclick='$.colorbox.close()' src='/p/evt/01/images/close-2.png' />");
		$("#cb-close-btn").css("position", "absolute");
	}, 1000);
}

//Back2Top
function backTop() {
var x1 = x2 = x3 = 0;
var y1 = y2 = y3 = 0;
if (document.documentElement) {
x1 = document.documentElement.scrollLeft || 0;
y1 = document.documentElement.scrollTop || 0;
}
if (document.body) {
x2 = document.body.scrollLeft || 0;
y2 = document.body.scrollTop || 0;
}
x3 = window.scrollX || 0;
y3 = window.scrollY || 0;
var x = Math.max(x1, Math.max(x2, x3));
var y = Math.max(y1, Math.max(y2, y3));
window.scrollTo(Math.floor(x* .5), Math.floor(y* .5));
if (x > 0 || y > 0) {
window.setTimeout("backTop()", 30);
}
}