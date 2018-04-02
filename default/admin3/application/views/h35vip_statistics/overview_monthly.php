<div id="func_bar">

</div>

<ul class="nav nav-tabs">
  <li class="">
      <a href="<?=site_url("h35vip_statistics/overview/{$game_id}")?>">【VIP 週人數統計】</a>
  </li>
  <li class="">
      <a href="<?=site_url("h35vip_statistics/topup_status/{$game_id}")?>">【VIP 週儲值統計】</a>
  </li>
  <li class="active">
      <a href="<?=site_url("h35vip_statistics/overview_monthly/{$game_id}")?>">【VIP 月人數統計】</a>
  </li>
  <li class="">
      <a href="<?=site_url("h35vip_statistics/monthly_topup/{$game_id}")?>">【累積 VIP 月儲值統計】</a>
  </li>
  <li class="">
      <a href="<?=site_url("h35vip_statistics/contribution_piechart/{$game_id}")?>">【分層貢獻金額佔比】</a>
  </li>
  <li class="">
      <a href="<?=site_url("h35vip_statistics/country_distribution/{$game_id}")?>">【國家別】</a>
  </li>
</ul>


<form method="get" action="<?=site_url("h35vip_statistics/overview_monthly/{$game_id}")?>" class="form-search">
	<div class="control-group">
		<select name="is_added">
      <option value="">全部</option>
      <option value="Y" <?=($this->input->get("is_added") =='Y'? 'selected="selected"' : '')?>>已加入Line普R以上用戶</option>
	  </select>

		時間
    <select name="start_month">
      <option value="">全部</option>
      <?foreach($month_data as $m_row):?>
      <option value="<?=$m_row->yearmonth?>"  <?=($this->input->get("start_month") ==$m_row->yearmonth? 'selected="selected"' : '')?> ><?=$m_row->yearmonth?></option>
      <?endforeach;?>
    </select>
    <select name="end_month">
      <option value="">全部</option>
      <?foreach($month_data as $m_row):?>
      <option value="<?=$m_row->yearmonth?>"  <?=($this->input->get("end_month") ==$m_row->yearmonth? 'selected="selected"' : '')?> ><?=$m_row->yearmonth?></option>
      <?endforeach;?>
    </select>

		<input type="submit" class="btn btn-small btn-inverse" name="action" value="篩選">

	</div>
</form>


<div id="barchart_material"></div>
<button id="cmdSizeIt" onclick="drawChart()">縮小圖表</button>
<br />
<br />
<br />
<?
$strGoogleData ="";
if ($query):
  if ($query->num_rows() == 0): echo '<div class="none">查無資料</div>'; else:?>

  <table class="table table-striped table-bordered" style="width:auto;">
    <thead>
      <tr>
        <th nowrap="nowrap">年/月</th>
        <th style="width:70px">普R</th>
        <th style="width:70px">銀R</th>
        <th style="width:70px">金R</th>
        <th style="width:70px">白金R</th>
        <th style="width:70px">黑R</th>
        <th style="width:100px">總人數</th>
        <th style="width:100px">成長</th>
        <th style="width:100px">儲值總額</th>
      </tr>
    </thead>
    <tbody>

    <?
      $prev_t_data = 0;
      $grow_rate = 0;

      $prev_accum_data = 0;
      $topup_this_month = 0;
      foreach($query->result() as $row):
      $strGoogleData .= "['{$row->yearmonth}', {$row->general}, {$row->silver}, {$row->gold}, {$row->platinum}, {$row->black}, '']," ;
      if ($prev_t_data != 0)
      {
        $grow_rate = ($row->month_total/$prev_t_data) * 100;
      }
      else {
        $grow_rate = 0;
      }

      $prev_t_data = $row->month_total;

      if ($prev_accum_data != 0)
      {
        $topup_this_month =  $row->accumulated_total - $prev_accum_data ;
      }
      else {
        $topup_this_month = $row->accumulated_total;
      }

      $prev_accum_data = $row->accumulated_total;

      ?>


      <tr>
        <td nowrap="nowrap"><?="{$row->yearmonth}" ?></td>
        <td style="text-align:right"><?=$row->general ?> </td>
        <td style="text-align:right"><?=$row->silver ?></td>
        <td style="text-align:right"><?=$row->gold ?></td>
        <td style="text-align:right"><?=$row->platinum ?></td>
        <td style="text-align:right"><?=$row->black ?></td>
        <td style="text-align:right"><?=$row->month_total ?></td>
        <td style="text-align:right"> <span style="color:green"><small>▲ <?=number_format($grow_rate, 2, '.', '') ?>%</small></span></td>
        <td style="text-align:right"><?=number_format($topup_this_month) ?></td>

      </tr>
    <?endforeach;?>
    </tbody>
  </table>
  <?endif;
endif; ?>





<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<script type="text/javascript">

     // Load the Visualization API and the corechart package.
     google.charts.load('current', {'packages':['bar']});

     // Set a callback to run when the Google Visualization API is loaded.
     google.charts.setOnLoadCallback(drawChart);

     // Callback that creates and populates a data table,
     // instantiates the pie chart, passes in the data and
     // draws it.
     function drawChart() {
       var x = document.getElementById("cmdSizeIt");
       var sizeOption = {
         width:800,
         height:600,
       };
       if (x.innerText=='放大圖表')
       {
         sizeOption = {
           width:1200,
           height:900,
         };
         x.innerText = '縮小圖表'
       }
       else {
         sizeOption = {
           width:600,
           height:400,
         };
         x.innerText = '放大圖表'
       }
       <?
        $vip_header = ($game_id=='h35naxx1hmt'? "['VIP 等級','普R:$15萬~20萬', '銀R:$20萬~40萬', '金R:$40萬~70萬', '白金R:$70萬-100萬', '黑R:儲值$100萬以上',{ role: 'annotation' }]"
        :
         "['VIP 等級','普R:$5萬~10萬', '銀R:$10萬以上', '金R:未定義', '白金R:未定義', '黑R:未定義',{ role: 'annotation' }]");
       ?>
       var data = google.visualization.arrayToDataTable([
         <? echo $vip_header; ?>,
         <? echo $strGoogleData; ?>
             ]);

            var options = {
              ...sizeOption,
              legend: { position: 'top', maxLines: 3 },
              bar: { groupWidth: '75%' },
              isStacked: true,
              series: {
                0:{color:'#A75B10'},
                1:{color:'#808080'},
                2:{color:'#D4AF37'},
                3:{color:'#E5E4E2'},
                4:{color:'#222'}
              }
            };

       // Instantiate and draw our chart, passing in some options.
       var chart = new google.charts.Bar(document.getElementById('barchart_material'));

       chart.draw(data, google.charts.Bar.convertOptions(options));


     }
   </script>
