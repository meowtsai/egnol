// JavaScript Document
$(function(){ 
	
    $.ajax({
        type: "get",
        async: false,
        url: "http://www.long_e.com.tw/platform/top_bar",
        dataType: "jsonp",
        success: function(json){            
    		$('#skybar').html(json.html);	
        }
    });
});
