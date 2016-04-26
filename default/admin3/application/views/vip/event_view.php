<style>
<!--
#vip {}
#vip table.officail {background:#e7efe5;}
#content {width:600px;}
-->
</style>

<legend>活動編號 #<?=$vip->id?></legend>	

<div id="func_bar">
  <? if ($vip->status <> '4'):?>
  
	  <form id="allocate_form" method="post" action="<?=site_url("service/allocate_json")?>" style="margin:0; display:inline-block">
		<input type="hidden" name="vip_id" value="<?=$vip->id?>">	
		<input type="hidden" name="allocate_result" value="<?=$vip->allocate_result?>">
		
		<div style="line-height:32px;">
			後送給 
			<select name="allocate_admin_uid" style="width:120px; margin-bottom:0;">
				<? foreach($allocate_users->result() as $row):?>
				<option value="<?=$row->uid?>"><?=$row->name?></option>
				<? endforeach;?>
			</select>
			<br>
			<input type="text" name="result" style="width:200px; margin:0;" placeholder="後送描述">
			<br>
			<div style="text-align:right;"><input type="submit" class="btn" value="送出"/></div>
		</div>
	  </form>
  
	<? if ($vip->type == '9'):?>	
	 | <a href="<?=site_url("service/edit/{$vip->id}")?>" class="btn btn-primary">編輯</a>
	<? endif;?>
  <? endif;?>	
</div>


