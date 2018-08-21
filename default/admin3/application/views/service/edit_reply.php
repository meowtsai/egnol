
	<form id="reply_form" method="post" action="<?=site_url("service/modify_reply_json")?>">
		<input type="hidden" name="question_id" value="<?=$row->question_id?>">
		<input type="hidden" name="reply_id" value="<?=$row->id?>">
		<input type="hidden" id="back_url" value="<?=site_url("service/view/$row->question_id")?>">
		回覆
		<textarea name="content" rows="6" style="width:96%" class="required"><?=preg_replace('!<br.*>!iU', "", $row->content );?></textarea>

		<div class="form-actions">
	  		<button type="submit" class="btn ">確認送出</button>

				<? if ($_SESSION['admin_uid']=="112"):?>
				<a href="javascript:;" class="del btn btn-danger" url="<?=site_url("service/delete_reply_json/{$row->id}")?>">
					<i class="icon icon-remove"></i>  刪除本篇
				</a>
				<?endif;?>

	  	</div>
	</form>
