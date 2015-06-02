// JavaScript Document
$(function(){ 
	
    $.ajax({
        type: "get",
        async: false,
        url: "/platform/top_bar",
        dataType: "jsonp",
        success: function(json){            
    		$('#skybar').html(json.html);	
        }
    });
});
