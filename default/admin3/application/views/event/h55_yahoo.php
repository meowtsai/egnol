
<div id="func_bar">

</div>

<div>
  <input type="text" id="find_keyword" name="find_keyword"  value="" class="input-medium required" placeholder="關鍵字...."> <i class='icon-search'></i>


  <ul class="nav nav-tabs" id="myTab">
    <li class="active"><a href="#home">兌換成功紀錄</a></li>
    <li><a href="#profile">歷程紀錄</a></li>

  </ul>

  <div class="tab-content">
    <div class="tab-pane active" id="home">
      <div class="alert alert-success">
      	成功驗證待發送獎勵的玩家名單
      </div>

      <? if ($result):?>
        <? if ($result->num_rows() == 0):?>
          <div class="none">尚無資料</div>
        <? else:?>
          <table class="table table-striped" id="list_table" style="width:700px">
          	<thead>
          		<tr>
                <th >帳號</th>
                <th >角色名稱</th>
                <th >角色id</th>
                <th >序號</th>
                <th >兌換時間</th>
                  </tr>
          	</thead>
          	<tbody>
          	</tbody>
          </table>
        <? endif;?>
      <? endif;?>
    </div>
    <div class="tab-pane" id="profile">
      <div class="alert alert-default">
      	此為玩家嘗試輸入驗證碼的歷程紀錄, 同一玩家輸入五次錯誤就鎖定，僅供規則調整之參考用。
      </div>
      <table class="table table-striped" id="log_table" style="width:700px">
        <thead>
          <tr>
            <th >#</th>
            <th >char_id</th>
            <th >ip</th>
            <th >serial_no</th>
            <th >create_time</th>
          </tr>
        </thead>
        <tbody>
        </tbody>
      </table>
    </div>
  </div>





<script type="text/javascript">
  $('#myTab a').click(function (e) {
    e.preventDefault();
    $(this).tab('show');
  })

  var logList = <?=json_encode($log->result());?>;

  for (var i = 0; i < logList.length; i++) {
    var $tr = $(`<tr><td>${logList[i].id}  </td>
      <td>${logList[i].char_name} </td>
      <td>${logList[i].ip} </td>
      <td>${logList[i].serial_no}</td>
      <td>${logList[i].create_time}</td>
      </tr>`);
    $("#log_table").append($tr);
  }




  var userList = <?=json_encode($result->result());?>;
  for (var i = 0; i < userList.length; i++) {
		var $tr = $(`<tr><td>${userList[i].partner_uid}</td>
			<td>${userList[i].name}</td>
			<td>${userList[i].in_game_id}</td>
			<td>${userList[i].serial}</td>
			<td>${userList[i].dt}</td>
			</tr>`);
		$("#list_table").append($tr);
	}

  $("#find_keyword").keyup(function(){

    var keyword= $("#find_keyword").val();
    //console.log(keyword);
    if(keyword)
    {
      $("#list_table tr:nth-child(n+1)").hide();
      $("#list_table tr td:nth-child(2)").each(function(){
        $("#list_table tr >td:contains('"+keyword+"')").parent().show()
      })
    }
    else {
      $("#list_table tr:nth-child(n+1)").show();
    }


  })

</script>
