<?
$warning = "";
if ($ticket->status==1 && $ticket->allocate_admin_uid==$_SESSION['admin_uid']) $warning = "warning";
else if (($ticket->status==2 || $ticket->status==3) && $ticket->admin_uid==$_SESSION['admin_uid']) $warning = "warning";
?>
<style>
<!--
#question {}
#question table.officail {background:#e7efe5;}
#content {width:600px;}
-->
</style>

<legend>工作單編號 #<?=$ticket->id?></legend>	

<div id="func_bar">
    <? if ($ticket->status==1 && $ticket->allocate_admin_uid==$_SESSION['admin_uid']):?>
  
	    <form id="allocate_form" method="post" action="<?=site_url("ticket/allocate_json")?>" style="margin:0; display:inline-block">
            <input type="hidden" name="ticket_id" value="<?=$ticket->id?>">	
            <input type="hidden" name="allocate_result" value="<?=$ticket->allocate_result?>">
		
            <div style="line-height:32px;">
                轉交給 
                <select name="allocate_admin_uid" style="width:120px; margin-bottom:0;">
                    <? foreach($allocate_users->result() as $row):?>
                    <option value="<?=$row->uid?>"><?=$row->name?> (<?=$row->role_desc?>)</option>
                    <? endforeach;?>
                </select>
                <br>
                <input type="text" name="result" style="width:200px; margin:0;" placeholder="轉交描述">
                <br>
                <div style="text-align:right;"><input type="submit" class="btn" value="送出"/></div>
            </div>
	    </form>
  
	<? elseif ($ticket->status == '3' && $ticket->admin_uid==$_SESSION['admin_uid']):?>	
	    <a href="<?=site_url("ticket/edit/{$ticket->id}")?>" class="btn btn-primary">編輯</a>
    <? endif;?>	
</div>


<div id="ticket">
	
	<div id="content">
	
	<table class="table table-bordered">
		<tr class="<?=$warning?>">
			<td style="width:80px;">狀態：</td>
			<td colspan="3">
			<?
				$status = $this->config->item("ticket_status");
				echo $status[$ticket->status];
			?>
			
				<?  if ($ticket->status == '2' || $ticket->status == '4'):?>
					<?= $ticket->is_read ? '<span style="color:#090">(分派人員已讀)</span>' : '<span style="color:#999">(分派人員未讀)</span>'; ?>
				<? endif;?>			
	
	<? if ($ticket->allocate_result):?>
		<div style="color:#666; font-size:13px; margin:6px 0;"><?=$ticket->allocate_result?></div>
	<? endif;?>
	
			</td>
		</tr>
		<tr class="<?=$warning?>">
			<td>類型：</td>
			<td>
			<?
				$ticket_type = $this->config->item("ticket_type");
				echo $ticket_type[$ticket->type];
			?>
			</td>
			<td>需求程度：</td>
			<td>
			<?
				$ticket_urgency = $this->config->item("ticket_urgency");
				echo $ticket_urgency[$ticket->urgency];
			?>
			</td>
		</tr>
		<tr class="<?=$warning?>">
			<td>遊戲名稱：</td>
			<td colspan="3"><?=($ticket->game_name)?$ticket->game_name:""?></td>
		</tr>
		<tr class="<?=$warning?>">
			<td>需求人員：</td>
			<td><?=$ticket->name?></td>
			<td>建單日期：</td>
			<td><?=$ticket->create_time?></td>
		</tr>	
		<tr class="<?=$warning?>">
			<td>處理人員：</td>
			<td><?=$ticket->allocate_user_name?></td>
			<td>通知人員：</td>
			<td><?=$ticket->cc_user_name?></td>
		</tr>	
		<tr class="<?=$warning?>">
			<td style="vertical-align:top">標題：</td>
			<td colspan="3" style="word-break: break-all"><?=$ticket->title?></td>
		</tr>				
		<tr class="<?=$warning?>">
			<td style="vertical-align:top">描述：</td>
			<td colspan="3" style="word-break: break-all"><?=$ticket->content?></td>
		</tr>		
		<tr class="<?=$warning?>">
			<td>附件：</td>
			<td colspan="3">
				<? if ($ticket->file_path1):?>
                    <? if(@is_array(getimagesize($mediapath))):?>
                    <a href="<?=$ticket->file_path1?>" target="_blank">
                        <img src="<?=$ticket->file_path1?>" style="max-width:400px;">
                    </a>
                    <? else:?>
                    <a href="<?=$ticket->file_path1?>" target="_blank">
                        <?=basename($ticket->file_path1)?>
                    </a>
                    <? endif;?>
				<? endif;?>
				<? if ($ticket->file_path2):?>
                    <br>
                    <? if(@is_array(getimagesize($mediapath))):?>
                    <a href="<?=$ticket->file_path2?>" target="_blank">
                        <img src="<?=$ticket->file_path2?>" style="max-width:400px;">
                    </a>
                    <? else:?>
                    <a href="<?=$ticket->file_path2?>" target="_blank">
                        <?=basename($ticket->file_path2)?>
                    </a>
                    <? endif;?>
				<? endif;?>
				<? if ($ticket->file_path3):?>
                    <br>
                    <? if(@is_array(getimagesize($mediapath))):?>
                    <a href="<?=$ticket->file_path3?>" target="_blank">
                        <img src="<?=$ticket->file_path3?>" style="max-width:400px;">
                    </a>
                    <? else:?>
                    <a href="<?=$ticket->file_path3?>" target="_blank">
                        <?=basename($ticket->file_path3)?>
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

        <form id="reply_form" method="post" action="<?=site_url("ticket/modify_reply_json")?>">
            <input type="hidden" name="ticket_id" value="<?=$ticket->id?>">	
            回覆
            <textarea name="content" rows="5" style="width:98%" class="required"></textarea>
            
            <button type="submit" class="btn ">確認送出</button>
        </form>
	</div>		
		
	<? if ($ticket->status == '1' && $ticket->allocate_admin_uid==$_SESSION['admin_uid']):?>

    <div class="form-actions">
        <a href="javascript:;" url="<?=site_url("ticket/move_ticket/{$ticket->id}?status=3")?>" class="json_post pull-left btn btn-danger">退回需求</a>
        <a href="javascript:;" url="<?=site_url("ticket/move_ticket/{$ticket->id}?status=2")?>" class="json_post pull-right btn btn-success">完成待需求者確認</a>
    </div>
    <? elseif ($ticket->status == '2' && $ticket->admin_uid==$_SESSION['admin_uid']): ?> 

    <div class="form-actions">
        <a href="javascript:;" url="<?=site_url("ticket/move_ticket/{$ticket->id}?status=1")?>" class="json_post pull-left btn btn-danger">退回處理</a>
        <a href="javascript:;" url="<?=site_url("ticket/move_ticket/{$ticket->id}?status=4")?>" class="json_post pull-right btn btn-success">結案</a>
    </div>
    <? elseif ($ticket->status == '3' && $ticket->admin_uid==$_SESSION['admin_uid']): ?> 

    <div class="form-actions">
        <a href="javascript:;" url="<?=site_url("ticket/move_ticket/{$ticket->id}?status=0")?>" class="json_post pull-left btn btn-active">取消需求</a>
        <a href="javascript:;" url="<?=site_url("ticket/move_ticket/{$ticket->id}?status=1")?>" class="json_post pull-right btn btn-success">再次分派</a>
    </div>
	<? endif;?>
	
</div>	