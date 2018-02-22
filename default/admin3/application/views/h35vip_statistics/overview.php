<div id="func_bar">

</div>

<ul class="nav nav-tabs">
    <li class="<?=(empty($span)) ? "active" : ""?>">
        <a href="<?=site_url("h35vip_statistics/overview")?>">VIP 人數成長</a>
    </li>
    <li class="">
        <a href="<?=site_url("h35vip_statistics/topup_status")?>">各伺服器每週VIP儲值情況</a>
    </li>
    <li class="">
        <a href="<?=site_url("h35vip_statistics/vip_distribution")?>">各伺服器各階VIP人數</a>
    </li>


</ul>


<div id="barchart_material"></div>

<?
$strGoogleData ="";
if ($query):
  if ($query->num_rows() == 0): echo '<div class="none">查無資料</div>'; else:?>

  <table class="table table-striped table-bordered" style="width:auto;">
    <thead>
      <tr>
        <th nowrap="nowrap">年/週</th>
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
      $topup_this_week = 0;
      foreach($query->result() as $row):
      $strGoogleData .= "['{$row->year}/w{$row->week}', {$row->general}, {$row->silver}, {$row->gold}, {$row->platinum}, {$row->black}, '']," ;
      if ($prev_t_data != 0)
      {
        $grow_rate = ($row->week_total/$prev_t_data) * 100;
      }
      else {
        $grow_rate = 0;
      }

      $prev_t_data = $row->week_total;

      if ($prev_accum_data != 0)
      {
        $topup_this_week =  $row->accumulated_total - $prev_accum_data ;
      }
      else {
        $topup_this_week = $row->accumulated_total;
      }

      $prev_accum_data = $row->accumulated_total;

      ?>


      <tr>
        <td nowrap="nowrap"><?="{$row->year}/w{$row->week}" ?></td>
        <td style="text-align:right"><?=$row->general ?> </td>
        <td style="text-align:right"><?=$row->silver ?></td>
        <td style="text-align:right"><?=$row->gold ?></td>
        <td style="text-align:right"><?=$row->platinum ?></td>
        <td style="text-align:right"><?=$row->black ?></td>
        <td style="text-align:right"><?=$row->week_total ?></td>
        <td style="text-align:right"> <span style="color:green"><small>▲ <?=number_format($grow_rate, 2, '.', '') ?>%</small></span></td>
        <td style="text-align:right"><?=number_format($topup_this_week) ?></td>

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

        // Create the data table.

        var data = google.visualization.arrayToDataTable([
          ['VIP 等級','普R:$5萬~10萬', '銀R:$10萬~30萬', '金R:$30萬~60萬', '白金R:$60萬-100萬', '黑R:儲值$100萬以上',{ role: 'annotation' }],
            <? echo $strGoogleData; ?>
             ]);

       //  var data = google.visualization.arrayToDataTable([
       //   ['等級','普R', '銀R', '金R', '白金R', '黑R',{ role: 'annotation' } ],
       //   ['2017/46', 2, 0, 0, 0, 0, ''],
       //   ['2017/47', 7, 2, 0, 0, 0, ''],
       //   ['2017/48', 27, 3, 1, 0, 0, ''],
       //   ['2017/49', 46, 13, 3, 0, 0, ''],
       //   ['2017/50', 63, 20, 3, 0, 0, ''],
       //   ['2017/51', 73, 37, 4, 1, 0, ''],
       //   ['2017/52', 100, 52, 4, 1, 0, ''],
       //   ['2017/53', 125, 69, 5, 0, 1, ''],
       //   ['2018/1', 152, 72, 6, 2, 1, ''],
       //   ['2018/2', 168, 81, 5, 5, 1, ''],
       //   ['2018/3', 181, 89, 5, 5, 2, ''],
       //   ['2018/4', 187, 99, 6, 4, 3, ''],
       //   ['2018/5', 194, 113, 8, 4, 3, ''],
       //   ['2018/6', 202, 142, 11, 3, 4, ''],
       //   ['2018/7', 220, 145, 13, 3, 4, ''],
       // ]);




             var options = {
               width: 600,
               height: 400,
               legend: { position: 'top', maxLines: 3 },
               bar: { groupWidth: '75%' },
               isStacked: true,
               colors: ['#d95f02']
             };

        // Instantiate and draw our chart, passing in some options.
        var chart = new google.charts.Bar(document.getElementById('barchart_material'));

        chart.draw(data, google.charts.Bar.convertOptions(options));


      }
    </script>
