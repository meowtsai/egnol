<form method="post" class="form-search" enctype="multipart/form-data" >
	
	<? if ($error_message):?>
	<div class="control-group">
		<p class="text-error"><?=$error_message?></p>
	</div>	
	<? endif;?>
	
	<div class="control-group">
		
		選擇伺服器
		<select name="server_id">
			<option value="">--</option>
			<? foreach($server->result() as $row):?>
			<option value="<?=$row->id?>" <?=$this->input->post("server_id")==$row->id ? 'selected="selected"' : '' ?>><?=$row->server_name?></option>
			<? endforeach;?>
		</select>
		
		，選擇csv檔案
		<input type="file" name="userfile">
	  	
	</div>
	
	<div class="control-group">
		<h5><span class="label label-info">說明</span> csv範例檔案</h5>
		<a href="<?=site_url("p/data/character/import_demo.xls")?>"><i class="icon-download-alt"></i> 點此下載</a>
	</div>
	
	<div class="form-actions">
  		<button class="btn " type="submit">確認送出</button>
  	</div>
		
</form>
