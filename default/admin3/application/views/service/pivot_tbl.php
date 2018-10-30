

<form method="get" action="<?=site_url("service/pivot_tbl")?>" class="form-search">
	<div class="control-group">
		選擇日期：<input type="text" name="date" value="<?=$this->input->get("date")?>" style="width:120px" id="date" autocomplete="off">
		<input type="submit" class="btn btn-small btn-inverse" name="action" value="查詢">

	</div>

</form>



<div>
<b>進件數量：</b>
選擇當日每個小時玩家所送出提問單的數量統計。
</div>
<div id="output"  style="margin: 30px;"></div>

<hr size=1 />
<div>
<b>官方回覆數量：</b>
選擇當日每個小時我方所回覆的數量統計（包含批次處理，不包含機器人）。
</div>

<div id="output_reply" style="margin: 30px;"></div>




<script type="text/javascript">

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
  var sum = $.pivotUtilities.aggregatorTemplates.sum;
  var numberFormat = $.pivotUtilities.numberFormat;
  var intFormat = numberFormat({digitsAfterDecimal: 0});
  var heatmap =  $.pivotUtilities.renderers["Heatmap"];

$("#output").pivot(
        <?=json_encode($stat);?>
    ,
    {
        rows: ["遊戲"],
        cols: ["時間"],
        aggregator: sum(intFormat)(["cnt"]),
        renderer: heatmap
    }
);

$("#output_reply").pivot(
        <?=json_encode($stat_reply);?>
    ,
    {
        rows: ["遊戲"],
        cols: ["時間"],
        aggregator: sum(intFormat)(["cnt"]),
        renderer: heatmap
    }
);
});
</script>
