<style>
<!--
#question {}
#question table.officail {background:#e7efe5;}
#content {width:600px;}
-->
</style>

<legend>VIP活動編號 #<?=$vip_event->id?></legend>	

<div id="func_bar">
	<? if ($vip_event->status == '1' && $vip_event->admin_uid==$_SESSION['admin_uid']):?>	
	    <a href="<?=site_url("vip/edit_event/{$vip_event->id}")?>" class="btn btn-primary">編輯</a>
    <? endif;?>	
</div>


<div id="vip_event">
	
	<div id="content">
	
	<table class="table table-bordered">
		<tr>
			<td style="width:80px;">狀態：</td>
			<td colspan="3">
			<?
				$status = $this->config->item("vip_event_status");
				echo $status[$vip_event->status];
			?>
			</td>
		</tr>
		<tr>
			<td>遊戲名稱：</td>
			<td colspan="3"><?=($vip_event->game_name)?$vip_event->game_name:""?></td>
		</tr>
		<tr>
			<td>提案人員：</td>
			<td><?=$vip_event->name?></td>
			<td>提案日期：</td>
			<td><?=$vip_event->create_time?></td>
		</tr>
		<tr>
			<td>核准人員：</td>
			<td><?=$vip_event->auth_user_name?></td>
			<td>核准日期：</td>
			<td><?=$vip_event->auth_time?></td>
		</tr>
		<tr>
			<td style="vertical-align:top">標題：</td>
			<td colspan="3" style="word-break: break-all"><?=$vip_event->title?></td>
		</tr>				
		<tr>
			<td style="vertical-align:top">描述：</td>
			<td colspan="3" style="word-break: break-all"><?=$vip_event->content?></td>
		</tr>		
		<tr>
			<td>附件：</td>
			<td colspan="3">
				<? if ($vip_event->file_path1):?>
                    <? if(@is_array(getimagesize($mediapath))):?>
                    <a href="<?=$vip_event->file_path1?>" target="_blank">
                        <img src="<?=$vip_event->file_path1?>" style="max-width:400px;">
                    </a>
                    <? else:?>
                    <a href="<?=$vip_event->file_path1?>" target="_blank">
                        <?=preg_replace('/^.+[\\\\\\/]/', '', $vip_event->file_path1)?>
                    </a>
                    <? endif;?>
				<? endif;?>
				<? if ($vip_event->file_path2):?>
                    <br>
                    <? if(@is_array(getimagesize($mediapath))):?>
                    <a href="<?=$vip_event->file_path2?>" target="_blank">
                        <img src="<?=$vip_event->file_path2?>" style="max-width:400px;">
                    </a>
                    <? else:?>
                    <a href="<?=$vip_event->file_path2?>" target="_blank">
                        <?=preg_replace('/^.+[\\\\\\/]/', '', $vip_event->file_path2)?>
                    </a>
                    <? endif;?>
				<? endif;?>
				<? if ($vip_event->file_path3):?>
                    <br>
                    <? if(@is_array(getimagesize($mediapath))):?>
                    <a href="<?=$vip_event->file_path3?>" target="_blank">
                        <img src="<?=$vip_event->file_path3?>" style="max-width:400px;">
                    </a>
                    <? else:?>
                    <a href="<?=$vip_event->file_path3?>" target="_blank">
                        <?=preg_replace('/^.+[\\\\\\/]/', '', $vip_event->file_path3)?>
                    </a>
                    <? endif;?>
				<? endif;?>								
			</td>
		</tr>
	</table>
	
	<? 
	$no = 1;
	foreach($replies->result() as $row):?>
        <? if ($row->admin_uid==$_SESSION['admin_uid']):?>
        <table class="table table-bordered" style="position:relative;">
            <tr class="success">
                <td style="word-break:break-all"><?=$row->content?></td>
                <td style="width:120px; text-align:center;">
                    NO<?=$no++?><br>
                    <?=date('Y-m-d H:i', strtotime($row->create_time))?>
                </td>
            </tr>
        </table>
        <? else:?>
        <table class="table table-bordered" style="position:relative;">
            <tr>
                <td style="width:120px; text-align:center;">
                    NO<?=$no++?><br>
                    <?=date('Y-m-d H:i', strtotime($row->create_time))?>
                    <? if ($row->admin_uname):?>
                    (<?=$row->admin_uname?>)
                    <? endif;?>
                </td>
                <td style="word-break:break-all"><?=$row->content?></td>
            </tr>
        </table>
        <? endif;?>
	<? endforeach;?>
	</div>
    <div class="form-actions">
        <? if ($vip_event->status==1): ?>
        <a href="javascript:;" url="<?=site_url("vip/move_vip_event/{$vip_event->id}?status=0")?>" class="json_post pull-left btn btn-danger">取消</a>
        <a href="javascript:;" url="<?=site_url("vip/move_vip_event/{$vip_event->id}?status=2")?>" class="json_post pull-right btn btn-success">核准</a>
        <? endif;?>
    </div>
</div>	