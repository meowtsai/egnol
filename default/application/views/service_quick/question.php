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
								<? endif;?>
							</select>
						</td>
					<tr>
						<th>問題描述</th><td><textarea name="content" class="required" minlength="5" maxlength="500"></textarea></td>
					</tr>
					<tr>

						<td colspan="2">


							<div id="div_hint" style="padding: 10px;margin-left: auto;display: none;border:1px solid silver;border-radius: 0.2rem;width:350px;color: #757c81;font-size: 16px;line-height:150%;" >
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
var faq_list =[
	{	"type":"1",
		"qlist":[
			{"q":"※陸服有辦法移轉到台服嗎?","a":"目前台版是全新的伺服器，所以無法移民喔\
	<br />提醒您：\
	<br />※雙版本系統(ios與安卓)是無法共用帳號，<br />但是，伺服器是可以一起遊玩喔！\
	<br />※台灣版本與海外版本是無法共用帳號，<br />但是，伺服器是可以一起遊玩喔！<br />"},

		{"q":"※帳號遺失/換了手機/忘了綁定？","a":"若您是要反映『帳號遺失』問題，\
				<br />還請您提供下列資訊：\
				<br /> ☑伺服器(亞洲服/歐美服)：\
				<br /> ☑帳號創建時間(年/月/日)：\
				<br /> ☑帳號等級：\
				<br /> ☑帳號暱稱：\
				<br /> ☑帳號最後登入裝置版本型號：\
				<br /> ☑最後登入時間：<br />"},
		{"q":"※如何刪除帳號？","a":"目前並未提供『刪除帳號』的服務喔。<br />"},
		{"q":"※性別填錯了怎麼辦？","a":"目前並未提供『變更性別』的服務喔。<br />"},
		{"q":"※如何綁定帳號？","a":"登入畫面點選右上方帳號進入用戶中心，<br />選擇使用Google Play、Facebook帳號來進行綁定。<br />"},

		]
	},

	{	"type":"2",
	"qlist":[
	{"q":"※扣款成功卻沒有拿到商品？","a":"請您重新啟動遊戲再次確認。<br />"},
	{"q":"※已經重新啟動還是沒拿到商品？","a":"還請您提供下列資訊：\
	<br /> ☑帳號ID：(於用戶中心的數字)\
	<br /> ☑角色ID：(頭像旁的數字ID)	\
	<br /> ☑交易時間： \
	<br /> ☑訂單編號：	\
	<br /> ☑購買品項名稱：	\
	<br /> ☑未收到的商品：	\
	<br /> ☑交易收據截圖：<br />"},
	{"q":"※有其他（例如超商或點卡）付款方式嗎？","a":"目前《第五人格》僅提供Google與Apple雙平台<br />儲值等方式，\
而未來若有增加其他儲值方式<br />都會公告於粉絲團或官網公告。<br />"},
{"q":"※無法儲值？","a":"請詳細說明在哪個步驟出現什麼錯誤訊息，<br />能附上擷圖會加快我們處理問題。<br />"},
	]
},
{"type":"3",
"qlist":[
	{"q":"※遊戲內沒有聲音?","a":"可先使用「修復客戶端」<br />以及確認遊戲內「聲音設置」是否都有開啟。\
	<br />提醒您，裝置若於靜音下，遊戲中也不會有聲音唷。<br />"},
	{"q":"※進入匹配都不是自身選擇的陣營?","a":"若選擇求生者進行遊戲而求生者陣營人數過多時，\
	<br />遊戲將會詢問『<b>是否要轉監管者</b>』，<br />這時候要點『<b>否</b>』才會是求生者陣營唷。<br />"},
{"q":"※為什麼會被禁言？","a":"遊戲內若有不當言論時玩家可透過檢舉機制反饋，\
<br />當經查核屬實時，系統將會給予禁言的懲處，\
<br />請查看郵件即可得知被禁言的時間。<br />"},
]



},
{"type":"4",
"qlist":[
	{"q":"※遊戲操作有異常?","a":"請您協助說明異常時間、\
	<br />異常地圖與人物以及詳細的操作流程，<br />讓相關人員能更快復現您的情形唷。<br />"},]
},


{	"type":"8",
"qlist":[
{"q":"※遊戲很卡很lag？","a":"嘗試互換您的網路連線選擇較佳的環境遊玩，<br />如Wifi / 4G 互相切換。<br />"},
{"q":"※換了網路環境還是很卡？","a":"請您來信提供下列相關連線環境資訊：\
<br /> ☑(1)帳號ID(於用戶中心的數字) : \
<br /> ☑(2)電信供應商／連線方式 :\
<br /> ☑(3)戰鬥異常時間:\
<br /> ☑(4)ping數值:（可查看遊戲左上）\
<br /> ☑(5)伺服器:歐美服/亞洲服\
<br /> ☑(6)異常情形說明:(如爆Ping.LAG)\
<br /> ☑(7)使用裝置;(如iPhone X)<br />"},
{"q":"※為什麼會一直退出遊戲?","a":"若手機記憶體緩存空間不足則會發生此情形，\
<br />需將手機關機重開並清除已開啟的APP應用程式，\
<br />倘若仍有問題，請您協助提供以下資訊：\
<br />☑裝置型號：\
<br />☑作業系統版本：\
<br />☑網路電信業者 / 連線方式(3G/4G/WiFi)：\
<br />☑遊戲版本：<br />"},]
}
];






	$( "select[name='question_type']" ).change(function(){
		var hint_text = "";
		var sel = $( "select[name='question_type']" ).val();
		var game_id = "<?=$site?>";
		if (game_id==="h55naxx2tw") //h55naxx2tw
		{

			var mydata = faq_list.filter(function(item){
				if (item.type===sel){ return item};
			})
			if (mydata.length>0)
			{
				for (var i = 0; i < mydata[0].qlist.length; i++) {
					hint_text += "<b>" + mydata[0].qlist[i].q + "</b><br />";
					hint_text +=  mydata[0].qlist[i].a + "<br />";
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

		}




	});
</script>
<?//endif;?>
