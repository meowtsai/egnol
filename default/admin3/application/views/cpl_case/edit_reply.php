<div style="padding:10px;width:600px;">
  <label><h4>編輯聯絡內容</h4></label>
  <form id="reply_form" method="post" action="<?=site_url("cpl_case/modify_reply_json")?>">
      <input type="hidden" name="case_id" value="<?=$row->case_id?>">
      <input type="hidden" name="reply_id" value="<?=$row->id?>">
      <input type="hidden" id="back_url" value="<?=site_url("cpl_case/view/$row->case_id")?>">

      聯絡日期:
      <input type="text" name="contact_date"  id="contact_date"  class="" value="<?=$row ? date('Y-m-d', strtotime($row->contact_date)) : ''?>">
      <?=$row->contact_date?>
      <?=$row ? date('Y-m-d', strtotime($row->contact_date)) : ''?>
      <br />
      玩家訴求
      <textarea name="claim" rows="5" style="width:98%" class="required" ><?=preg_replace('!<br.*>!iU', "", $row->claim );?></textarea>

      我方回覆
      <textarea name="response" rows="5" style="width:98%" class="required" ><?=preg_replace('!<br.*>!iU', "", $row->response );?></textarea>

      相關公函(若有):
      <select name="ref_gov_letter" style="width:150px;" id="ref_gov_letter">
        <option value="">--無相關公函--</option>
        <? foreach($letters->result() as $letter_row):?>
        <option value="<?=$letter_row->id?>" <?=($letter_row && $letter_row->id==$row->ref_gov_letter? 'selected="selected"':""); ?> >#<?=$letter_row->id?> - <?=$letter_row->o_letter_id?>- <?=date('Y-m-d', strtotime($letter_row->o_letter_date))?></option>
        <? endforeach;?>
      </select>


      <div class="form-actions">
      <button type="submit" class="btn ">確認送出</button>
      <a href="javascript:;" class="del btn btn-danger" url="<?=site_url("cpl_case/delete_reply_json/{$row->id}")?>">
        <i class="icon icon-remove"></i>  刪除本篇
      </a>
      </div>
  </form>
</div>
