
<?php
	$question_type = $this->config->item('question_type');

?>

	<div class="control-group">
		選擇日期：<input type="text" name="date"  style="width:120px" id="date" autocomplete="off">

		<a href="javascript:void(0);" onclick="get_data($('#date').val())" class="btn btn-small btn-inverse" role="button">查詢</a>
		<p style="margin:30px 0 30px 0;">
			<a href="javascript:void(0);" onclick="get_data(TODAY)" class="btn" role="button">最新（昨日）</a>
      <a href="javascript:void(0);" onclick="get_data(_date(-1))" class="btn btn-primary" role="button">&lt;&lt; 前一天</a>
      <a href="javascript:void(0);" onclick="get_data(_date(1))" class="btn btn-primary" role="button">後一天 &gt;&gt;</a>
		</p>
	</div>




<div>
<b>後送處理數量：</b>
選擇當日專員有標註處理狀況的提問單數量統計，每天凌晨會計算前一天的量。<br />
同日同一案件只會計算一次。
</div>
<div id="output"  style="margin: 30px;"></div>
<label class="lbl_loading">載入資料中.....</label>
<hr size=1 />
<div>




<script type="text/javascript">

//var d =new Date();
var dtmp = new Date();
dtmp.setDate(dtmp.getDate()-1);
//console.log(d.toLocaleDateString());
const TODAY = $.datepicker.formatDate('yy-mm-dd', dtmp);
$(function(){
  var getDate = $("#date").val();
  //console.log();
  $("#date").datepicker({
    changeMonth: true,
    changeYear: true
  });
  $( "#date" ).datepicker("option", "dateFormat", "yy-mm-dd");

  if (!getDate){
    getDate =  $.datepicker.formatDate('yy-mm-dd', new Date());
  }


  $("#date").val(getDate);
  //$( "#date" ).datepicker( "option", "defaultDate", -7 );

//console.log($("#date").val());

get_data(TODAY);


});

//http://test-payment.longeplay.com.tw/default/admin3/service/hourly_count_json?date=2018-10-29

function _date(xDay){
	//console.log(xDay) ;
	var d = new Date($("#date").val());
	//var x = 5; // go back 5 days!
	d.setDate(d.getDate() + xDay);
	//console.log(d) ;
	var vY = d.getFullYear().toString();
	var vM = ("0" + (d.getMonth() + 1).toString()).substr(-2);
	var vD = ("0" + (d.getDate()).toString()).substr(-2);

	var rtnDate = vY + "-" + vM + "-" + vD;
	//console.log(vY + "-" + vM + "-" + vD) ;

	return rtnDate;


}

function get_data(date)
{
	$("#date").val(date);
	$("#output").hide();
	$(".lbl_loading").show();

	let question_type=<?=json_encode($question_type)?>;
  let url = "./daily_allocate_json";
  $.ajax({
    type: "GET",
    url: url,
    data: "date=" + date,
  }).done(function(result) {
    //console.log( "Request done: " + result );
		$(".lbl_loading").hide();
		let obj = JSON.parse(result);
		var derivers = $.pivotUtilities.derivers;
		var tpl = $.pivotUtilities.aggregatorTemplates;
		var sum = $.pivotUtilities.aggregatorTemplates.sum;
		 var numberFormat = $.pivotUtilities.numberFormat;
		 var intFormat = numberFormat({digitsAfterDecimal: 0});
		 var heatmap =  $.pivotUtilities.renderers["Heatmap"];



		$("#output").pivotUI(
		        obj.stat,
		    {

					rows: ["處理專員"],
          cols: ["遊戲"],
          hiddenAttributes: ["cnt","類型"],
					derivedAttributes: {
                        "類別":  function(record) {return question_type[record.類型];}
                    },
						aggregators: {
									 "數量":  function() { return tpl.sum(intFormat)(["cnt"]) },
							 },
						rendererName: "Heatmap",
		    }
		).show();

  })
  .fail(function( jqXHR, textStatus ) {
    console.log( "Request failed: " + textStatus );

  })
  .always(function() {
    //alert( "complete" );
    console.log("complete")
  });;
}
</script>
