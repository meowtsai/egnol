
<div class="row">
	<div class="span5">
		<table class="table table-bordered" style="width:auto;">
			<caption>本日案件</caption>
				<tr>
					<th style="width:120px"><a href="<?=site_url("service/get_list?action=查詢&start_date=".date("Y-m-d", time()))?>">新增案件：</a></th>
					<td style="width:100px"><?=$stat->all?>
						(<a href="<?=site_url("service/get_list?type=9&action=查詢&start_date=".date("Y-m-d", time()))?>">電話</a> <?=$stat->phone?>)</td>
				</tr>
				<tr class="<?=$stat->new ? 'warning' : ''?>">
					<th><a href="<?=site_url("service/get_list?status=1&action=查詢&start_date=".date("Y-m-d", time()))?>">未回覆案件：</a></th>
					<td><?=$stat->new?></td>
				</tr>
				<tr>
					<th><a href="<?=site_url("service/get_list?status=2&action=查詢&start_date=".date("Y-m-d", time()))?>">等待中案件：</a></th>
					<td><?=$stat->success?></td>
				</tr>
				<tr>
					<th><a href="<?=site_url("service/get_list?status=4&action=查詢&start_date=".date("Y-m-d", time()))?>">結案：</a></th>
					<td><?=$stat->close?></td>
				</tr>
			</table>
	</div>
	<div class="span5">
		<svg class="chart"></svg>
	</div>


</div>
<table class="table table-bordered" style="width:auto;">
	<caption>合計</caption>
		<tr>
			<th style="width:120px"><a href="<?=site_url("service/get_list?status=1&action=查詢")?>">未回覆案件：</a></th>
			<td style="width:100px"><?=$stat->new_total?></td>
		</tr>
		<tr>
			<th><a href="<?=site_url("service/get_list?status=2&action=查詢")?>">等待中案件：</a></th>
			<td><?=$stat->success_total?></td>
		</tr>
		<tr>
			<th><a href="<?=site_url("service/get_list?status=4&action=查詢")?>">結案：</a></th>
			<td><?=$stat->close_total?></td>
		</tr>
		<tr>
			<th><a href="<?=site_url("service/get_list?status=0&action=查詢")?>">隱藏：</a></th>
			<td><?=$stat->hidden_total?></td>
		</tr>
		<tr>
			<th><a href="<?=site_url("service/get_list?type=9&action=查詢")?>">電話案件：</a></th>
			<td><?=$stat->phone_total?></td>
		</tr>
	</table>

<table class="table table-bordered" style="width:auto;">
	<caption>後送案件</caption>
<? if ( ! empty($allocate[1])):?>
		<tr>
			<th><a href="<?=site_url("service/get_list?allocate_status=1&action=查詢")?>">後送中：</a></th>
			<td>
			<? foreach($allocate[1] as $row):?>
			<span style="display:inline-block; padding:1px 4px;"><a href="<?=site_url("service/get_list?allocate_status=1&allocate_auid={$row->uid}&action=查詢")?>"><?=$row->name?>(<?=$row->cnt?>)</a></span>
			<? endforeach;?>
			</td>
		</tr>
<? endif;?>

<? $ants_total=0;
if ( ! empty($allocate[2])):?>
		<tr>
			<th><a href="<?=site_url("service/get_list?allocate_status=2&action=查詢")?>">完成：</a></th>
			<td>

			<? foreach($allocate[2] as $row):?>
			<? if ($row->role != 'ants'):?>
			<span style="display:inline-block; padding:1px 4px;"><a href="<?=site_url("service/get_list?allocate_status=2&allocate_auid={$row->uid}&action=查詢")?>">
				<?=$row->name?>(<?=$row->cnt?>)</a></span>

			<? else:
				 $ants_total += $row->cnt ?>
				<? endif;?>
			<? endforeach;?>

			<span style="display:inline-block; padding:1px 4px;">
				蟻力群組(<?=$ants_total?>)</span>
			</td>
		</tr>
<? endif;?>

	</table>

	<script>

		<?
		$str_data = [];
		foreach($chart_data->result() as $row){
			array_push($str_data, $row->cnt);
		}

	?>

	var data = 	<?=json_encode($chart_data->result(),JSON_NUMERIC_CHECK  )?>;

	var margin = {top: 20, right: 30, bottom: 30, left: 40},
    width = 460 - margin.left - margin.right,
    height = 500 - margin.top - margin.bottom;



	var x = d3.scaleBand()
	    .rangeRound([0, width])
	    .padding(0.1);

	var y = d3.scaleLinear()
	    .range([height, 0]);

	var xAxis = d3.svg.axis()
    .scale(x)
    .orient("bottom");

		var chart = d3.select(".chart")
    .attr("width", width + margin.left + margin.right)
    .attr("height", height + margin.top + margin.bottom)
  .append("g")
		.attr("class", "x axis")
    .attr("transform", "translate(" + margin.left + "," + margin.top + ")").call(xAxis);;



	  x.domain(data.map(function(d) { return d.name; }));
	  y.domain([0, d3.max(data, function(d) { return d.value; })]);

	  var bar = chart.selectAll("g")
	      .data(data)
	    .enter().append("g")
	      .attr("transform", function(d) { return "translate(" + x(d.name) + ",0)"; });

	  bar.append("rect")
	      .attr("y", function(d) { return y(d.value); })
	      .attr("height", function(d) { return height - y(d.value); })
	      .attr("width", x.bandwidth());

	  bar.append("text")
	      .attr("x", x.bandwidth() / 2)
	      .attr("y", function(d) { return y(d.value) + 3; })
	      .attr("dy", ".75em")
	      .text(function(d) { return d.value; });


	function type(d) {
	  d.value = +d.value; // coerce to number
	  return d;
	}

	</script>
