<?
	$arr = array();
	// $bulletin && $arr = explode(",", strval($bulletin->target));
	$faq && $arr = explode(',',strval($faq->type_ids));
	$faq && $arr_games = explode(',',strval($faq->games));
	$games = $this->db->from("games")->where("is_active", "1")->get();
?>

<form action="<?=site_url('game_faq/modify')?>" method="POST">
  	<input type="hidden" name="faq_id" value="<?=$faq ? $faq->id : ''?>">
  	<input type="hidden" name="game_id" value="<?=$this->game_id?>">
  	<input type="hidden" name="back_url" value="<?=$back_url?>">

<div>
	<label>標題</label>
 	<input type="text" name="faq_title" class="required input-xxlarge" value="<?=$faq ? $faq->title : ''?>" size="80" maxlength="80">

	<label>問題類型</label>
  	<? foreach($question_type as $key => $type):?>
		<label class="checkbox inline">
      <input type="checkbox" name="q_type[]" value="<?=$key?>"
			<?if ($arr && in_array($key, $arr)):?>
				checked
			<?endif;?>
			 > <?=$type?>
    </label>
		<? endforeach;?>
</div>

<hr />


	<div id="choose_target_server" >
		<label>發布遊戲

		</label>
		<div style="margin-bottom:13px;">
			<label class="checkbox"><input type="checkbox" id="clickAll"> 全選</label>
			<div id="servers">
				<? foreach($games->result() as $row):?>

				<label class="checkbox inline"><input type="checkbox" name="target[]" value="<?=$row->game_id?>"

					<?if ($faq &&  in_array($row->game_id, $arr_games)):?>
						checked
					<?endif;?>

					> <?=$row->name?></label>
				<? endforeach;?>
			</div>
		</div>
	</div>


	<label>內文</label>
	<textarea name="faq_content" id="faq_content" ><?=$faq ? $faq->content : ''?></textarea>

	<div class="clearfix" style="margin-bottom:10px;"></div>

	<label>是否發布</label>
	<label class="radio inline"><input type="radio" value="3" name="priority" <?=$faq ? ($faq->priority=='3' ? 'checked="checked"' : '') : ''?>> 首篇</label>
	<label class="radio inline"><input type="radio" value="2" name="priority" <?=$faq ? ($faq->priority=='2' ? 'checked="checked"' : '') : ''?>> 置頂</label>
	<label class="radio inline"><input type="radio" value="1" name="priority" <?=$faq ? ($faq->priority=='1' ? 'checked="checked"' : '') : 'checked="checked"'?>> 發布</label>
	<label class="radio inline"><input type="radio" value="0" name="priority" <?=$faq ? ($faq->priority=='0' ? 'checked="checked"' : '') : ''?>> 不發布</label>

	<div class="clearfix" style="margin-bottom:10px;"></div>

	<label>發布時間</label>
	<input type="text" name="start_time" class="" value="<?=$faq ? date('Y-m-d H:i', strtotime($faq->start_time)) : ''?>">
	<span class="help-inline">(非必填，設定未來時間即可預約發布)</span>

	<label>~ 關閉時間</label>
	<input type="text" name="end_time" class="" value="<?=$faq && $faq->end_time<'2038-01-01' ? date('Y-m-d H:i', strtotime($faq->end_time)) : ''?>">
	<span class="help-inline">(非必填，設定後消息將於指定時間關閉)</span>


	<div class="form-actions">
  		<button type="submit" class="btn ">確認送出</button>
  	</div>
</form>

<script type="text/javascript">

  var simplemde = new SimpleMDE({ element: document.getElementById("faq_content") });

</script>
