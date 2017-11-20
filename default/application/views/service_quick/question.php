<style>
.pic_input { color:#000; }
</style>
<div id="content-login">
	<div class="login-ins">
		<div class="bread cf" typeof="v:Breadcrumb">
			<a href="<?=$longe_url?>service_quick?site=<?=$site?>" title="客服中心" rel="v:url" property="v:title">客服中心</a> > <a href="<?=$longe_url?>service_quick/question?site=<?=$site?>" title="線上回報" rel="v:url" property="v:title">線上回報</a>
		</div>
		<form id="question_form" enctype="multipart/form-data" method="post" action="<?=$longe_url?>service_quick/question_ajax?site=<?=$site?>">
			<div class="login-form">
				<table class="member_info">
                    <? if (!$partner_uid):?>
					<tr>
						<th>E-mail</th>
						<td><input type="text" name="email" class="email" id="email" size="33"></td>
					</tr>
					<tr>
						 <th>手機號碼</th>
						 <td><input type="text" name="mobile" class="mobile isMobile" id="mobile" size="33"></td>
					<tr>
						 <th></th>
						 <td>E-mail與手機號碼至少需填寫其中一個</td>
					</tr>
                    <? else:?>
                    <input type="hidden" name="partner_uid" id="partner_uid" value="<?=$partner_uid?>">
                    <? endif;?>
                    <? if ($server_name == ""):?>
					<tr>
						<th>遊戲名稱</th>
						<td>
							<select name="game" class="required" style="width:90%;">
								<option value="">--請選擇--</option>
								<?
								foreach($games->result() as $row):
								//if ( IN_OFFICE == false && in_array($row->is_active, array("2", "0"))) continue;
								if ( $site!=$row->game_id) continue;
								?>
								<option value="<?=$row->game_id?>" <?=($site==$row->game_id ? 'selected="selected"' : '')?>><?=$row->name?></option>
								<? endforeach;?>
							</select>
						</td>
					</tr>
					<tr>
						<th>伺服器</th>
						<td>
							<select name="server" class="required" style="width:90%;">
								<option value="">--請先選擇遊戲--</option>
							</select>

							<select id="server_pool" style="display:none;">
								<? foreach($servers->result() as $row):?>
								<option value="<?=$row->server_id?>" <?=($this->input->get("server")==$row->server_id ? 'selected="selected"' : '')?> class="<?=$row->game_id?>"><?=$row->name?></option>
								<? endforeach;?>
							</select>
						</td>
					</tr>
                    <? else:?>
					<tr>
						<th>伺服器</th>
						<td>
                            <input type="hidden" name="server" id="server" value="<?=$server_id?>"><?=$server_name?>
						</td>
					</tr>
                    <? endif;?>
					<tr>
						<th>角色名稱</th>
                        <? if ($character_name == ""):?>
				        <td><input type="text" name="character_name" id="character_name" size="33"></td>
                        <? else:?>
				        <td><input type="hidden" name="character_name" id="character_name" value="<?=$character_name?>"><?=$character_name?></td>
                        <? endif;?>
					</tr>
					<tr>
						<th>問題類型</th>
						<td>
							<select name="question_type" class="required" style="width:90%;">
								<option value="">--請選擇--</option>
								<? foreach($this->config->item("question_type") as $id => $type):?>
								<option value="<?=$id?>"><?=$type?></option>
								<? endforeach;?>
							</select>
						</td>
					<tr>
						<th>問題描述</th><td><textarea name="content" class="required" minlength="5" maxlength="500"></textarea></td>
					</tr>
                    <? if (!$is_ingame):?>
					<tr>
						<th>圖片附件</th><td style="white-space:pre-wrap;"><img src="<?=$longe_url?>p/image/server/server-pic-btn1.png" class="pic_btn"> <input type="file" name="file01" class="pic_input" /></td>
					</tr>
					<tr>
						<th>&nbsp;</th><td style="white-space:pre-wrap;"><img src="<?=$longe_url?>p/image/server/server-pic-btn2.png" class="pic_btn"> <input type="file" name="file02" class="pic_input"></td>
					</tr>
					<tr>
						<th>&nbsp;</th><td style="white-space:pre-wrap;"><img src="<?=$longe_url?>p/image/server/server-pic-btn3.png" class="pic_btn"> <input type="file" name="file03" class="pic_input"></td>
					</tr>
					<tr>
						<th></th>
						<td style="white-space:pre-wrap;">圖檔可接受格式：jpg、png、gif、bmp<br/>最大尺寸 6144x6144 畫素，容量最大 6MB。</td>
                    </tr>
                    <? else:?>
					<tr>
						<td style="white-space:pre-wrap;" colspan="2">
            <div class="notes" style="text-align:center;padding:5px;">提醒勇者：需附檔案回報時，請直接利用官網線上提問，謝謝。</div></td>
                    </tr>
                    <? endif;?>
				</table>
				<div class="login-button">
					<p>
						<input name="doSubmit" type="submit" id="doSubmit" value="" style="display:none;" />
                        <img style="cursor:pointer;" src="<?=$longe_url?>p/image/server/server-back-btn1.png" class="button_submit" onclick="javascript:$('#doSubmit').trigger('click');this.disabled=true;"/>&nbsp;
						<img style="cursor:pointer;" src="<?=$longe_url?>p/image/server/server-back-btn2.png" class="button_submit" onclick="javascript:history.back();this.disabled=true;" />
					</p>
				</div>
			</div>
		</form>
	</div>
</div>
