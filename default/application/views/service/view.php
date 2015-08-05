<ul class="le_form">
	<li>提問查詢</li>

<? if ($question):?>

	<li>【提問內容】</li>
	<li>
		<div class="field_name">提問類型
		</div><div class="field_input">
			<?
				$type = $this->config->item("question_type");
				echo $type[$question->type];
			?>
		</div>
	</li>
	<li>
		<div class="field_name">遊戲名稱
		</div><div class="field_input"><?=$question->game_name?></div>
	</li>
	<li>
		<div class="field_name">伺服器
		</div><div class="field_input"><?=$question->server_name?></div>
	</li>
	<li>
		<div class="field_name">角色名稱
		</div><div class="field_input"><?=$question->character_name?></div>
	</li>
	<li>
		<div class="field_name">電子郵件
		</div><div class="field_input"><?=$question->email?></div>
	</li>
	<li>
		<div class="field_name">行動電話
		</div><div class="field_input"><?=$question->mobile?></div>
	</li>
	<li>
		<div class="field_name">提問描述
		</div><div class="field_input"><?=$question->content?></div>
	</li>
	<li>
		<div class="field_name">截圖
		</div><div class="field_input">
        	<? if ($question->pic_path1):?>
				<a href="<?=$question->pic_path1?>" target="_blank">
					<img src="<?=$question->pic_path1?>" style="max-width:400px;">
				</a>
			<? endif;?>
			<? if ($question->pic_path2):?>
				<a href="<?=$question->pic_path2?>" target="_blank">
					<img src="<?=$question->pic_path2?>" style="max-width:400px;">
				</a>
			<? endif;?>
			<? if ($question->pic_path3):?>
				<a href="<?=$question->pic_path3?>" target="_blank">
					<img src="<?=$question->pic_path3?>" style="max-width:400px;">
				</a>
			<? endif;?>
		</div>
	</li>
		<?
		if ($replies->num_rows() == 0) echo '目前尚在處理中';
		$no = $replies->num_rows();
		foreach($replies->result() as $row):?>
		<table class="reply <?=($row->is_official ? 'official' : '') ?>" style="position:relative;">
			<tr>
				<td style="width:120px; border-right:6px solid #fff; text-align:center;">
					NO<?=$no?>.<?=($row->is_official ? '客服回覆' : '再次提問') ?><br>
					<?=date('Y-m-d H:i', strtotime($row->create_time))?>
				</td>
				<td style="word-break:break-all">
					<? if ($row->is_official == '1' && $question->status <> '4' && $no == $replies->num_rows()):?>
					<div style="float:right; padding:0 0 20px 20px;">
						<a href="javascript:;" url="<?=site_url("service/close_question/{$question->id}")?>" class="close_question">我沒問題了</a>
						<a href="#reply">我還有疑問</a>
					</div>
					<? endif;?>
					<?=$row->content?>
				</td>
			</tr>
		</table>
		<?
			$no--;
		endforeach;?>
	<li>
		<? if ($question->status <> '4' && $replies->num_rows() > 0):?>
		<form method="post" action="<?=site_url("service/insert_reply_json")?>">
			<input type="hidden" name="question_id" value="<?=$question->id?>">

		<div style="padding:15px;">
			<a name="reply"></a>
			<div style="background:#c7ffd5; padding:20px;">
				再次提問（若與本次提問主題不同，請另開開單方式提問，謝謝）
				<textarea name="content" rows="6" style="width:100%" class="required"></textarea>
			</div>
		</div>

		<div style="text-align:center; margin-top:20px;">
			<a href="javascript:;" onclick="$('form').submit()"><span style="background-position:-480px 0; height:50px; width:227px; display:inline-block;" class="items"></span></a>
		</div>

		</form>
		<? endif;?>
	</li>

<? else:?>

	<li>問題不存在</li>

<? endif;?>
</ul>

