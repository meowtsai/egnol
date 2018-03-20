<div id="func_bar">

</div>

<ul class="nav nav-tabs">
  <li class="">
      <a href="<?=site_url("h35vip_statistics/overview")?>">【VIP週人數統計】</a>
  </li>
  <li class="">
      <a href="<?=site_url("h35vip_statistics/topup_status")?>">各伺服器每週VIP儲值情況</a>
  </li>
  <li class="">
      <a href="<?=site_url("h35vip_statistics/vip_distribution")?>">各伺服器各階VIP人數</a>
  </li>
  <li class="">
      <a href="<?=site_url("h35vip_statistics/daily_topup")?>">各伺服器by月份儲值總覽</a>
  </li>
  <li class="active">
      <a href="<?=site_url("h35vip_statistics/hourly_topup")?>">by時間儲值總覽</a>
  </li>
  <li class="">
      <a href="<?=site_url("h35vip_statistics/country_distribution")?>">國家別</a>
  </li>
</ul>

<form method="get" action="<?=site_url("h35vip_statistics/hourly_topup")?>" class="form-search">
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




<div id="line_top_x"></div>

<?
$strGoogleData ="";
if ($query):
  if ($query->num_rows() == 0): echo '<div class="none">查無資料</div>'; else:?>

  <table class="table table-striped table-bordered" style="width:auto;">
    <thead>
      <tr>
        <th nowrap="nowrap">時間</th>
        <th style="width:70px">Sun</th>
        <th style="width:70px">Mon</th>
        <th style="width:70px">Tue</th>
        <th style="width:70px">Wed</th>
        <th style="width:70px">Thu</th>
        <th style="width:70px">Fri</th>
        <th style="width:70px">Sat</th>
      </tr>
    </thead>
    <tbody>

    <?
      foreach($query->result() as $row):
      $strGoogleData .= "['{$row->hour}', {$row->Sun}, {$row->Mon}, {$row->Tue}, {$row->Wed}, {$row->Thu}, {$row->Fri}, {$row->Sat}]," ;

      ?>

      <tr>
        <td nowrap="nowrap"><?="{$row->hour}" ?></td>
        <td style="text-align:right"><?=number_format($row->Sun) ?> </td>
        <td style="text-align:right"><?=number_format($row->Mon) ?></td>
        <td style="text-align:right"><?=number_format($row->Tue) ?></td>
        <td style="text-align:right"><?=number_format($row->Wed) ?></td>
        <td style="text-align:right"><?=number_format($row->Thu) ?></td>
        <td style="text-align:right"><?=number_format($row->Fri) ?></td>
        <td style="text-align:right"><?=number_format($row->Sat) ?></td>

      </tr>
    <?endforeach;?>
    </tbody>
  </table>
  <?endif;
endif; ?>





<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script type="text/javascript">

      // Load the Visualization API and the corechart package.
      google.charts.load('current', {'packages':['line']});
      // Set a callback to run when the Google Visualization API is loaded.
      google.charts.setOnLoadCallback(drawChart);

      // Callback that creates and populates a data table,
      // instantiates the pie chart, passes in the data and
      // draws it.
      function drawChart() {
        var data = new google.visualization.DataTable();
        data.addColumn('string', 'hour');
        data.addColumn('number', 'Sun');
        data.addColumn('number', 'Mon');
        data.addColumn('number', 'Tue');
        data.addColumn('number', 'Wed');
        data.addColumn('number', 'Thu');
        data.addColumn('number', 'Fri');
        data.addColumn('number', 'Sat');

        data.addRows([
          <? echo $strGoogleData; ?>
        ]);

        var options = {
          chart: {
            title: '光明之戰vip儲值總覽',
            subtitle: '時間:  <? echo $this->input->get("select_month"); ?> ',
          },
          width: 900,
          height: 500,
          axes: {
            x: {
              0: {side: 'top'}
            }
          }
        };


        var chart = new google.charts.Line(document.getElementById('line_top_x'));

         chart.draw(data, google.charts.Line.convertOptions(options));



      }
    </script>
