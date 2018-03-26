<div id="func_bar">

</div>

<ul class="nav nav-tabs">
  <li class="">
      <a href="<?=site_url("h35vip_statistics/overview/{$game_id}")?>">【VIP 週人數統計】</a>
  </li>
  <li class="">
      <a href="<?=site_url("h35vip_statistics/topup_status/{$game_id}")?>">【VIP 週儲值統計】</a>
  </li>
  <li class="">
      <a href="<?=site_url("h35vip_statistics/overview_monthly/{$game_id}")?>">【VIP 月人數統計】</a>
  </li>
  <li class="">
      <a href="<?=site_url("h35vip_statistics/monthly_topup/{$game_id}")?>">【累積 VIP 月儲值統計】</a>
  </li>
  <li class="active">
      <a href="<?=site_url("h35vip_statistics/contribution_piechart/{$game_id}")?>">【分層貢獻金額佔比】</a>
  </li>
</ul>


<form method="get" action="<?=site_url("h35vip_statistics/contribution_piechart")?>" class="form-search">
	<div class="control-group">
		<select name="is_added">
      <option value="">全部</option>
      <option value="Y" <?=($this->input->get("is_added") =='Y'? 'selected="selected"' : '')?>>已加入Line普R以上用戶</option>
	  </select>

		時間
    <input type="text" name="start_date" id="start_date" value="<?=$start_date?>" class="date required"  style="width:120px"> 至
    <input type="text" name="end_date" id="end_date" value="<?=$end_date?>" style="width:120px" placeholder="現在">
		<input type="submit" class="btn btn-small btn-inverse" name="action" value="篩選">

	</div>
</form>


<div id="donutchart"  style="width: 900px; height: 500px;"></div>
<div id="donutchart2" style="width: 900px; height: 500px;"></div>

<br />
<br />
<br />
<?
$strGoogleData ="";
$strGoogleData2 ="";
if ($query):
  if ($query->num_rows() == 0): echo '<div class="none">查無資料</div>'; else:?>

  <table class="table table-striped table-bordered" style="width:auto;">
    <thead>
      <tr>
        <th nowrap="nowrap">層級</th>
        <th style="width:300px"><?=$start_date?>~<?=$end_date?>儲值總額</th>
        <th style="width:300px">開服至今儲值總額</th>
      </tr>
    </thead>
    <tbody>
      <?foreach($query->result() as $row):
        $vip_text = convert_vip_text($row->vip_ranking);
        $strGoogleData .= "['{$vip_text}', {$row->range_amount}]," ;
        $strGoogleData2 .= "['{$vip_text}', {$row->total_amount}]," ;
        ?>
      <tr>
        <td style="text-align:right"><?=$vip_text ?> </td>
        <td style="text-align:right"><?=number_format($row->range_amount) ?></td>
        <td style="text-align:right"><?=number_format($row->total_amount) ?></td>

      </tr>
    <?endforeach;?>
    </tbody>
  </table>
  <?endif;
endif; ?>



<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<script type="text/javascript">

$(function(){
	$("#end_date").datepicker({
		changeMonth: true,
    changeYear: true
	});

  //$('#end_date').datepicker("setDate", new Date(1985,01,01) );

	$( "#end_date" ).datepicker( "option", "dateFormat", "yy-mm-dd");


  $("#start_date").datepicker({
		changeMonth: true,
    changeYear: true
	});
  //$('#start_date').datepicker("setDate", new Date(<?=date("Y,m,d",strtotime($start_date));?>) );
	$( "#start_date" ).datepicker( "option", "dateFormat", "yy-mm-dd");
  $('#start_date').val('<?=date("Y-m-d",strtotime($start_date));?>');
  $('#end_date').val('<?=date("Y-m-d",strtotime($end_date));?>');

});


  google.charts.load("current", {packages:["corechart"]});
  google.charts.setOnLoadCallback(drawChart);

  function drawChart() {
    var data = google.visualization.arrayToDataTable([
      ['VIP等級','總額'],
        <? echo $strGoogleData; ?>
         ]);
    var options = {
     title: 'VIP層級儲值占比<?echo $start_date."~".$end_date?>',
     is3D: true,
     slices: {
       0:{color:'#A75B10'},
       1:{color:'#808080'},
       2:{color:'#D4AF37'},
       3:{color:'#E5E4E2'},
       4:{color:'#222'}
      }

    };

    var chart = new google.visualization.PieChart(document.getElementById('donutchart'));
    chart.draw(data, options);


    var data2 = google.visualization.arrayToDataTable([
      ['VIP等級','總額'],
        <? echo $strGoogleData2; ?>
         ]);
    var options2 = {
     title: 'VIP層級儲值占比 開服至今',
     is3D: true,
     slices: {
       0:{color:'#A75B10'},
       1:{color:'#808080'},
       2:{color:'#D4AF37'},
       3:{color:'#E5E4E2'},
       4:{color:'#222'}
      }

    };

    var chart2 = new google.visualization.PieChart(document.getElementById('donutchart2'));
    chart2.draw(data2, options2);




  }
</script>

<?
function convert_vip_text($vip_code)
{
  switch ($vip_code) {
    case 'general':
      return '普R';
      break;
    case 'silver':
      return '銀R';
      break;
    case 'gold':
      return '金R';
      break;
    case 'platinum':
      return '白金R';
      break;
    case 'black':
      return '黑R';
      break;
    default:
      return 'No R';
      break;
  }
    //$strGoogleData .= "['{$row->year}/w{$row->week}', {$row->general}, {$row->silver}, {$row->gold}, {$row->platinum}, {$row->black}, '']," ;
}
?>
