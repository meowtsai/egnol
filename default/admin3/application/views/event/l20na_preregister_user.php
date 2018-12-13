
<div id="func_bar">

</div>
<div>

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

<hr>

<table class="table table-striped" id="npc_table" style="width:800px">
  <thead>
    <tr>
      <th >id</th>
      <th >NPC名稱</th>
      <th >代碼</th>
      <th >性別</th>
      <th >好感度</th>
    </tr>
  </thead>
  <tbody>
  </tbody>
</table>
<hr>

<table class="table table-striped" id="item_table" style="width:800px">
  <thead>
    <tr>
      <th >活動名目</th>
      <th >領取時間</th>

    </tr>
  </thead>
  <tbody>
  </tbody>
</table>


<hr>

<table class="table table-striped" id="log_table" style="width:800px">
  <thead>
    <tr>
      <th >#</th>
      <th >歷程描述</th>
      <th >送誰</th>
      <th >送什麼</th>
      <th >時間</th>

    </tr>
  </thead>
  <tbody>
  </tbody>
</table>
</div>


<script type="text/javascript">

  var logList = <?=json_encode($logs->result());?>;
  for (var i = 0; i < logList.length; i++) {
		var $tr = $(`<tr><td>${logList[i].id}</td>
			<td>${logList[i].note}</td>
			<td>${logList[i].aff_id}(${logList[i].npc_code})</td>
      <td>${logList[i].item_id}</td>
      <td>${logList[i].create_time}</td>
      </tr>`);
		$("#log_table").append($tr);
  }


  var userList = <?=json_encode($user->result());?>;
  for (var i = 0; i < userList.length; i++) {
		var $tr = $(`<tr><td>${userList[i].id}</td>
			<td>${userList[i].nick_name}</td>
			<td>${userList[i].email}</td>
      <td>${userList[i].item_status}</td>
      <td>${userList[i].ip}</td>
      <td>${userList[i].country}</td>
			<td>${userList[i].create_time}</td>
			</tr>`);
		$("#list_table").append($tr);
  }

  var itemList = <?=json_encode($items->result());?>;

  var reducedOrders = itemList.map(function(item){
    return { order_id:item.desc, order_dt: item.create_time};
  }).reduce(function(init, curr){
    //console.log(curr);
    if (init.length===0 || init[init.length-1].order_id!=curr.order_id){
      init.push(curr);
    }
    return init;
  },[]);


  for (var i = 0; i < reducedOrders.length; i++) {
    var $tr = $(`<tr><td><b>★${reducedOrders[i].order_id}</b></td>
      <td>${reducedOrders[i].order_dt}</td>
      </tr>`);
    $("#item_table").append($tr);

    var order_item = itemList.filter(function(item){
      if (item.desc ===reducedOrders[i].order_id ){
        return item;
      }
    });


    var allItems = "";
    for (var j = 0; j < order_item.length; j++) {
      allItems += `<tr><td>${order_item[j].id}</td>
        <td>${order_item[j].item_name}(${order_item[j].item_code})</td>
        <td>${order_item[j].status==1?"<font color='green'>未使用</font>":"<font color='red'>已使用</font>"}</td>

        </tr>`;
        //$("#item_table").append($tr);
    }

    var $tr = $(`<tr><td colspan=3>
      <table><tr>
        <th>#</th>
        <th>物品(code)</th>
        <th>使用狀態</th><tr/>${allItems}</table></td>
      </tr>`);

      $("#item_table").append($tr);

  }


// allbooks = [
//   'Alphabet', 'Bible', 'Harry Potter', 'War and peace',
//   'Romeo and Juliet', 'The Lord of the Rings',
//   'The Shining'
// ]


  var npcList = <?=json_encode($npcs->result());?>;
  for (var i = 0; i < npcList.length; i++) {
    var $tr = $(`<tr><td>${npcList[i].id}</td>
      <td>${npcList[i].npc_name}</td>
      <td>${npcList[i].npc_code}</td>
      <td>${npcList[i].npc_gender=="m"?"男性":"女性"}</td>
      <td>${npcList[i].affection}</td>
      </tr>`);
    $("#npc_table").append($tr);
  }



</script>
