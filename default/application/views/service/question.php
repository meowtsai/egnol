<form id="register_form" method="post" action="/member/register_json">
	<ul class="le_form">
		<li>線上回報</li>
		<li>
			<div class="field_name">遊戲
			</div><div class="field_input">
				<select name="game" class="required" style="width:85%;">
					<option value="">--請選擇--</option>
					<? foreach($games->result() as $row):?>
					<option value="<?=$row->game_id?>" <?=($this->input->get("game")==$row->game_id ? 'selected="selected"' : '')?>><?=$row->name?></option>
					<? endforeach;?>
				</select>
			</div>
		</li>
		<li>
			<div class="field_name">伺服器
			</div><div class="field_input">
				<select name="server" class="required" style="width:85%;">
					<option value="">--請先選擇遊戲--</option>
				</select>

				<select id="server_pool" style="display:none;">
					<? foreach($servers->result() as $row):?>
					<option value="<?=$row->id?>" <?=($this->input->get("server")==$row->id ? 'selected="selected"' : '')?> class="<?=$row->game_id?>"><?=$row->name?></option>
					<? endforeach;?>
				</select>
			</div>
		</li>
		<li>
			<div class="field_name">角色名稱
			</div><div class="field_input">
				<select name="character_name" class="required" style="width:85%;">
					<option value="">--請選擇角色--</option>
				</select>

				<select id="character_pool" style="display:none;">
					<? foreach($characters->result() as $row): ?>
					<option value="<?=$row->id?>" class="<?=$row->server_id?>"><?=$row->character_name?></option>
					<? endforeach;?>
				</select>
			</div>
		</li>
		<li>
			<div class="field_name">問題類型
			</div><div class="field_input">
				<select name="question_type" class="required" style="width:85%;">
					<option value="">--請選擇--</option>
					<? foreach($this->config->item("question_type") as $id => $type):?>
					<option value="<?=$id?>"><?=$type?></option>
					<? endforeach;?>
				</select>
			</div>
		</li>
		<li>
			<div class="field_name">問題描述
			</div><div class="field_input">
				<textarea rows="3" minlength="5" maxlength="500" style="width:85%;" name="content" class="required"></textarea>
			</div>
		</li>
		<li>
			<div class="field_name">圖片附件1
			</div><div class="field_input"><input type="file" name="file01" />
			</div><div class="field_name">圖片附件2
			</div><div class="field_input"><input type="file" name="file02" />
			</div><div class="field_name">圖片附件3
			</div><div class="field_input"><input type="file" name="file03" />
			</div>
		</li>
		<li>
			<input tabindex="3" name="send_question" type="submit" id="send_question" value="提交" />&nbsp;
			<input name="cancel" type="button" value="取消" onclick="javascript:history.back();" />
		</li>
	</ul>
</form>
