
<div id="func_bar">

</div>
<div>
  <input type="text" id="find_keyword" name="find_keyword"  value="" class="input-medium required" placeholder="關鍵字...."> <a href="javascript:;;" onclick="func_search()"><i class='icon-search'></i></a>
  <ul class="nav nav-tabs" id="myTab">
    <li class="active"><a href="#home">預註冊玩家清單</a></li>
    <li><a href="#profile">歷程紀錄</a></li>
    <li><a href="#ref">關係對照表</a></li>
  </ul>
  <span class="recordcount label label-warning" style="display: flex;float:right;"></span>
  <div class="pagination">
    <ul>
    </ul>
  </div>
  <div class="tab-content">
    <div class="tab-pane active" id="home">
      <div class="alert alert-success">
      	預註冊玩家清單
      </div>
      <? if ($result):?>
        <? if ($result->num_rows() == 0):?>
          <div class="none">尚無資料</div>
        <? else:?>
          <table class="table table-striped" id="list_table" style="width:800px">
          	<thead>
          		<tr>
                <th >id</th>
                <th >臉書暱稱</th>
                <th >Email</th>
                <th >未使用/所有物品</th>
                <th >ip</th>
                <th >國家</th>
                <th >時間</th>
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
      	所有玩家歷程, 方便監控是否有異常活動
      </div>
      <table class="table table-striped" id="log_table" style="width:700px">
        <thead>
          <tr>
            <th >#</th>
            <th >活動</th>
            <th >時間</th>
          </tr>
        </thead>
        <tbody>
        </tbody>
      </table>
    </div>

    <div class="tab-pane" id="ref">
      <div class="alert alert-default">
      	NPC 和道具關係對照, 參考用
      </div>
      <table class="table table-striped" id="ref_table" style="width:1000px">
        <thead>
          <tr>
            <th >NPC名稱</th>
            <th >道具名稱</th>
            <th >好感度</th>
            <th >對白</th>
            <th >語音檔</th>
          </tr>
        </thead>
        <tbody>
        </tbody>
      </table>
    </div>
  </div>



<script type="text/javascript">
  var activeTable = "預註冊玩家清單";
  var which_tbl= "list_table";

  var csvData ="編號,臉書暱稱,email,ip,國家,兌換時間\n";

  $('#myTab a').click(function (e) {
    e.preventDefault();
    $(this).tab('show');
    //console.log($(this).text());

//     歷程紀錄
// l20na_preregister:397 log_table
// l20na_preregister:337 關係對照表
// l20na_preregister:397 log_table
// l20na_preregister:337 預註冊玩家清單

    activeTable = $(this).text(); //歷程紀錄
    which_tbl =  (activeTable==="預註冊玩家清單"?"list_table":activeTable==="歷程紀錄"?"log_table":"ref_table");
    func_search();
    //showCount();
  })




var refList = <?=json_encode($refrence->result());?>;
for (var i = 0; i < refList.length; i++) {
  var $tr = $(`<tr><td>${refList[i].npc_name}  </td>
    <td>${refList[i].item_name} </td>
    <td>${(refList[i].response==='awesome'?"<font color='blue'>非常喜歡</font>":refList[i].response==='okla'?"普通":"<font color='red'>不喜歡</font>")}</td>
    <td>${refList[i].response_text}</td>
    <td>${refList[i].response_voice}</td>
    </tr>`);
  $("#ref_table").append($tr);
}
  var logList = <?=json_encode($log->result());?>;
  for (var i = 0; i < logList.length; i++) {
    var $tr = $(`<tr><td>${logList[i].id}  </td>
      <td>${logList[i].note} </td>
      <td>${logList[i].create_time}</td>
      </tr>`);
    $("#log_table").append($tr);
  }

  // a.id,a.nick_name,a.create_time,a.update_time,a.email,a.ip,a.country,")
  // ->select("(select concat(sum(case when status=1 then 1 else 0 end),'/',count(*)) as item_status from l20na_detail where o_id in (select id from l20na_orders where event_uid=a.id)) as item_status",FALSE)
  // ->from("event_preregister a")"
  // "
  var userList = <?=json_encode($result->result());?>;
  for (var i = 0; i < userList.length; i++) {
		var $tr = $(`<tr><td>${userList[i].id}</td>
			<td><a href="./l20na_preregister_user/${userList[i].id}">${userList[i].nick_name}</a></td>
			<td>${userList[i].email}</td>
      <td>${userList[i].item_status}</td>
      <td>${userList[i].ip}</td>
      <td>${userList[i].country}</td>
			<td>${userList[i].create_time}</td>
			</tr>`);
		$("#list_table").append($tr);

    csvData += `${userList[i].id},${userList[i].nick_name},${userList[i].email},${userList[i].ip},${userList[i].country},${userList[i].create_time}\n`;
	}
  var curPage = 1;
  var page_size=50;


  renderListTable();

  $("#find_keyword").keydown(function(){func_search();});
  $("#find_keyword").keyup(function(){func_search();});

  function func_search(){

    var keyword= $("#find_keyword").val();
    console.log(which_tbl);
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
    var count =  (activeTable==="預註冊玩家清單"?userList.length:activeTable==="歷程紀錄"?logList.length:refList.length);
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

      filename = 'l20na_preregister_export'+ new Date().getTime() +'.csv';

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
