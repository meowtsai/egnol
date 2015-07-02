$(function(){
		
    $('#payment_amount').change(function () {    	
    	if ($(this).val()) {
	    	$.post("/mycard/get_payment/long_e"+$(this).val(), function(json){
	    		$("#pay_kind").empty().append("<option value='' selected='selected'>--請選擇購買方式--</option>");;
	    		$.each(json, function(k, v){    			
	    			$("#pay_kind").append("<option value='"+k+"'>"+v+"</option>");
	    		})
	    	}, 'json')
    	}
    	else {
    		$("#pay_kind").empty().append("<option value='' selected='selected'>--請選擇購買方式--</option>");    		
    	}
    }).trigger("change");
    
    $("form").validate();
    
});