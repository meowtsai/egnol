<div style="padding:10px;width:600px;">
  <label><h4>編輯聯絡內容</h4></label>
  <form id="reply_form" method="post" action="<?=site_url("cpl_case/modify_reply_json")?>">
      <input type="hidden" name="case_id" value="<?=$row->case_id?>">
      <input type="hidden" name="reply_id" value="<?=$row->id?>">
      <input type="hidden" id="back_url" value="<?=site_url("cpl_case/view/$row->case_id")?>">


      <label>聯絡時間</label>
    	<input type="text" name="contact_time" class="" value="<?=$row ? date('Y-m-d H:i', strtotime($row->contact_time)) : ''?>">


      <br />
      <label>歷程紀錄</label>
      <textarea name="note" rows="5" style="width:98%" class="required" ><?=preg_replace('!<br.*>!iU', "", $row->note );?></textarea>


      <div class="form-actions">
      <button type="submit" class="btn ">確認送出</button>
      <a href="javascript:;" class="del btn btn-danger" url="<?=site_url("cpl_case/delete_reply_json/{$row->id}")?>">
        <i class="icon icon-remove"></i>  刪除本篇
      </a>
      </div>
  </form>
</div>
