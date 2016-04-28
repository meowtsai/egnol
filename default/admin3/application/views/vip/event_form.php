<style>
<!--
#main table td {padding:4px;}
-->
</style>

<div style="padding:20px;">
	
	<form method="post" action="<?=site_url("vip/modify_event_json")?>" enctype="multipart/form-data">
		<input type="hidden" name="vip_id" value="<?=$vip ? $vip->id : ''?>">
		
	<table cellspacing="0" cellpadding="0" style="width:660px;">
		<tr>
			<td>遊戲：</td>
			<td>
				<select name="game" style="width:150px;">
					<option value="">--請選擇--</option>
					<? foreach($games->result() as $row):?>
					<option value="<?=$row->game_id?>" <?=($vip && $vip->game_id==$row->game_id ? 'selected="selected"' : '')?>><?=$row->name?><?=($row->is_active)?"":" (停)"?></option>
					<? endforeach;?>
				</select>	
			</td>
		</tr>
        <tr>
			<td>時間：</td>
			<td>
                <input type="text" name="start_date" class="date required" value="<?=($this->input->get("start_date"))?$this->input->get("start_date"):date("Y-m-d",strtotime("-8 days"))?>" style="width:120px"> 至
                <input type="text" name="end_date" class="date" value="<?=$this->input->get("end_date")?>" style="width:120px" placeholder="現在">
                <a href="javascript:;" class="clear_date"><i class="icon-remove-circle" title="清除"></i></a>
			</td>
        </tr>
        <tr>
			<td>是否發布：</td>
			<td>
                <label class="radio inline"><input type="radio" value="1" name="is_active" <?=$vip ? ($vip->is_active=='1' ? 'checked="checked"' : '') : 'checked="checked"'?>> 發布</label>
                <label class="radio inline"><input type="radio" value="0" name="is_active" <?=$vip ? ($vip->is_active=='0' ? 'checked="checked"' : '') : ''?>> 不發布</label>
             </td>
        </tr>
		<tr>
			<td>標題：</td>
			<td><input type="text" name="title" class="required" maxlength="60" style="width:300px;" value="<?=$vip ? preg_replace('/<br\\s*?\/??>/i','',$vip->title): ''?>"></td>
		</tr>		
		<tr>
			<td style="vertical-align:top">描述：</td>
			<td><textarea rows="8" minlength="5" style="width:100%;" name="content" class="required"><?=$vip ? preg_replace('/<br\\s*?\/??>/i','',$vip->content): ''?></textarea></td>
		</tr>
        
        <tr>
            <th>附件</th><td style="white-space:pre-wrap;"><input type="file" name="file01" class="pic_input" /></td>
        </tr>
        <tr>
            <th>&nbsp;</th><td style="white-space:pre-wrap;"><input type="file" name="file02" class="pic_input"></td>
        </tr>
        <tr>
            <th>&nbsp;</th><td style="white-space:pre-wrap;"><input type="file" name="file03" class="pic_input"></td>
        </tr>
        <!--tr>
            <th></th>
            <td style="white-space:pre-wrap;">圖檔可接受格式：jpg、png、gif、bmp<br/>最大尺寸 6144x6144 畫素，容量最大 6MB。</td>
        </tr-->
	</table>
	
	<div class="form-actions">
  		<button type="submit" class="btn">確認送出</button>
  	</div>

  	</form>
  		
</div>
