<style>
body {font-family: "PingFangTC-Light","Microsoft JhengHei","Helvetica Neue","Heiti TC","微軟正黑體",sans-serif;;}
.btn {
	width:8em;
	padding: 10px 16px;
	border-radius: 10px;
	border:1px solid #FA5858;
	color:#ffffff;
	font-size:1rem;
	background-color:#FA5858;
	margin-right:20px;
}

.btn-cancel {
	background-color:#E6E6E6;
	color:#848484;
	border:1px solid #848484;

}

/* The Modal (background) */
.modal {
    display: none; /* Hidden by default */
    position: fixed; /* Stay in place */
    z-index: 1; /* Sit on top */
    padding-top: 100px; /* Location of the box */
    left: 0;
    top: 0;
    width: 100%; /* Full width */
    height: 100%; /* Full height */
    overflow: auto; /* Enable scroll if needed */
    background-color: rgb(0,0,0); /* Fallback color */
    background-color: rgba(0,0,0,0.4); /* Black w/ opacity */
}

.modal-header {
	border-bottom: 1px solid #888;
	padding: 4px;
	font-size:16px;
	color: gray;

}
/* Modal Content */
.modal-content {
    background-color: #fefefe;
    margin: auto;
    padding: 10px;
    border: 1px solid #888;
    width: 400px;
}

/* The Close Button */
.close {
    color: #aaaaaa;
    float: right;
    font-size: 28px;
    font-weight: bold;

}

.close:hover,
.close:focus {
    color: #000;
    text-decoration: none;
    cursor: pointer;
}
.user_info {
	color: #4a4a4a;
	padding: 10px;
	line-height: 200%;


}
.input_serial {
 border: 1px solid #888;
 border-radius: 4px;
 padding: 6px;
 margin: 10px;
 height: 32px;
 width: 200px;
}

.error {
	color: red;
}
</style>

<div id="content-login">
	<div>
		<h3 >
			客服中心 > 第五人格Yahoo購物活動獎勵
		</h3>
    <form id="event_form"  method="post" action="<?=$longe_url?>service_quick/yahoo_ajax?site=<?=$site?>">
			<div style="margin-left: auto;  margin-right: auto;  width: 400px;">
				<table >
					<tr>
						<td colspan="2">
							<div class="user_info">
								<input type="hidden" name="server" id="server" value="<?=$char_data->server_id?>">
                <input type="hidden" name="partner_uid" id="partner_uid" value="<?=$char_data->partner_uid?>">
								<input type="hidden" name="char_id" id="partner_uid" value="<?=$char_data->id?>">
								<input type="hidden" name="character_name" id="character_name" value="<?=$char_data->name?>">

								遊戲： <b>第五人格</b> <br />
								角色名稱： <b><?=$char_data->name?> </b><br />
								角色 ID： <b><?=$char_data->in_game_id?> </b><br />
								活動名稱： <b><?=$event->event_name; ?> </b><br />
								序號：
								<? if (!$event_status):?>

								 <input type="text" name="serial_no" id="serial_no" size="20" minlength="15" maxlength="30" placeholder="輸入序號" class="input_serial required" >
								<?else:?>
								<b><?=$event_status; ?></b>

								<?endif?>
								<hr />
								<p>
									<? if (!$event_status):?>

									<button type="button" name="cmdCancel" onclick="javascript:history.back();this.disabled=true;" class="btn btn-cancel" >取消</button>
									<button type="submit" name="cmdSubmit"  class="btn pull-right">送出</button>
									<?else:?>
									<div class="" style="background-color:#FA5858;color:#ffffff;font-weight:bold;border-radius:3px;text-align:center;padding:5px;">
											【已經完成兌換，<br/>獎項將於 2019/01/15 晚上 00：00 前發送至指定角色ID】
									</div>
									<?endif?>
								</p>
							</div>
						</td>
					</tr>
					<tr>
						<td colspan="2">
								<fieldset style="width:400px;border-radius: 5px;white-space:normal;border:#848484;">
									<legend>注意事項</legend>
									<ul style="text-align: left;list-style-type:square;line-height:150%;color: #2E2E2E;font-size:smaller;">
										<li>本序號僅提供《第五人格》亞洲服玩家兌換。</li>
										<li>序號可兌換期限至 2018/12/31 晚上 00：00 止。</li>
										<li>每個遊戲帳號可兌換一次。</li>
										<li>獎項將於 2019/01/15 晚上 00：00 前，以遊戲內郵件發送至所填寫的角色ID。</li>
									</ul>
								</fieldset>



						</td>
					</tr>

          </table>

        </div>
      </form>
    </div>
  </div>


	<!-- The Modal -->
	<div id="myModal" class="modal">

	  <!-- Modal content -->
	  <div class="modal-content">
			<span class="close">&times;</span>
			<div class="modal-header">
				 兌換結果
			</div>
	    <p></p>
	  </div>

	</div>
