<div id="func_bar">
	<a href="<?=site_url("ad/add_group")?>" class="btn btn-primary">+新增</a>
</div>

<form method="get" action="<?=site_url("ad")?>" class="form-search">
			
</form>

<? if (empty($ad_groups)):?>
	
	<div class="none">尚無資料</div>
	
	<? else:?>
	
	<table class="table table-striped table-bordered">
		<thead>
			<tr><th style="width:40px">#</th><th style="width:80px">遊戲</th><th>目標網址</th><th style="width:20px"></th></tr>
		</thead>
		<tbody>
		<? foreach($ad_groups as $row): ?>
			<tr>
				<td style="vertical-align:middle;"><?=$row->id?></td>
				<td style="vertical-align:middle;"><?=$row->name?></td>
				<td>
					Android：<a href="<?=$row->open_url_android?>" target="_blank"><?=$row->open_url_android?></a><br>
					iOS：<a href="<?=$row->open_url_ios?>" target="_blank"><?=$row->open_url_ios?></a>
				</td>
				<td style="vertical-align:middle;">		
					<div class="btn-group">					
						<a href="<?=site_url("ad/add/{$row->id}")?>" class="btn btn-success">+新增廣告</a>
 						<a href="<?=site_url("ad/edit_group/{$row->id}")?>" class="btn">修改</a> 
						<a href="javascript:;" url="<?=site_url("ad/delete_group/{$row->id}")?>" class="json_del btn">刪除</a>
					</div>
				</td>
			</tr>
			<tr>
				<td colspan="4" style="padding:16px;">
				
		<? if ($row->ads):?>
		<table class="table table-bordered" style="margin:0px;">
			<thead>
				<tr><th style="width:90px;">#廣告ID</th><th style="width:90px">建立日期</th><th>追踨網址</th><th style="width:20px"></th></tr>
			</thead>	
			<? foreach($row->ads as $row2): ?>
				<tr>
					<td><a href="<?=site_url("ad/statistics?ad_channel={$row2->ad}&action=時段統計")?>" title="查看統計"><?=$row2->ad?></a></td>
					<td><?=date("Y-m-d", strtotime($row2->create_time))?></td>
					<td style="border:2px solid #dcb; border-width:1px 2px;"><?=base_url()?>/ad/trace?ad=<?=$row2->ad?></td>		
					<td style="text-align:center;">		
						<div class="btn-group">					
							<a href="javascript:;" url="<?=site_url("ad/delete/{$row2->ad}")?>" class="json_del btn btn-mini">刪除</a>
						</div>
					</td>
				</tr>
			<? endforeach;?>				
		</table>	
		<? endif;?>
				
				</td>
			</tr>
		<? endforeach;?>
		</tbody>
	</table>
	
	<? endif;?>
