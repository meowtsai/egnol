<div id="func_bar">

</div>

<ul class="nav nav-tabs">
  <li class="">
      <a href="<?=site_url("h35vip_statistics/overview")?>">VIP 人數成長</a>
  </li>
  <li class="active">
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
        <th style="width:70px">戰爭領袖</th>
        <th style="width:70px">黎明誓約</th>
        <th style="width:70px">星辰護佑</th>
        <th style="width:70px">裁決之劍</th>
        <th style="width:70px">狂野之怒</th>
      </tr>
    </thead>
    <tbody>

    <?
      foreach($query->result() as $row):
      $strGoogleData .= "['{$row->year}/w{$row->week}', {$row->s10001}, {$row->s10002}, {$row->s10003}, {$row->s10004}, {$row->s10005}, '']," ;
      ?>


      <tr>
        <td nowrap="nowrap"><?="{$row->year}/w{$row->week}" ?></td>
        <td style="text-align:right"><?=number_format($row->s10001) ?> </td>
        <td style="text-align:right"><?=number_format($row->s10002) ?></td>
        <td style="text-align:right"><?=number_format($row->s10003) ?></td>
        <td style="text-align:right"><?=number_format($row->s10004) ?></td>
        <td style="text-align:right"><?=number_format($row->s10005) ?></td>

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
          ['伺服器','戰爭領袖', '黎明誓約', '星辰護佑', '裁決之劍', '狂野之怒',{ role: 'annotation' }],
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
               colors: ['#1b9e77']
             };

        // Instantiate and draw our chart, passing in some options.
        var chart = new google.charts.Bar(document.getElementById('barchart_material'));

        chart.draw(data, google.charts.Bar.convertOptions(options));


      }
    </script>
