<div id="func_bar">

</div>

<ul class="nav nav-tabs">
  <li class="">
      <a href="<?=site_url("h35vip_statistics/overview/{$game_id}")?>">【VIP 週人數統計】</a>
  </li>
  <li class="active">
      <a href="<?=site_url("h35vip_statistics/topup_status/{$game_id}")?>">【VIP 週儲值統計】</a>
  </li>
  <li class="">
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
  <!-- <li class="">
      <a href="<?=site_url("h35vip_statistics/hourly_topup")?>">by時間儲值總覽</a>
  </li>
  <li class="">
      <a href="<?=site_url("h35vip_statistics/country_distribution")?>">國家別</a>
  </li> -->
</ul>

<form method="get" action="<?=site_url("h35vip_statistics/topup_status/{$game_id}")?>" class="form-search">
	<div class="control-group">
		<select name="is_added">
      <option value="">全部</option>
      <option value="Y" <?=($this->input->get("is_added") =='Y'? 'selected="selected"' : '')?>>已加入Line</option>
      <option value="R" <?=($this->input->get("is_added") =='R'? 'selected="selected"' : '')?>>已加入Line普R以上用戶</option>
	  </select>

		時間
    <select name="start_week">
      <option value="">全部</option>
      <?foreach($week_data as $w_row):?>
      <option value="<?=$w_row->myyearweek?>"  <?=($this->input->get("start_week") ==$w_row->myyearweek? 'selected="selected"' : '')?> ><?=substr($w_row->myyearweek, 0,4)?>/<?=$w_row->mymonth?>/w<?=substr($w_row->myyearweek, 4,2)?></option>
      <?endforeach;?>
    </select>
    <select name="end_week">
      <option value="">全部</option>
      <?foreach($week_data as $w_row):?>
      <option value="<?=$w_row->myyearweek?>" <?=($this->input->get("end_week") ==$w_row->myyearweek? 'selected="selected"' : '')?>><?=substr($w_row->myyearweek, 0,4)?>/<?=$w_row->mymonth?>/w<?=substr($w_row->myyearweek, 4,2)?></option>
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
$strGoogleHeader ="";
if ($query):
  if ($query->num_rows() == 0): echo '<div class="none">查無資料</div>'; else:?>

<?
  $data_set = $query->result();
  //print_r($data_set[0]);
  //stdClass Object ( [year] => 2018 [week] => 04 [first_date] => 2018-01-28 [sS2 燕十三] => 200835 [sS3 慕容秋荻] => 0 [sS4 娃娃] => 0 [sS5 楚留香] => 0 [sS6 江小魚] => 0 [sS6 江小鱼] => 0 [sS7 李寻欢] => 0 [s提前測試 謝曉峰] => 14830 )

  $array_row = (array)$data_set[0];
  //$columns = count((array)$row);
  //echo "columns:".$columns;
  //echo "hello" . $array_row["sS4 娃娃"];
  $array_row_keys =  array_keys($array_row);
  //echo "hello" .$array_row_keys[3]; //S2 燕十三


?>
  <table class="table table-striped table-bordered" style="width:auto;">
    <thead>
      <tr>
        <th nowrap="nowrap">年/週</th>
        <th nowrap="nowrap">當周首日</th>
        <?
        for ($x_num = 3; $x_num <= count($array_row_keys)-1; $x_num++) {
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
      //echo  $row->[3];
      //print_r($row);
      $strGoogleData .= "['{$row->year}/w{$row->week}'," ;
      ?>


      <tr>
        <td nowrap="nowrap"><?="{$row->year}/w{$row->week}" ?></td>
        <td nowrap="nowrap"><?="{$row->first_date}" ?></td>
        <?
        for ($x_num = 3; $x_num <= count($array_row_keys)-1; $x_num++) {
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

        // Create the data table.

        var data = google.visualization.arrayToDataTable([
          ['伺服器',<? echo $strGoogleHeader; ?> { role: 'annotation' }],
            <? echo $strGoogleData; ?>
             ]);

             var options = {
               ...sizeOption,
               legend: { position: 'top', maxLines: 3 },
               bar: { groupWidth: '75%' },
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

        // Instantiate and draw our chart, passing in some options.
        var chart = new google.charts.Bar(document.getElementById('barchart_material'));

        chart.draw(data, google.charts.Bar.convertOptions(options));


      }
    </script>
