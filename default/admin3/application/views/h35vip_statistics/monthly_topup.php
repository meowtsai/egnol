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
  <li class="active">
      <a href="<?=site_url("h35vip_statistics/monthly_topup/{$game_id}")?>">【累積 VIP 月儲值統計】</a>
  </li>
  <li class="">
      <a href="<?=site_url("h35vip_statistics/contribution_piechart/{$game_id}")?>">【分層貢獻金額佔比】</a>
  </li>
</ul>

<form method="get" action="<?=site_url("h35vip_statistics/monthly_topup/{$game_id}")?>" class="form-search">
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
$strGoogleHeader ="";
if ($query):
  if ($query->num_rows() == 0): echo '<div class="none">查無資料</div>'; else:?>

  <?
    $data_set = $query->result();
    $array_row = (array)$data_set[0];
    $array_row_keys =  array_keys($array_row);
  ?>

  <table class="table table-striped table-bordered" style="width:auto;">
    <thead>
      <tr>
        <th nowrap="nowrap">日期</th>
        <?
        for ($x_num = 1; $x_num <= count($array_row_keys)-1; $x_num++) {
            //echo $servers_data[str_replace($array_row_keys[$x_num],"s","")];
            $strGoogleHeader .= "'".$array_row_keys[$x_num]."',";
            echo "<th style='width:70px'>{$array_row_keys[$x_num]}</th>";
        }
        ?>

        
      </tr>
    </thead>
    <tbody>

      <?
        foreach($query->result() as $row):
        $strGoogleData .= "['{$row->myyearmonth}'," ;
        ?>


        <tr>
          <td nowrap="nowrap"><?="{$row->myyearmonth}" ?></td>
          <?
          for ($x_num = 1; $x_num <= count($array_row_keys)-1; $x_num++) {
            $strGoogleData .= "{$row->$array_row_keys[$x_num]},";
              echo "<td style='width:120px'>".number_format((double)$row->$array_row_keys[$x_num])."</td>";
          }
           $strGoogleData .= "''],";
          ?>

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
           ['月份',<? echo $strGoogleHeader; ?> { role: 'annotation' }],
             <? echo $strGoogleData; ?>
            ]);


           var options = {
             chart: {
               title: 'vip儲值總覽',
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
               4:{color:'#73B9FF'},
               5:{color:'#B18904'}
             }
           };

       var chart = new google.charts.Bar(document.getElementById('barchart_material'));

       chart.draw(data, google.charts.Bar.convertOptions(options));



      }
    </script>
