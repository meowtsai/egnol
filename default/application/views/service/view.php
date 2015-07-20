<style>
<!--
#main table td {padding:4px;}
#main table.talk {width:100%; margin-bottom:6px; }
#main table.officail {background:#c7ffd5;}
-->
</style>
<h3>提問查詢</h3>

<? if ($question):?>

<div style="border-bottom:1px solid #518239; padding:15px;">

<? if ($question->status == '4'):?>
<!-- 已結案 -->
<? endif;?>

	<div style="color:red; margin-bottom:8px;">【提問內容】</div>
	<table cellspacing="0" cellpadding="0">
		<tr>
			<td style="width:80px">提問類型：</td>
			<td>
			<?
				$type = $this->config->item("question_type");
				echo $type[$question->type];
			?>
			</td>
			<td></td>
			<td></td>
		</tr>
		<tr>
			<td>遊戲名稱：</td>
			<td><?=$question->game_name?></td>
			<td>伺服器：</td>
			<td><?=$question->server_name?></td>
		</tr>
		<tr>
			<td>角色名稱：</td>
			<td><?=$question->character_name?></td>
			<td></td>
			<td></td>
		</tr>		
		<tr>
			<td style="vertical-align:top;">提問描述：</td>
			<td colspan="3" style="word-break:break-all"><?=$question->content?></td>
		</tr>		
		<tr>
			<td style="vertical-align:top">截圖：</td>
			<td colspan="3">
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
			</td>
		</tr>				
		<tr>
			<td>聯絡電話：</td>
			<td><?=$question->mobile?></td>
			<td>E-mail：</td>
			<td><?=$question->email?></td>
		</tr>			
		<tr>
			<td></td>
			<td colspan="3">( 聯絡電話及E-mail由系統自動帶入，若要變更請至<a href="<?=site_url("member/update_profile")?>" target="_blank">會員中心</a>。)</td>
		</tr>
	</table>
			
</div>

<div style="border-bottom:1px solid #518239; padding:15px; ">
 	
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
	
</div>

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

<? else:?>
不存在
<? endif;?>