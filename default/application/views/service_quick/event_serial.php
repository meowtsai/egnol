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
			兌獎中心 > <?=$event->event_name?>
		</h3>
    <form id="event_form"  method="post" action="<?=$longe_url?>service_quick/event_serial_ajax?site=<?=$site?>">
			<div style="margin-left: auto;  margin-right: auto;  width: 400px;">
				<table >
					<tr>
						<td colspan="2">
							<div class="user_info">

								遊戲： <b><?=$_SESSION['game_name']?></b> <br />
								活動名稱： <b><?=$event->event_name; ?> </b><br />
								<input type="hidden" name="event_id" id="event_id" value="<?=$event->id?>">
								<input type="hidden" name="is_ingame" id="is_ingame" value="<?=$is_ingame?>">

								<input type="hidden" name="server" id="server" value="<?=$char_data->server_id?>">
                <input type="hidden" name="partner_uid" id="partner_uid" value="<?=$char_data->partner_uid?>">
								<input type="hidden" name="char_id" id="char_id" value="<?=$char_data->id?>">
								<input type="hidden" name="character_name" id="character_name" value="<?=$char_data->name?>">
								角色名稱： <b><?=$char_data->name?> </b><br />
								角色 ID： <b><?=$char_data->in_game_id?> </b><br />



								序號：<input type="text" name="serial_no" id="serial_no" size="20" minlength="10" maxlength="30" placeholder="輸入序號" class="input_serial required" autocomplete="off" >

								<? if ($records):?>
								<div style="width:420px;border:solid 1px black;border-radius: 5px;">


								<fieldset style="width:400px;white-space:normal;border:#848484;">
									<legend><font color='blue'>★以下是已成功兌換品項<br/>(預定 <b>2019/3/15 晚上 23：59 前發送</b>。)</font></legend>
									<ul style="text-align: left;list-style-type:decimal;line-height:150%;color: #2E2E2E;font-size:smaller;background-color:#F5A9A9;border-radius:5px;">
										<? foreach ($records as $record ): ?>
										<li><?=$record->title?>(<b>序號: <?=$record->serial?></b>)</li>
										<? endforeach; ?>
									</ul>
								</fieldset>
								</div>
								<?endif?>
								<hr />
								<p>
									<button type="button" name="cmdCancel" onclick="javascript:history.back();this.disabled=true;" class="btn btn-cancel" >取消</button>
									<button type="submit" name="cmdSubmit"  class="btn pull-right">送出</button>

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
										<li>取得序號者，可至《第五人格》遊戲內的偵探大廳→點選介面右上角「齒輪」→問題回饋→線上回報→問題類型：第五人格TGS虛寶兌換→填寫表單內容→送出。</li>
										<li>序號可兌換期限至 2019/2/25 晚上 23：59 止。</li>
										<li><b><u>同一獎項類別，每個遊戲帳號只能兌換一次。</u></b></li>
										<li>錯誤達十次將鎖定兌獎功能。</li>
										<li>獎項將於 <b>2019/3/15 晚上 23：59 </b>前，以遊戲內郵件發送獎勵至所填寫的角色ID。</li>
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

	<script type="text/javascript">
	$(function()
	{
	  var modal = document.getElementById('myModal');
	  var span = document.getElementsByClassName("close")[0];
	  var content = document.getElementsByClassName("modal-content")[0].childNodes[5];
	  span.onclick = function() {
	      modal.style.display = "none";
	  }

	  window.onclick = function(event) {
	      if (event.target == modal) {
	          modal.style.display = "none";
	      }
	  };

		$("#event_form").validate({
			onfocusout: false,
			onkeyup: false,
			onclick: false,
			messages: {
				serial_no: {
					required: "* 必填",
					minlength: "* 序號錯誤",
					maxlength: "* 序號錯誤"
				},
			},
			submitHandler: function(form)
			{
				$(form).ajaxSubmit(
				{
					dataType: 'json',
					success: function(json)
					{
						if (json.status == 'success')
						{
							modal.style.display = "block";
							content.innerText = json.message;
							//location.href = '/service_quick/event_serial';
							var timer = setTimeout(function() {
								 window.location='/service_quick/event_serial?event_id=<?=$event->id?>'

						 }, 1000);
						}
						else
						{
							modal.style.display = "block";
							content.innerHTML = "<font color='red'>" + json.message + "</font>";
							//leOpenDialog('兌換失敗', json.message, leDialogType.MESSAGE);
						}
					}
				});
			}
		});
	});

	</script>
