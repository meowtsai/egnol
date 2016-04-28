<style>
#content {width:800px;}
</style>

<legend>VIP活動編號 #<?=$vip_event->id?></legend>	

<div id="func_bar">
	<? if ($modify_acl || ($vip_event->status == '1' && $vip_event->admin_uid==$_SESSION['admin_uid'])):?>	
	    <a href="<?=site_url("vip/edit_event/{$vip_event->id}")?>" class="btn btn-primary">編輯</a>
    <? endif;?>	
</div>


<div id="vip_event">
	
	<div id="content">
	
	<table class="table table-bordered">
		<tr>
			<td>遊戲名稱：</td>
			<td><?=($vip_event->game_name)?$vip_event->game_name:""?></td>
			<td style="width:80px;">狀態：</td>
			<td style="color:<?=($vip_event->status==2)?"green":"red"?>">
			<?
				$status = $this->config->item("vip_event_status");
				echo $status[$vip_event->status];
			?>
			</td>
			<td style="width:80px;">類型：</td>
			<td>
			<?
				$type = $this->config->item("vip_event_type");
				echo $type[$vip_event->type];
			?>
			</td>
		</tr>
		<tr>
			<td>開始日期：</td>
			<td colspan="2"><?=date('Y-m-d', strtotime($vip_event->start_date))?></td>
			<td>結束日期：</td>
			<td colspan="2"><?=($vip_event->end_date=='0000-00-00 00:00:00')?"":date('Y-m-d', strtotime($vip_event->end_date))?></td>
		</tr>
		<tr>
			<td style="vertical-align:top">標題：</td>
			<td colspan="5" style="word-break: break-all"><?=$vip_event->title?></td>
		</tr>				
		<tr>
			<td style="vertical-align:top">描述：</td>
			<td colspan="5" style="word-break: break-all"><?=$vip_event->content?></td>
		</tr>	
		<tr>
			<td style="vertical-align:top">金額：</td>
			<td colspan="5" style="word-break: break-all"><?=$vip_event->cost?></td>
		</tr>		
		<tr>
			<td>附件：</td>
			<td colspan="5">
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
		<tr>
			<td>提案人員：</td>
			<td colspan="2"><?=$vip_event->name?></td>
			<td>提案日期：</td>
			<td colspan="2"><?=$vip_event->create_time?></td>
		</tr>
		<tr>
			<td>核准人員：</td>
			<td colspan="2"><?=$vip_event->auth_user_name?></td>
			<td>核准日期：</td>
			<td colspan="2"><?=$vip_event->auth_time?></td>
		</tr>
	</table>
	
    <ul class="nav nav-tabs">
        <li class="<?=($this->input->get("ticket_status")==="0") ? "active" : ""?>">
            <a href="<?=site_url("vip/event_view/{$vip_event->id}?ticket_status=0")?>">取消(<?=$vip_event->cancelled_count?>)</a>
        </li>
        <li class="<?=($this->input->get("ticket_status")==null || $this->input->get("ticket_status")==1) ? "active" : ""?>">
            <a href="<?=site_url("vip/event_view/{$vip_event->id}?ticket_status=1")?>">待匯款/新建(<?=$vip_event->pending_count?>)</a>
        </li>
        <li class="<?=($this->input->get("ticket_status")==2) ? "active" : ""?>">
            <a href="<?=site_url("vip/event_view/{$vip_event->id}?ticket_status=2")?>">匯款完成(<?=$vip_event->complete_count?>)</a>
        </li>
        <li class="<?=($this->input->get("ticket_status")==3) ? "active" : ""?>">
            <a href="<?=site_url("vip/event_view/{$vip_event->id}?ticket_status=3")?>">派發完成(<?=$vip_event->delivered_count?>)</a>
        </li>
        <li class="<?=($this->input->get("ticket_status")==4) ? "active" : ""?>">
            <a href="<?=site_url("vip/event_view/{$vip_event->id}?ticket_status=4")?>">結案(<?=$vip_event->closed_count?>)</a>
        </li>
    </ul>
    
    <? if ($vip_tickets):?>
    <table class="table table-striped table-bordered" style="width:auto;">
        <thead>
            <tr>
                <th rowspan="2">編號</th>
                <th colspan="5">用戶資料</th>
                <th colspan="8">訂單資訊</th>
            </tr>
            <tr>
                <th>UID</th>
                <th>伺服器</th>
                <th>角色</th>
                <th>遊戲ID</th>
                <th>LINE</th>
                <th>訂購時間</th>
                <th>處理人</th>
                <th>交易金額</th>
                <th>匯款時間</th>
                <th>匯款帳號</th>
                <th>匯款戶名</th>
                <th colspan="2">動作</th>
            </tr>
        </thead>
        <tbody>
            <? if ($vip_tickets->num_rows() > 0):?>
            
            <? foreach($vip_tickets->result() as $row):?>
            <tr>
				<form id="ticket_form" method="post" action="<?=site_url("vip/modify_ticket_json")?>" style="margin:0">
					<input type="hidden" name="vip_event_id" value="<?=$vip_event->id?>">	
					<input type="hidden" name="ticket_id" value="<?=$row->id?>">	
                    <td><?=$row->id?></td>
                    <td><?=$row->uid?></td>
                    <td><?=$row->server_name?></td>
                    <td><?=$row->character_name?></td>
                    <td><?=$row->in_game_id?></td>
                    <td><?=$row->line?></td>
                    <td><?=$row->create_time?></td>
                    <td><?=$row->admin_uname?></td>
                    <td><?=$row->cost?></td>
                    <? if (!$this->input->get("ticket_status") || $this->input->get("ticket_status")==1): ?>
                        <td><input type="text" name="billing_time" class="date required" value="" style="width:120px"></td>
                        <td><input type="text" name="billing_account" class="required" value="" style="width:60px"></td>
                        <td><input type="text" name="billing_name" class="required" value=""></td>
                    <? else:?>
                        <td><?=$row->billing_time?></td>
                        <td><?=$row->billing_account?></td>
                        <td><?=$row->billing_name?></td>
                    <? endif;?>
                    <? if (!$this->input->get("ticket_status") || $this->input->get("ticket_status")==1): ?>
                        <td> 
                            <input type="hidden" name="action" value="2">	
                            <input type="submit" class="btn btn-small btn-success" value="匯款完成">
                        </td>
                    </form>  	
                    <form id="cancel_ticket_form" method="post" action="<?=site_url("vip/modify_ticket_json")?>" style="margin:0">
                        <input type="hidden" name="vip_event_id" value="<?=$vip_event->id?>">	
                        <input type="hidden" name="action" value="0">	
                        <input type="hidden" name="ticket_id" value="<?=$row->id?>">	
                        <td> 
                            <input type="submit" class="btn btn-small btn-inverse" value="取消訂單">
                        </td>
                    <? elseif ($this->input->get("ticket_status")==2 && $modify_acl):?>
                        <td> 
                            <input type="hidden" name="action" value="3">	
                            <input type="submit" class="btn btn-small btn-success" value="派發完成">
                        </td>
                    </form>  	
                    <form id="cancel_ticket_form" method="post" action="<?=site_url("vip/modify_ticket_json")?>" style="margin:0">
                        <input type="hidden" name="vip_event_id" value="<?=$vip_event->id?>">	
                        <input type="hidden" name="action" value="0">	
                        <input type="hidden" name="ticket_id" value="<?=$row->id?>">	
                        <td> 
                            <input type="submit" class="btn btn-small btn-inverse" value="取消訂單">
                        </td>
                    <? elseif ($this->input->get("ticket_status")==3 && $authorize_acl):?>
                        <td> 
                            <input type="hidden" name="action" value="4">	
                            <input type="submit" class="btn btn-small btn-success" value="結案">
                        </td>
                    </form>  	
                    <form id="cancel_ticket_form" method="post" action="<?=site_url("vip/modify_ticket_json")?>" style="margin:0">
                        <input type="hidden" name="vip_event_id" value="<?=$vip_event->id?>">	
                        <input type="hidden" name="action" value="0">	
                        <input type="hidden" name="ticket_id" value="<?=$row->id?>">	
                        <td>
                            <input type="submit" class="btn btn-small btn-inverse" value="取消訂單">
                        </td>
                    <? else:?>
                        <td colspan="2"> </td>
                    <? endif;?>	
				</form>  	
            </tr>
            <? endforeach;?>
            
            <? endif;?>
            
            <? if (!$this->input->get("ticket_status") || $this->input->get("ticket_status")==1): ?>
            <tr>
				<form id="new_ticket_form" method="post" action="<?=site_url("vip/modify_ticket_json")?>" style="margin:0">
					<input type="hidden" name="vip_event_id" value="<?=$vip_event->id?>">	
					<input type="hidden" name="status" value="1">	
					<input type="hidden" name="action" value="1">	
                    <td>+</td>
                    <td><input type="text" name="uid" class="required" style="width:60px;" value=""></td>
                    <td>
                        <select name="server" class="required" style="width:150px;">
                            <option value="">--請選擇--</option>
                            <? foreach($servers->result() as $row):?>
                            <option value="<?=$row->server_id?>"><?=$row->name?><?=($row->server_status=='public')?"":"(未開放)"?></option>
                            <? endforeach;?>
                        </select>
                    </td>
                    <td><input type="text" name="character_name" class="required" value=""></td>
                    <td></td>
                    <td><input type="text" name="line" class="required" style="width:120px;" value=""></td>
                    <td></td>
                    <td></td>
                    <td><input type="text" name="cost" class="required" style="width:60px;" value=""></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td colspan="2"> 
                    <?if($vip_event->status==2 && strtotime($vip_event->start_date)<=time() && ($vip_event->end_date=='0000-00-00 00:00:00' || strtotime($vip_event->start_date)>=time())):?>
                    <input type="submit" class="btn btn-small btn-primary" value="新建"></td>
                    <?endif;?>
				</form>  		
            </tr>
            <?endif;?>
            
        </tbody>
    </table>
    <? endif;?>
	</div>
    <div class="form-actions">
        <? if ($modify_acl || ($vip_event->status == '1' && $vip_event->admin_uid==$_SESSION['admin_uid'])): ?>
        <a href="javascript:;" url="<?=site_url("vip/move_vip_event/{$vip_event->id}?status=0")?>" class="json_post pull-left btn btn-danger">取消</a>
        <? endif;?>
        <? if ($vip_event->status==1 && $authorize_acl): ?>
        <a href="javascript:;" url="<?=site_url("vip/move_vip_event/{$vip_event->id}?status=2")?>" class="json_post pull-right btn btn-success">核准</a>
        <? endif;?>
    </div>
</div>	