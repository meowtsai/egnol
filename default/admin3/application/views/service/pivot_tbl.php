


	<div class="control-group">
		選擇日期：<input type="text" name="date"  style="width:120px" id="date" autocomplete="off">

		<a href="javascript:void(0);" onclick="get_data($('#date').val())" class="btn btn-small btn-inverse" role="button">查詢</a>
		<p style="margin:30px 0 30px 0;">
			<a href="javascript:void(0);" onclick="get_data(TODAY)" class="btn" role="button">今天</a>
      <a href="javascript:void(0);" onclick="get_data(_date(-1))" class="btn btn-primary" role="button">&lt;&lt; 前一天</a>
      <a href="javascript:void(0);" onclick="get_data(_date(1))" class="btn btn-primary" role="button">後一天 &gt;&gt;</a>
		</p>
	</div>




<div>
<b>進件數量：</b>
選擇當日每個小時玩家所送出提問單的數量統計。
</div>
<div id="output"  style="margin: 30px;"></div>
<label class="lbl_loading">載入資料中.....</label>
<hr size=1 />
<div>
<b>官方回覆數量：</b>
選擇當日每個小時我方所回覆的數量統計（包含批次處理，不包含機器人）。
</div>

<div id="output_reply" style="margin: 30px;"></div>
<label class="lbl_loading">載入資料中.....</label>



<script type="text/javascript">

const TODAY = $.datepicker.formatDate('yy-mm-dd', new Date());
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

get_data('2018-10-29');


});

//http://test-payment.longeplay.com.tw/default/admin3/service/hourly_count_json?date=2018-10-29

function _date(xDay){
	console.log(xDay) ;
	var d = new Date($("#date").val());
	//var x = 5; // go back 5 days!
	d.setDate(d.getDate() + xDay);
	console.log(d) ;
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
	$("#output_reply").hide();
	$(".lbl_loading").show();
  let url = "./hourly_count_json";
  $.ajax({
    type: "GET",
    url: url,
    data: "date=" + date,
  }).done(function(result) {
    //console.log( "Request done: " + result );
		$(".lbl_loading").hide();
		let obj = JSON.parse(result);
		var sum = $.pivotUtilities.aggregatorTemplates.sum;
	  var numberFormat = $.pivotUtilities.numberFormat;
	  var intFormat = numberFormat({digitsAfterDecimal: 0});
	  var heatmap =  $.pivotUtilities.renderers["Heatmap"];

		$("#output").pivot(
		        obj.stat,
		    {
		        rows: ["遊戲"],
		        cols: ["時間"],
		        aggregator: sum(intFormat)(["cnt"]),
		        renderer: heatmap
		    }
		).show();

		$("#output_reply").pivot(
			 obj.stat_reply,
		    {
		        rows: ["遊戲"],
		        cols: ["時間"],
		        aggregator: sum(intFormat)(["cnt"]),
		        renderer: heatmap
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