<div id="vip">
	
	<div id="content">
	
	<table class="table table-bordered">
		<tr>
			<th style="width:80px;">案件狀態：</th>
			<td colspan="3">
			<?
				$status = $this->config->item("vip_status");
				echo $status[$vip->status];
			?>
			
				<?  if ($vip->status == '2' || $vip->status == '4'):?>
					<?= $vip->is_read ? '<span style="color:#090">(玩家已讀)</span>' : '<span style="color:#999">(玩家未讀)</span>'; ?>
				<? endif;?>			
				
	
	<? if ($vip->allocate_status == 1):?>
	<span style="color:#999">(已後送給 <?=$vip->allocate_user_name?>)</span>
	<? elseif ($vip->allocate_status == 2):?>
	<span style="color:#009">(<?=$vip->allocate_user_name?> 處理完畢)</span>
	<? endif;?>
	
	<? if ($vip->allocate_result):?>
		<div style="color:#666; font-size:13px; margin:6px 0;"><?=$vip->allocate_result?></div>
	<? endif;?>
	
	<? if ($vip->allocate_status == 1):?>
	<div style="margin:8px 0 0;">	
	  <form id="result_form" method="post" action="<?=site_url("service/finish_allocate_json")?>" style="margin:0;">
		<input type="hidden" name="vip_id" value="<?=$vip->id?>">
		<input type="hidden" name="allocate_result" value="<?=$vip->allocate_result?>">
		
		<input type="text" name="result" style="width:300px; margin:0;" placeholder="處理描述">	
		<input type="submit" class="btn" value="處理完成"/>
	  </form>
	</div>	
	<? endif;?>	
	
			</td>
		</tr>
		<tr>
			<th>提問類型：</th>
			<td colspan="3">
                <form id="type_form" method="post" action="<?=site_url("service/edit_type_json")?>" style="margin:0;">
                    <select name="type" class="required" style="width:150px;">
                        <option value="">--請選擇--</option>
                        <? foreach($this->config->item("vip_type") as $id => $type):?>
                        <option value="<?=$id?>" <?=($vip && $vip->type==$id ? 'selected="selected"' : '')?>><?=$type?></option>
                        <? endforeach;?>
                    </select>
                </form>
			</td>
		</tr>
		<tr>
			<th>遊戲名稱：</th>
			<td><?=$vip->game_name?></td>
			<th style="width:80px;">伺服器：</th>
			<td><?=$vip->server_name?></td>
		</tr>
		<? if ($vip->type <> '9'):?>
		<tr>
			<th>uid：</th>
			<td colspan="3">
				<a href="<?=site_url("member/view/{$vip->uid}")?>" target="_blank"><?=$vip->uid?></a>
				<a href="<?=site_url("service/get_list?uid={$vip->uid}&action=查詢")?>"><i class="icon-search"></i></a>
			</td>
		</tr>			
		<tr>
			<th>帳號：</th>
			<td colspan="3">
				<?
	            if (!$vip->email && !$vip->mobile) {
		            $ex_id = explode("@",$vip->external_id); 
		            if ('device' == $ex_id[1]) echo "快速登入";
		            else echo $ex_id[1];
	            } else {
		            if ($vip->email) echo $vip->email;
		            echo $vip->mobile;
	            }
				?>
			</td>
		</tr>	
		<? endif;?>	
		<tr>
			<th>角色名稱：</th>
			<td colspan="3"><?=$vip->character_name?></td>
		</tr>		
		<tr>
			<th>提問日期：</th>
			<td colspan="3">
			<?=$vip->create_time?>
			</td>
		</tr>			
		<tr>
			<th style="vertical-align:top">提問描述：</th>
			<td colspan="3" style="word-break: break-all"><?=$vip->content?></td>
		</tr>		
		<? if ($vip->type <> '9'):?>		
		<tr>
			<th>截圖：</th>
			<td colspan="3">
				<? if ($vip->pic_path1):?>
				<a href="<?=$vip->pic_path1?>" target="_blank">
					<img src="<?=$vip->pic_path1?>" style="max-width:400px;">
				</a>
				<? endif;?>
				<? if ($vip->pic_path2):?>
				<a href="<?=$vip->pic_path2?>" target="_blank">
					<img src="<?=$vip->pic_path2?>" style="max-width:400px;">
				</a>
				<? endif;?>
				<? if ($vip->pic_path3):?>
				<a href="<?=$vip->pic_path3?>" target="_blank">
					<img src="<?=$vip->pic_path3?>" style="max-width:400px;">
				</a>
				<? endif;?>								
			</td>
		</tr>				
		<tr>
			<th>聯絡電話：</th>
			<td><?=$vip->mobile?></td>
			<th>E-mail：</th>
			<td><?=$vip->email?></td>
		</tr>	
		<? else:?>
		<tr>
			<th>聯絡電話：</th>
			<td><?=$vip->phone?></td>
			<th>E-mail：</th>
			<td><?=$vip->email?></td>
		</tr>	
		<? endif;?>
		<tr>
			<th>備註：</th>
			<td colspan="3">
				<form id="note_form" method="post" action="<?=site_url("service/update_note_json")?>" style="margin:0">
					<input type="hidden" name="vip_id" value="<?=$vip->id?>">	
					<textarea name="note" rows="3" style="width:80%" ><?=$vip->note?></textarea>
					<button type="submit" class="btn" style="vertical-align:top;">儲存</button>
				</form>  			
			</td>
		</tr>
	</table>
	
	<? 
	$no = 1;
	foreach($replies->result() as $row):?>
	<table class="table table-bordered <?=($row->is_official ? 'official' : '') ?>" style="position:relative;">
		<tr>
			<td style="width:120px; text-align:center;">
				NO<?=$no++?>.<?=($row->is_official ? '客服回覆' : '再次提問') ?><br>
				<?=date('Y-m-d H:i', strtotime($row->create_time))?>
				<? if ($row->admin_uname):?>
				<div style="font-size:12px; color:#129;">(<?=$row->admin_uname?>)</div>
				<? endif;?>
			</td>
			<td style="word-break:break-all"><?=$row->content?>
				<? if ($row->is_official): ?>
				<div><a href="<?=site_url("service/edit_reply/{$row->id}")?>">編輯</a></div>
				<? endif;?>
			</td>
		</tr>
	</table>
	<? endforeach;?>
		
	
	</div>		
		
	<? if ($vip->status <> '4'):?>

	<form id="reply_form" method="post" action="<?=site_url("service/modify_reply_json")?>">
		<input type="hidden" name="vip_id" value="<?=$vip->id?>">	
		回覆
		<textarea name="content" rows="28" style="width:98%" class="required">親愛的玩家您好，
感謝您使用龍邑客服中心線上回報系統


倘若有任何疑問或是需要進一步瞭解的事項，請直接透過客服中心線上回報，我們將會竭誠為您服務。
***龍邑客服中心敬上***</textarea>
		
		<div class="form-actions">
	  		<button type="submit" class="btn ">確認送出</button>
	  		<a href="javascript:;" url="<?=site_url("service/close_vip/{$vip->id}")?>" class="json_post pull-right btn btn-danger">結案</a>
	  	</div>
	</form> 
    <? else: ?> 

	<form id="reply_form" method="post" action="<?=site_url("service/modify_reply_json")?>">
		<div class="form-actions">
	  		<a href="javascript:;" url="<?=site_url("service/show_vip/{$vip->id}")?>" class="json_post pull-right btn btn-danger">調回處理中</a>
	  	</div>
	</form>
	<? endif;?>
	
</div>	