


	<div class="control-group">
		選擇月份：
		<select name="select_month" id="select_month">
			<option value="">全部</option>
			<?foreach($month_data as $m_row):?>
			<option value="<?=$m_row->month?>"  <?=($this->input->get("select_month") ==$m_row->month? 'selected="selected"' : '')?> ><?=$m_row->month ?></option>
			<?endforeach;?>
		</select>

		<a href="javascript:void(0);" onclick="get_data($('#select_month').val())" class="btn btn-small btn-inverse" role="button">查詢</a>

	</div>




<div>
<b>官方回覆數量:</b>
每天每個遊戲的回覆數量
</div>
<div id="output"  style="margin: 30px;"></div>
<label class="lbl_loading">載入資料中.....</label>
<hr size=1 />




<script type="text/javascript">
$(".lbl_loading").hide();
// const TODAY = $.datepicker.formatDate('yy-mm-dd', new Date());
// $(function(){
//   var getDate = $("#date").val();
//   //console.log();
//   $("#date").datepicker({
//     changeMonth: true,
//     changeYear: true
//   });
//   $( "#date" ).datepicker("option", "dateFormat", "yy-mm-dd");
//
//   if (!getDate){
//     getDate =  $.datepicker.formatDate('yy-mm-dd', new Date());
//   }
//
//
//   $("#date").val(getDate);
//   //$( "#date" ).datepicker( "option", "defaultDate", -7 );
//
// //console.log($("#date").val());
//
// get_data(TODAY);
//
//
// });

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
  let url = "./daily_count_json";
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

	  // var heatmap =  $.pivotUtilities.renderers["Heatmap"];

		$("#output").pivotUI(
		        obj.stat,
		    {
		        rows: ["遊戲"],
		        cols: ["日期"],
						//aggregator: sum(intFormat)(["cnt"]),
						aggregators: {
									 "數量":  function() { return tpl.sum(intFormat)(["數量"]) },
							 },

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
