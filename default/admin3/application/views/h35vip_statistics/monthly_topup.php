<div id="func_bar">

</div>

<ul class="nav nav-tabs">
  <li class="">
      <a href="<?=site_url("h35vip_statistics/overview")?>">【VIP 週人數統計】</a>
  </li>
  <li class="">
      <a href="<?=site_url("h35vip_statistics/topup_status")?>">【VIP 週儲值統計】</a>
  </li>
  <li class="">
      <a href="<?=site_url("h35vip_statistics/overview_monthly")?>">【VIP 月人數統計】</a>
  </li>
  <li class="active">
      <a href="<?=site_url("h35vip_statistics/monthly_topup")?>">【累積 VIP 月儲值統計】</a>
  </li>
  <li class="">
      <a href="<?=site_url("h35vip_statistics/contribution_piechart")?>">【分層貢獻金額佔比】</a>
  </li>
  <!-- <li class="">
      <a href="<?=site_url("h35vip_statistics/hourly_topup")?>">by時間儲值總覽</a>
  </li>
  <li class="">
      <a href="<?=site_url("h35vip_statistics/country_distribution")?>">國家別</a>
  </li> -->
</ul>

<form method="get" action="<?=site_url("h35vip_statistics/monthly_topup")?>" class="form-search">
	<div class="control-group">
		<select name="is_added">
      <option value="">全部</option>
      <option value="Y" <?=($this->input->get("is_added") =='Y'? 'selected="selected"' : '')?>>已加入Line</option>
      <option value="R" <?=($this->input->get("is_added") =='R'? 'selected="selected"' : '')?>>已加入Line普R以上用戶</option>
	  </select>


		時間
    <select name="select_month">
      <option value="">全部</option>
      <?foreach($month_data as $m_row):?>
      <option value="<?=$m_row->month?>"  <?=($this->input->get("select_month") ==$m_row->month? 'selected="selected"' : '')?> ><?=$m_row->month ?></option>
      <?endforeach;?>
    </select>

    <select name="select_month_end">
      <option value="">全部</option>
      <?foreach($month_data as $m_row):?>
      <option value="<?=$m_row->month?>"  <?=($this->input->get("select_month_end") ==$m_row->month? 'selected="selected"' : '')?> ><?=$m_row->month ?></option>
      <?endforeach;?>
    </select>

		<input type="submit" class="btn btn-small btn-inverse" name="action" value="篩選">

	</div>
</form>




<div id="barchart_material"></div>

<?
$strGoogleData ="";
if ($query):
  if ($query->num_rows() == 0): echo '<div class="none">查無資料</div>'; else:?>

  <table class="table table-striped table-bordered" style="width:auto;">
    <thead>
      <tr>
        <th nowrap="nowrap">日期</th>
        <th style="width:70px">戰爭領袖</th>
        <th style="width:70px">黎明誓約</th>
        <th style="width:70px">星辰護佑</th>
        <th style="width:70px">裁決之劍</th>
        <th style="width:70px">狂野之怒</th>
        <th style="width:70px">加總</th>
      </tr>
    </thead>
    <tbody>

    <?
      foreach($query->result() as $row):
      $strGoogleData .= "['{$row->month}', {$row->s10001}, {$row->s10002}, {$row->s10003}, {$row->s10004}, {$row->s10005}]," ;
      $sum = $row->s10001 + $row->s10002 + $row->s10003 + $row->s10004 + $row->s10005;
      ?>


      <tr>
        <td nowrap="nowrap"><?="{$row->month}" ?></td>
        <td style="text-align:right"><?=number_format($row->s10001) ?> </td>
        <td style="text-align:right"><?=number_format($row->s10002) ?></td>
        <td style="text-align:right"><?=number_format($row->s10003) ?></td>
        <td style="text-align:right"><?=number_format($row->s10004) ?></td>
        <td style="text-align:right"><?=number_format($row->s10005) ?></td>
        <td style="text-align:right"><?=number_format($sum) ?></td>


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
         var data = google.visualization.arrayToDataTable([
             ['月份','戰爭領袖', '黎明誓約', '星辰護佑', '裁決之劍', '狂野之怒'],
             <? echo $strGoogleData; ?>
           ]);

           var options = {
             chart: {
               title: '光明之戰vip儲值總覽',
               subtitle: '分伺服器:  <? echo $this->input->get("select_month"); ?> ',
             },
             bars: 'vertical',
             vAxis: {format: 'decimal'},
             width:600,
             height: 400,
             isStacked: true,
             series: {
               0:{color:'#0080FF'},
               1:{color:'#C50047'},
               2:{color:'#889F00'},
               3:{color:'#9E308E'},
               4:{color:'#73B9FF'}
             }
           };

       var chart = new google.charts.Bar(document.getElementById('barchart_material'));

       chart.draw(data, google.charts.Bar.convertOptions(options));



      }
    </script>
