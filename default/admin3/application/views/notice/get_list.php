
<div id="func_bar">
	<a href="<?=site_url("notice/add")?>" class="btn btn-primary">+ 新增</a>
</div>

<?=tran_pagination($this->pagination->create_links());?>

<table class="table table-striped table-bordered">
	<thead>
		<tr><th style="width:50px;">#</th><th>標題</th><th style="width:90px">建立時間</th><th style="width:70px">是否發布</th><th style="width:80px">人數(已讀)</th><th style="width:50px;"></th></tr>
	</thead>
	<tbody>
	<? foreach($query->result() as $row): ?>
		<tr>
			<td><?=$row->id?></td>
			<td>
				<?=$row->title?>
				<a href="http://www.long_e.com.tw/notice/preview/<?=$row->id?>" target="_blank" title="預覽"><i class="icon icon-search"></i></a>
			</td>
			<td style="font-size:13px; color:#666">
				<?=date("Y/m/d H:i", strtotime($row->create_time))?>
			</td>
			<td><?=$row->is_active ? '是' : '否'?></td>			
			<td>
				<? 
					$total_cnt = $this->db->query("select count(*) cnt from notice_targets where notice_id={$row->id}")->row()->cnt;
					$read_cnt = $this->db->query("select count(*) cnt from notice_targets where notice_id={$row->id} and `is_read`=1")->row()->cnt;					
				?>
				<a href="<?=site_url("notice/get_user/{$row->id}")?>" title="檢視"><?=$total_cnt?>(<?=$read_cnt?>)</a>
			</td>
			<td>			
				<div class="btn-group">
					<? if ($total_cnt == 0):?>
					<a href="<?=site_url("notice/choose/{$row->id}")?>" class="btn btn-mini btn-success">
						選擇玩家
					</a>
					<? else:?>
					<a href="<?=site_url("notice/choose/{$row->id}")?>" class="btn btn-mini">
						重選玩家
					</a>
					<? endif;?>
					<a href="<?=site_url("notice/edit/{$row->id}?record={$this->input->get("record")}")?>" class="btn btn-mini">
						修改
					</a>					
					<a class="btn btn-mini dropdown-toggle" data-toggle="dropdown"><span class="caret"></span></a>	
					<ul class="dropdown-menu pull-right">
						<li><a href="javascript:;" class="del" url="<?=site_url("notice/delete/{$row->id}")?>">
								<i class="icon icon-remove"></i>  刪除</a></li>
						<li class="dropdown-submenu pull-left">
						    <a tabindex="-1" href="#">調整狀態</a>
						    <ul class="dropdown-menu">
								<li><a href="javascript:;" class="json_post" url="<?=site_url("notice/set_is_active/{$row->id}/1")?>">開啟</a></li> 
								<li><a href="javascript:;" class="json_post" url="<?=site_url("notice/set_is_active/{$row->id}/0")?>">關閉</a></li>
						    </ul>
						</li>
					</ul>
				</div>				
			</td>				
		</tr>
	<? endforeach;?>
	</tbody>
</table>

