<div id="func_bar">

</div>

<ul class="nav nav-tabs">
  <li class="">
      <a href="<?=site_url("h35vip_statistics/overview")?>">VIP 人數成長</a>
  </li>
  <li class="">
      <a href="<?=site_url("h35vip_statistics/topup_status")?>">各伺服器每週VIP儲值情況</a>
  </li>
  <li class="">
      <a href="<?=site_url("h35vip_statistics/vip_distribution")?>">各伺服器各階VIP人數</a>
  </li>
  <li class="active">
      <a href="<?=site_url("h35vip_statistics/daily_topup")?>">各伺服器by月份儲值總覽</a>
  </li>
</ul>

<form method="get" action="<?=site_url("h35vip_statistics/daily_topup")?>" class="form-search">
	<div class="control-group">
		<select name="is_added">
      <option value="">全部</option>
      <option value="Y" <?=($this->input->get("is_added") =='Y'? 'selected="selected"' : '')?>>已加入Line</option>
	  </select>

		時間
    <select name="select_month">
      <option value="">全部</option>
      <?foreach($month_data as $m_row):?>
      <option value="<?=$m_row->month?>"  <?=($this->input->get("select_month") ==$m_row->month? 'selected="selected"' : '')?> ><?=$m_row->month ?></option>
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
      </tr>
    </thead>
    <tbody>

    <?
      foreach($query->result() as $row):
      $strGoogleData .= "['{$row->day}', {$row->s10001}, {$row->s10002}, {$row->s10003}, {$row->s10004}, {$row->s10005}]," ;

      ?>


      <tr>
        <td nowrap="nowrap"><?="{$row->day}" ?></td>
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
         var data = google.visualization.arrayToDataTable([
             ['日期','戰爭領袖', '黎明誓約', '星辰護佑', '裁決之劍', '狂野之怒'],
             <? echo $strGoogleData; ?>
           ]);

           var options = {
             chart: {
               title: '光明之戰vip儲值總覽',
               subtitle: '分伺服器:  <? echo $this->input->get("select_month"); ?> ',
             },
             bars: 'vertical',
             vAxis: {format: 'decimal'},
             height: 400,
             colors: ['#1b9e77', '#d95f02', '#7570b3','blue','red']
           };

       var chart = new google.charts.Bar(document.getElementById('barchart_material'));

       chart.draw(data, google.charts.Bar.convertOptions(options));



      }
    </script>
