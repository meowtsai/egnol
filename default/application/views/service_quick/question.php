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
						 <td>E-mail和手機欄位必填</td>
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
				        <td><input type="text" name="character_name" id="character_name" size="33" placeholder="(選填)"></td>
                        <? else:?>
				        <td><input type="hidden" name="character_name" id="character_name" value="<?=$character_name?>"><?=$character_name?></td>
                        <? endif;?>
					</tr>
					<tr>
						<th>問題類型</th>
						<td>
							<select name="question_type" class="required" style="width:90%;">
								<option value="">--請選擇--</option>
								<? if ($evt_code):?>
									<option value="e" selected><?=$this->config->item("question_type")["e"];?></option>
								<?else:?>
									<? foreach($this->config->item("question_type") as $id => $type):?>
										<option value="<?=$id?>"><?=$type?></option>

									<? endforeach;?>
									<?if (IN_OFFICE && $site=='h55naxx2tw' && $partner_uid):?>
										<option value="Yahoo">第五人格 Yahoo 購物活動獎勵</option>
									<? endif;?>
								<? endif;?>
							</select>
						</td>
					<tr>
						<th>問題描述</th><td><textarea name="content" class="required" minlength="5" maxlength="500"></textarea></td>
					</tr>
					<tr>

						<td colspan="2">


							<div id="div_hint" style="white-space: pre-wrap;padding: 10px;margin-left: auto;display: none;border:1px solid silver;border-radius: 0.2rem;width:350px;color: #757c81;font-size: 16px;line-height:150%;" >
							</div>
						</td>
					</tr>
					<?
					//echo $_SERVER['HTTP_USER_AGENT'];
					$ua = strtolower($_SERVER['HTTP_USER_AGENT']);
					//$is_ingame
					//if (!$is_ingame ):
					?>
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
						<th>&nbsp;</th><td style="white-space:pre-wrap;"><img src="<?=$longe_url?>p/image/server/server-pic-btn4.png" class="pic_btn"> <input type="file" name="file04" class="pic_input" /></td>
					</tr>
					<tr>
						<th>&nbsp;</th><td style="white-space:pre-wrap;"><img src="<?=$longe_url?>p/image/server/server-pic-btn5.png" class="pic_btn"> <input type="file" name="file05" class="pic_input"></td>
					</tr>
					<tr>
						<th>&nbsp;</th><td style="white-space:pre-wrap;"><img src="<?=$longe_url?>p/image/server/server-pic-btn6.png" class="pic_btn"> <input type="file" name="file06" class="pic_input"></td>
					</tr>
					<tr>
						<th></th>
						<td style="white-space:pre-wrap;">圖檔可接受格式：jpg、png、gif、bmp<br/>最大尺寸 6144x6144 畫素，容量最大 6MB。</td>
          </tr>

					<tr>
						<td style="white-space:pre-wrap;" colspan="2">
            <div class="notes" style="text-align:center;padding:5px;">提醒您：若無法選取檔案回報，請直接利用官網線上提問，謝謝。</div></td>
          </tr>

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

<?
$user_ip = $_SERVER['REMOTE_ADDR'];
//if ($user_ip=="61.220.44.200"):
	?>
<script type="text/javascript">

var faq_list =[]
$.ajax({
type: "GET",
url:  window.location.origin + "/game_faq/get_faq_list/<?=$site?>",
data: '',
dataType: 'JSON',
success: function (data) {
  //console.log(result);
  faq_list = data.reduce(function(result, current) {
    result[current.type_id] = result[current.type_id] || [];
    result[current.type_id].push(current);
		return result;
	}, {});

} });

	$( "select[name='question_type']" ).change(function(){
		var hint_text = "";
		$("#div_hint").hide();
		var sel = $( "select[name='question_type']" ).val();

		if (sel==='Yahoo'){
			$( "select[name='question_type']" ).val('');
			location.href = '/service_quick/yahoo_event';

			return;
		}
		var mydata= faq_list[sel];
		if (mydata===undefined)
		{
			return;
		}

			if (mydata.length>0)
			{
				for (var i = 0; i < mydata.length; i++) {

					hint_text += "<b>" + mydata[i].title + (mydata[i].priority==0 ? "<font color='red'>(內網)</font>" : "") + " </b><br />";
					hint_text +=  mydata[i].content + "<br />";
				}
			}

			if (hint_text!=="")
			{
				$("#div_hint").html( hint_text);
				$("#div_hint").show();
			}
			else {
				$("#div_hint").hide();
			}






	});
</script>
<?//endif;?>
