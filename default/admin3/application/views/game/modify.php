<?
	$tags = $row ? explode(",", $row->tags) : array();

	if (isset($msg)) output_result($msg);
?>
<form method="post" class="validation" enctype="multipart/form-data" action="<?=site_url("game/modify")?>">
	<fieldset>
	    <?if(isset($row->game_id)):?>
		<input type="hidden" name="id" value="<?=$row ? $row->game_id : ''?>">
		<?else:?>
		<label>遊戲代號</label>
		<input type="text" name="game_id" value="" class="required" style="width:50px">
		<?endif;?>

		<label>遊戲名稱</label>
		<input type="text" name="name" value="<?=$row ? $row->name : ''?>" class="required" style="width:200px">

		<select name="is_active" style="width:120px;">
			<option value="1" <?=$row ? ($row->is_active=='1' ? 'selected' : '') : ''?>>o開啟遊戲</option>
			<option value="0" <?=$row ? ($row->is_active=='0' ? 'selected' : '') : ''?>>x關閉遊戲</option>
			<option value="2" <?=$row ? ($row->is_active=='2' ? 'selected' : '') : ''?>>2內部測試</option>
		</select>

		<label>遊戲簡稱</label>
		<input type="text" name="abbr" value="<?=$row ? $row->abbr : ''?>" class="required" style="width:150px">


		<div style="margin-bottom:10px;"></div>


		<label>套用主題(遊戲內FAQ用)</label>
		<select name="game_theme">
			<? foreach($theme_list->result() as $theme_row):?>
			<option value="<?=$theme_row->id?>" <?=$row ? ($row->theme_id == $theme_row->id ? 'selected="selected"' : '') : ''?>><?=$theme_row->title?></option>
			<? endforeach;?>
		</select>

		<div style="margin-bottom:10px;"></div>


		<label><font color='blue'><b>官網首頁遊設定</b></font></label>
		<div style="margin-bottom:10px;"></div>

		<label><font color='blue'>遊戲設定</font></label>
		<label class="checkbox inline"><input name="tags[]" type="checkbox" value="首頁輪播" <?=in_array("首頁輪播", $tags) ? "checked='checked'" : ""?>> 首頁輪播</label>


		<label><font color='blue'>粉絲團URL</font> <font size="small" color="gray">* 範例: https://www.facebook.com/playvxz/</font></label>
		<input type="text" name="fanpage" value="<?=$row ? $row->fanpage : ''?>"  style="width:300px">


		<label><font color='blue'>官網</font> <font size="small" color="gray">* 範例: https://vxz.longeplay.com.tw/</font></label>
		<input type="text" name="site" value="<?=$row ? $row->site : ''?>"  style="width:300px">

		<label><font color='blue'>行銷文字</font> <font size="small" color="gray">* 範例: 心驚膽戰 追逐逃生</font></label>
		<input type="text" name="slogan" value="<?=$row ? $row->slogan : ''?>"  style="width:450px">


		<label><font color='blue'>logo小圖(.gif, jpg, jpeg, png)</font><font size="small" color="gray"> * 設定後才會出現在首頁下拉選單</font>
			<span style="width:45px; height:45px; line-height:45px; background:#ddd; text-align:center; display:block; overflow:hidden;">
				<img src="<?=$row ? $row->logo_path : ''?>">
			</span>
			<input type="file" name="file04">
			<input type="hidden" name="logo_path" class="" value="<?=$row ? $row->logo_path : ''?>">
		</label>

		<label><font color='blue'>遊戲標題圖檔(.gif, jpg, jpeg, png)</font><font size="small" color="gray"> </font>
			<span style="width:45px; height:45px; line-height:45px; background:#ddd; text-align:center; display:block; overflow:hidden;">
				<img src="<?=$row ? $row->title_path : ''?>">
			</span>
			<input type="file" name="file02">
			<input type="hidden" name="title_path" class="" value="<?=$row ? $row->title_path : ''?>">
		</label>



		<label><font color='blue'>輪播大圖(.gif, jpg, jpeg, png)</font><font size="small" color="gray"> * 設定後才會出現在輪播</font>
			<span style="width:1920px; height:947px; line-height:947px; background:#ddd; text-align:center; display:block; overflow:hidden;">
				<img src="<?=$row ? $row->bg_path : ''?>">
			</span>
			<input type="file" name="file01">
			<input type="hidden" name="bg_path" class="" value="<?=$row ? $row->bg_path : ''?>">
		</label>








   		<div class="form-actions">
   			<button type="submit" class="btn"><i class="icon-ok"></i> 確認送出</button>
   		</div>

	</fieldset>
</form>
