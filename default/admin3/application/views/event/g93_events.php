
<div id="func_bar">

</div>
<div>
  <input type="text" id="find_keyword" name="find_keyword"  value="" class="input-medium required" placeholder="關鍵字...."> <a href="javascript:;;" onclick="func_search()"><i class='icon-search'></i></a>
  <ul class="nav nav-tabs" id="myTab">
    <li class="active"><a href="#home">兌換成功紀錄</a></li>
    <li><a href="#profile">歷程紀錄</a></li>
  </ul>
  <span class="recordcount label label-warning" style="display: flex;float:right;"></span>
  <div class="pagination">
    <ul>
    </ul>
  </div>
  <div class="tab-content">
    <div class="tab-pane active" id="home">
      <div class="alert alert-success">
      	成功驗證待發送獎勵的玩家名單
      </div>
      <? if ($result):?>
        <? if ($result->num_rows() == 0):?>
          <div class="none">尚無資料</div>
        <? else:?>
          <table class="table table-striped" id="list_table" style="width:1500px">
          	<thead>
          		<tr>
                <th >帳號</th>
                <th >角色名稱</th>
                <th >角色id</th>
                <th >序號</th>
                <th >兌換時間</th>
                <th >獎項</th>

                  </tr>
          	</thead>
          	<tbody>
          	</tbody>
          </table>

          <input type="button" class="btn btn-small btn-warning" name="action" value="輸出" onclick="downloadCSV()">
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
            <th >輸入序號</th>
            <th >時間</th>
            <th >結果</th>
          </tr>
        </thead>
        <tbody>
        </tbody>
      </table>
    </div>
  </div>



<script type="text/javascript">
  var activeTable = "兌換成功紀錄";
  var which_tbl= "list_table";

  var csvData ="帳號,角色名稱,角色id,序號,兌換時間,獎項\n";

  $('#myTab a').click(function (e) {
    e.preventDefault();
    $(this).tab('show');
    //console.log($(this).text());

    activeTable = $(this).text(); //歷程紀錄
    which_tbl =  (activeTable==="兌換成功紀錄"?"list_table":"log_table");
    func_search();
    //showCount();


  })


  var logList = <?=$log? json_encode($log->result()):"";?>;
  for (var i = 0; i < logList.length; i++) {
    var $tr = $(`<tr><td>${logList[i].id}  </td>
      <td>${logList[i].char_name} </td>
      <td>${logList[i].ip} </td>
      <td>${logList[i].serial}</td>
      <td>${logList[i].create_time}</td>
      <td>${logList[i].status==1?'成功':'失敗'}</td>
      </tr>`);
    $("#log_table").append($tr);
  }

  var userList = <?=$result? json_encode($result->result()):"";?> ;
  for (var i = 0; i < userList.length; i++) {
		var $tr = $(`<tr><td>${userList[i].partner_uid}</td>
			<td>${userList[i].name}</td>
			<td>${userList[i].in_game_id}</td>
			<td>${userList[i].serial}</td>
			<td>${userList[i].dt}</td>
      <td>${userList[i].event_sub_id} - ${userList[i].title}</td>
			</tr>`);
		$("#list_table").append($tr);

    csvData += `${userList[i].partner_uid},"${userList[i].name}",${userList[i].in_game_id},${userList[i].serial},${userList[i].dt},${userList[i].event_sub_id} - ${userList[i].title}\n`;
	}
  var curPage = 1;
  var page_size=50;


  renderListTable();

  $("#find_keyword").keydown(function(){func_search();});
  $("#find_keyword").keyup(function(){func_search();});

  function func_search(){

    var keyword= $("#find_keyword").val();
    //console.log(which_tbl);
    if(keyword)
    {
      var rc=0;
      $("#"+ which_tbl +" tbody>tr").hide();

      $("#"+ which_tbl +" tr >td:contains('"+keyword+"')").parent().show();
      rc = $("#"+ which_tbl +" tr >td:contains('"+keyword+"')").parent().length;
      $(".recordcount").text("總筆數:" + rc);
    }
    else {
      //$("#list_table tr:nth-child(n+1)").show();
      renderListTable();
    }
  }



  function pageClick(){
    curPage = $(this).text();
    renderListTable(curPage);
  }

  function renderListTable(){
    //console.log(curPage);
    // $("#datatable-editable tr:eq(3)) 是第一行
    $("#"+ which_tbl +" tr").hide();
    $("#"+ which_tbl +" tr:eq(0)").show();

    for (var i = 0; i < page_size; i++) {
      //console.log(parseInt(3+i+((curPage-1)*page_size)));
      $("#"+ which_tbl +" tr:eq("+ parseInt(1+i+((curPage-1)*page_size))+")").show();
    }

    showCount();

  }

  function showCount(){
    var count =  (activeTable==="兌換成功紀錄"?userList.length:logList.length);
    $(".recordcount").text("總筆數:" + count);
    var page_count=Math.ceil(count/page_size);

    $(".pagination > ul").html("");
    for (var i = 1; i <= page_count; i++) {
      $(".pagination > ul").append("<li><span><a href='javascript:;;'>"+ String(i) +"</a></span></li>");
    }

    $(".pagination > ul> li").click(pageClick);
  }


  function downloadCSV() {
      var data, filename, link;

      filename = 'g93_events_export'+ new Date().getTime() +'.csv';

      if (!csvData.match(/^data:text\/csv/i)) {
          csvData = 'data:text/csv;charset=uft-8,' + csvData;
      }
      data = encodeURI(csvData);

      link = document.createElement('a');
      link.setAttribute('href', data);
      link.setAttribute('download', filename);
      link.click();
  }
</script>
