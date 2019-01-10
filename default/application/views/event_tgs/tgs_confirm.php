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
	margin-left:50px;
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
.tbl_head{
	text-align:right;
}
</style>

<div id="content-login">
	<div>
		<h3 >
			兌獎中心 > <?=$event->event_name?>
		</h3>
    <form id="event_form"  method="post" action="<?=$longe_url?>event_tgs/event_serial_confirm_ajax">
			<div style="margin-left: auto;  margin-right: auto;  width: 400px;">
				<table >
          <tr>
						<th class="tbl_head">遊戲：</th>
						<td><b>荒野行動</b></td>
					</tr>
					<tr>
						<th class="tbl_head">活動名稱：</th>
						<td><b>荒野行動TGS虛寶兌換</b></td>
					</tr>
					<tr>
						<th class="tbl_head">角色ID：</th>
						<td><?=$data["char_id"]; ?><?=$char_id; ?></td>
					</tr>
					<tr>
						<th class="tbl_head">角色名：</th>
						<td><?=$data["char_name"]; ?></td>
					</tr>
					<tr>
						<th class="tbl_head">Email：</th>
						<td><?=$data["email"]; ?></td>
					</tr>
					<tr>
						<th class="tbl_head">伺服器：</th>
						<td>
              <?=$data["server_text"]; ?>
							</td>
					</tr>
					<tr>
						<th class="tbl_head">序號：</th>
						<td><?=$data["serial"];?></td>
					</tr>
					<tr>
						<td colspan="2">
						<button type="button" name="cmdCancel" onclick="javascript:history.back();this.disabled=true;" class="btn btn-cancel" >我要修改</button>
						<button type="submit" name="cmdSubmit"  class="btn pull-right">確認無誤送出!</button>

            <div id="succ_info" style="display: none;background-color:#FA5858;color:#ffffff;font-weight:bold;border-radius:3px;text-align:center;padding:5px;">

                【已經登錄完成】
                <br /><button type="button" name="cmdGoGo" onclick="javascript:location.href='/event_tgs'" class="btn pull-right">>>繼續輸入其他序號<<<</button>
            </div>
						<hr />
						</td>
					</tr>


					<tr>
						<td colspan="2">
								<fieldset style="width:400px;border-radius: 5px;white-space:normal;border:#848484;">
									<legend>注意事項</legend>
									<ul style="text-align: left;list-style-type:square;line-height:150%;color: #2E2E2E;font-size:smaller;">
										<li>請務必確認角色id和角色名稱無誤。</li>
										<li>按下確認後系統會發送 mail 到您填寫的信箱</li>
										<li>這邊僅是登錄, 獎項將於 2019/3/15 晚上 23：59 前，以遊戲內郵件發送至所填寫的角色ID。</li>
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
              $("button[name='cmdSubmit']").hide();
              $("button[name=cmdCancel]").hide();
              $("#succ_info").html("登錄完成，恭喜您獲得[" + json.item_title + "]<br /><button type='button' name='cmdGoGo' onclick='javascript:location.href=\"/event_tgs\"' class='btn-cancel pull-right'>>>繼續輸入其他序號<<<</button>");

              $("#succ_info").show();




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
