$(function(){
	
	/*
	if ( $("div#my-folio-of-works li").length > 0) {
		 $("div#my-folio-of-works").slideViewerPro({
		        thumbs: 5, 
		        autoslide: true, 
		        asTimer: 3500, 
		        galBorderWidth: 0,
		        thumbsRightMargin: 0,
		        thumbsBorderWidth: 2,
		        thumbsBorderOpacity: 0, 
		        buttonsTextColor: "#707070",
		        buttonsWidth: 40,
		        thumbsActiveBorderOpacity: 0.8,
		        thumbsActiveBorderColor: "aqua",
		        shuffle: true
		 });
	}*/
	
	$("#pikame").PikaChoose({carousel:true, hoverPause:true, text:{ play: "", stop: "", previous: "", next: "", loading: "載入中" }});
	
});
