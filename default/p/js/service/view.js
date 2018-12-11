$(function(){

	$("form").validate({
		onfocusout: false,
		onkeyup: false,
		onclick: false,
		submitHandler: function(form) {
			$.blockUI({ message: '<h1><img src="/p/image/loading.gif" /> 請稍等...</h1>' });
			$(form).json_ajaxSubmit(function(json){
				//alert('成功!');
				$.unblockUI();
				location.reload();
			});
		}
	});

	$(".close_question").click(function(){
		var site = $(this).attr("site");
		$.json_post($(this).attr("url"), function(){
			location.href = '/service/listing?site=' + site;
		});
	});
});
