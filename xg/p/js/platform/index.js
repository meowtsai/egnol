$(function(){
	
	$("#bulletin_menu").on("mouseover", "a", function(){		
		show(this);
	});
	show($("#bulletin_menu a").get(0));

	
});

function show(a) {
	$.get("platform/get_bulletin/"+a.id, function(data){
		$("#bulletin_content").html(data);			
	});	
}