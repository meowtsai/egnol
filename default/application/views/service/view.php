<div id="content-login">
	<div class="login-ins">
		<div class="bread cf" typeof="v:Breadcrumb">
			<a href="<?=$game_url?>" title="首頁" rel="v:url" property="v:title">首頁</a> > <a href="<?=$longe_url?>service?site=<?=$site?>" title="客服中心" rel="v:url" property="v:title">客服中心</a> > <a href="" title="提問查詢" rel="v:url" property="v:title">提問查詢</a>
		</div>
		<div class="login-form">
<? if ($question):?>
			<table class="member_info">
				<tr>
					<th>案件編號　|</th>
					<td><?=$question->id?></td>
				</tr>
				<tr>
					<th>提問類型　|</th>
					<td>
					<?
						$type = $this->config->item("question_type");
						echo $type[$question->type];
					?>
					</td>
				</tr>
				<tr>
					<th>遊戲名稱　|</th>
					<td><?=$question->game_name?></td>
				</tr>
				<tr>
					<th>伺服器　|</th>
					<td><?=$question->server_name?></td>
				</tr>
				<tr>
					<th>角色名稱　|</th>
					<td><?=$question->character_name?></td>
				</tr>
				<tr>
					<th>E-MAIL　|</th>
					<td><?=$question->email?></td>
				</tr>
				<tr>
					<th>手機號碼　|</th>
					<td><?=$question->mobile?></td>
				</tr>
				<tr>
					<th>提問描述　|</th>
					<td style="overflow:visible; text-overflow:clip; white-space:normal; word-wrap: break-word;"><?=$question->content?></td>
				</tr>
				<tr>
					<th>截圖　|</th>
					<td>
		        	<? if ($question->pic_path1):?>
					<div>
						<a href="<?=$question->pic_path1?>" target="_blank">
							<img src="<?=$question->pic_path1?>" style="max-width:100%;">
						</a>
					</div>
					<? endif;?>
					<? if ($question->pic_path2):?>
					<div>
						<a href="<?=$question->pic_path2?>" target="_blank">
							<img src="<?=$question->pic_path2?>" style="max-width:100%;">
						</a>
					</div>
					<? endif;?>
					<? if ($question->pic_path3):?>
					<div>
						<a href="<?=$question->pic_path3?>" target="_blank">
							<img src="<?=$question->pic_path3?>" style="max-width:100%;">
						</a>
					</div>
					<? endif;?>
					</td>
				</tr>
				<tr>
					<th>處理狀態　|</th>
					<td>
					<?
					if ($replies->num_rows() == 0) echo '目前尚在處理中';
					$no = $replies->num_rows();
					foreach($replies->result() as $row):?>
					<table class="reply <?=($row->is_official ? 'official' : '') ?>" style="position:relative;width:100%">
						<tr>
							<td style="overflow:visible; text-overflow:clip; white-space:normal; word-wrap: break-word;">
								<?//=($row->is_official ? '《客服回覆》' : '《再次提問》') ?>
								<div style="background-color: rgba(255, 255, 255, 0.3);border-radius: 5px; padding:5px; word-wrap: break-word;">
								    <?=$row->content?>
								</div>
								<? if ($row->is_official == '1' && $question->status <> '4' && $no == 1):?>
								<div style="float:right; padding:0 0 20px 20px;">
									<a href="javascript:;" url="<?=site_url("service/close_question/{$question->id}")?>" site="<?=$site?>" class="close_question">[我沒問題了]</a>
									<a href="#go_to_reply">[我還有疑問]</a>
								</div>
								<? endif;?>
								<div style="float:right; font-size: 8px; color: #D8D8D8; font-style: italic;">
								<?=date('Y-m-d H:i', strtotime($row->create_time))?>
								</div>
							</td>
						</tr>
					</table>
					<?
						$no--;
					endforeach;?>
					</td>
				</tr>
				<tr>
					<td colspan="2" style="overflow:visible; text-overflow:clip; white-space:normal; word-wrap: break-word;">
					<? if ($question->status <> '4' && $replies->num_rows() > 0):?>
					<form method="post" action="<?=site_url("service/insert_reply_json")?>">
						<input type="hidden" name="question_id" value="<?=$question->id?>">

					<div style="padding:15px;">
						<a name="go_to_reply"></a>
						再次提問（若與本次提問主題不同，請以另開單方式提問，謝謝）<br>
						<textarea name="content" rows="6" style="width:100%" class="required"></textarea>
					</div>

					<div style="text-align:center; margin-top:20px;">
						<a href="javascript:;" onclick="$('form').submit()"><span style="background-position:-480px 0; height:50px; width:227px; display:inline-block;" class="items">填好了，送出!</span></a>
					</div>
					</form>
					<? endif;?>
					</td>
				</tr>
			</table>
<? else:?>
			<div>問題不存在</div>
<? endif;?>
		</div>
	</div>
</div>