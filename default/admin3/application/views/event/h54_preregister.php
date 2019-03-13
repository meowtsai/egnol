
<div id="func_bar">

</div>
<div>
  <input type="text" id="find_keyword" name="find_keyword"  value="" class="input-medium required" placeholder="關鍵字...."> <a href="javascript:;;" onclick="func_search()"><i class='icon-search'></i></a>
  <ul class="nav nav-tabs" id="myTab">
    <li class="active"><a href="#home">總覽</a></li>
    <li><a href="#all_users">預註冊玩家清單</a></li>
  </ul>
  <span class="recordcount label label-warning" style="display: flex;float:right;"></span>
  <div class="pagination">
    <ul>
    </ul>
  </div>
  <div class="tab-content">
    <div class="tab-pane active" id="home">

      <div class="container">
        <div class="row" style="display: flex;">
          <div class="col-sm" style="margin:0 20px;">
            <h3>每日總數</h3>
            <table class="table table-bordered" id="data_table_summary" >
              <thead>
                 <tr>
                   <th>日期</th>
                   <th>人數</th>
                 </tr>
               </thead>
               <tbody>
               </tbody>
            </table>
          </div>
          <div class="col-sm">
            <h3>國家分布</h3>
            <table class="table table-bordered" id="data_table_country" >
              <thead>
                 <tr>
                   <th>國家</th>
                   <th>人數</th>
                 </tr>
               </thead>
               <tbody>
               </tbody>
            </table>
          </div>


        </div>
      </div>




    </div>
    <div class="tab-pane" id="all_users">
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
                <th >Email</th>
                <th >ip</th>
                <th >國家</th>
                <th >城市</th>
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

  </div>



<script type="text/javascript">
  var activeTable = "預註冊玩家清單";
  var which_tbl= "general";

  var csvData ="編號,email,ip,國家,城市,時間\n";
$(".pagination").hide();
  $('#myTab a').click(function (e) {
    e.preventDefault();
    $(this).tab('show');

    activeTable = $(this).text(); //歷程紀錄
    switch (activeTable) {
      case "預註冊玩家清單":
        which_tbl = "list_table";
        break;
      case "總覽":
        which_tbl = "general";
        break;
      default:
        which_tbl = "general";
        break;

    }
    //which_tbl =  (activeTable==="預註冊玩家清單"?"list_table":activeTable==="歷程紀錄"?"log_table":"ref_table");
    curPage = 1;
    //renderListTable(curPage);
    func_search();
    //showCount();
  })

  // var summaryList = [{"dDate":"2018-12-10","count":"5"}];
  // var summaryCountryList = [{"country":"Taiwan","count":"5"}];

var summaryList = <?=json_encode($summary->result());?>;
for (var i = 0; i < summaryList.length; i++) {
  var $tr = $(`<tr><td>${summaryList[i].dDate}  </td>
    <td>${summaryList[i].count} </td>
    </tr>`);
  $("#data_table_summary").append($tr);
}
var summaryCountryList = <?=json_encode($summary_country->result());?>;
for (var i = 0; i < summaryCountryList.length; i++) {
  var $tr = $(`<tr><td>${summaryCountryList[i].country}  </td>
    <td>${summaryCountryList[i].count} </td>
    </tr>`);
  $("#data_table_country").append($tr);
}

  var userList = <?=json_encode($result->result());?>;
  for (var i = 0; i < userList.length; i++) {
		var $tr = $(`<tr><td>${userList[i].id}</td>
			<td>${userList[i].email}</td>
      <td>${userList[i].ip}</td>
      <td>${userList[i].country}</td>
      <td>${userList[i].nick_name}</td>
			<td>${userList[i].create_time}</td>
			</tr>`);
		$("#list_table").append($tr);

    csvData += `${userList[i].id},${userList[i].email},${userList[i].ip},${userList[i].country},${userList[i].nick_name},${userList[i].create_time}\n`;
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
    $(".pagination").hide();
    $("#"+ which_tbl +" tr").hide();
    $("#"+ which_tbl +" tr:eq(0)").show();

    for (var i = 0; i < page_size; i++) {
      //console.log(parseInt(3+i+((curPage-1)*page_size)));
      $("#"+ which_tbl +" tr:eq("+ parseInt(1+i+((curPage-1)*page_size))+")").show();
    }
    //console.log("which_tbl",which_tbl);
    if (which_tbl!="general")
    {
    showCount();
    }


  }

  function showCount(){

    $(".pagination").show();
    var count = userList.length;
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

      filename = 'h54_preregister_export'+ new Date().getTime() +'.csv';

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
