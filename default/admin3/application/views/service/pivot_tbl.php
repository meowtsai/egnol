

<form method="get" action="<?=site_url("service/pivot_tbl")?>" class="form-search">
	<div class="control-group">
		選擇日期：<input type="text" name="date" value="<?=$this->input->get("date")?>" style="width:120px" id="date" autocomplete="off">
		<input type="submit" class="btn btn-small btn-inverse" name="action" value="查詢">

	</div>

</form>



<div id="output" class="table table-bordered" style="margin: 30px;"></div>




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
});
</script>
