<?php 
	$sever_status = $this->config->item("server_status");	
?>

<form enctype="multipart/form-data" action="<?=site_url("server/modify")?>" method="post">
	<input type="hidden" name="id" value="<?=$row ? $row->id : ''?>">
	<input type="hidden" name="game_id" value="<?=htmlspecialchars($this->game_id)?>">
		
	<label>伺服器代碼</label>
	<input type="text" value="<?=$row ? $row->server_id : ''?>" name="server_id" class="required" style="width:120px;">
	
	<label>伺服器名稱</label>
	<input type="text" value="<?=$row ? $row->name : ''?>" name="name" class="required">
	
	<label>遊戲串接設定</label>
	<input type="text" value="<?=$row ? $row->address : ''?>" name="address" class="required">
	
	<label>上下架狀態</label>
	<select name="server_status" id="server_status" style="width:120px;">
		<? foreach($sever_status as $key => $val):?>
		<option value="<?=$key?>" <?=$row ? ($row->server_status==$key ? 'selected="selected"' : '') : ''?>><?=$val["name"]?></option>
		<?endforeach;?>
	</select>
	
	<label>維護公告</label>
	<input type="text" value="<?=$row ? $row->maintaining_msg : ''?>" name="maintaining_msg" style="width:300px;">
		
	<label>金流交易許可</label>
	<label class="radio inline"><input type="radio" value="0" name="is_transaction_active" <?=$row ? ($row->is_transaction_active=='0' ? 'checked="checked"' : '') : 'checked="checked"'?>>不可交易</label>
	<label class="radio inline"><input type="radio" value="1" name="is_transaction_active" <?=$row ? ($row->is_transaction_active=='1' ? 'checked="checked"' : '') : ''?>>可交易</label>

	<div class="clearfix" style="margin-bottom:10px;"></div>
	
	<? /*?>
	<label>充值匯率</label>
	<input type="text" value="<?=$row ? $row->exchange_rate : ''?>" name="exchange_rate" class="required number">
	
	<label>伺服器狀態</label>
	<input type="text" value="<?=$row ? $row->server_performance : ''?>" name="server_performance">
	<? */?>
	
	<label>列為最新伺服器</label>
	<label class="radio inline"><input type="radio" value="0" name="is_new_server" <?=$row ? ($row->is_new_server=='0' ? 'checked="checked"' : '') : 'checked="checked"'?>>一般</label>
	<label class="radio inline"><input type="radio" value="1" name="is_new_server" <?=$row ? ($row->is_new_server=='1' ? 'checked="checked"' : '') : ''?>>最新</label>
					
	<h4>伺服器圖示</h4>		
	<label>新服(.png)</label>
	<? if ($row && file_exists(g_conf("http_document_root").$this->game_id."/p/img/server/".$row->server_id."n.png")):?>
	<img src="http://<?=$this->game_id?>.longeplay.com.tw/p/img/server/<?=$row->server_id?>n.png">
	<? endif;?>
	<input type="file" name="file01">
	<label>一般(.png)</label>
	<? if ($row && file_exists(g_conf("http_document_root").$this->game_id."/p/img/server/".$row->server_id."_off.png")):?>
	<img src="http://<?=$this->game_id?>.longeplay.com.tw/p/img/server/<?=$row->server_id?>_off.png">
	<? endif;?>
	<input type="file" name="file02">
	<label>一般(滑鼠移入)(.png)</label>			
	<? if ($row && file_exists(g_conf("http_document_root").$this->game_id."/p/img/server/".$row->server_id."_on.png")):?>
	<img src="http://<?=$this->game_id?>.longeplay.com.tw/p/img/server/<?=$row->server_id?>_on.png">
	<? endif;?>
	<input type="file" name="file03">
					
	<div class="form-actions">
		<input type="submit" value="確認送出" name="button" class="btn"> 
		<input type="reset" value="重設" name="button2" class="btn">
	</div>
</form>
