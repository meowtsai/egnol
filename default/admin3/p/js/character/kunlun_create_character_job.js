$(function(){
	
	$("input[name=start_date]").datetimepicker({dateFormat: "yy-mm-dd", changeMonth: true, changeYear: true});
	
	var keep = 0;
	var success = 0;
	var total = $("#total").text();
	
	$("#start").click(function(){
		keep = 1;
		run();
	});
	
	$("#stop").click(function(){
		keep = 0;
	});
	
	function run() 
	{
		var $li = $("#ids li:first").remove();
		if ($li.length > 0) {
			$.get("/admin3/character/kunlun_run_job/"+$li.text(), function(){
				success++;					
				$("#percent").width(success/total*100+"%");
				$("#current").text(success);
				if (keep ==1) {
					run();
				}
			});
		}		
	}
	
});