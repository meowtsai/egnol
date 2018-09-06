<style media="screen">
#datatable-editable .fa-times{
	color: #d03f3f;
}
#datatable-editable .fa-trash-alt{
	color: #d03f3f;
}
</style>


<div id="func_bar">
</div>


<form method="get" action="<?=site_url("service/complaints")?>" class="form-search">
	<input type="hidden" name="game_id" value="<?=$this->game_id?>">

	<div class="control-group">
	</div>



</form>


<div class="content">
<div class="container-fluid">
	<div class="row">
		<div class="col-sm-12">
			<div class="card">
				<div class="card-body">
					<div class="row">
							<div class="col-sm-6">
									<div class="m-b-30">
											<button id="addToTable" class="btn btn-success waves-effect waves-light">新增項目 <i class="fas fa-plus-circle"></i></button>
									</div>
							</div>
					</div>
					<table class="table table-striped add-edit-table" id="datatable-editable">
							<thead>
									<tr>
											<th>#</th>
											<th>遊戲</th>
											<th>項目名稱</th>
											<th>案件數量</th>
											<th>處理者</th>
											<th>建立日期</th>
											<th>更新日期</th>
									</tr>
							</thead>
							<tbody>
									<tr id="rowForInsert">
										<td></td>
										<td>
										</td>
										<td></td>
										<td></td>
										<td></td>
										<td></td>
										<td></td>
										<td>
												<a href="#" class="save-row"  title="儲存" data-original-title="儲存"><i class="fa fa-save"></i></a>
												<a href="#" class="cancel-row" title="取消" data-original-title="取消"><i class="fa fa-times"></i></a>
										</td>
									</tr>
									<tr class="gradeX" style="display:none">
											<td>1</td>
											<td>光明之戰
											</td>
											<td>網路設定異常</td>
											<td>100</td>
											<td></td>
											<td>2018/9/1</td>
											<td>2018/8/1</td>
											<td class="actions">
													<a href="#" class="on-default edit-row" data-toggle="tooltip" data-placement="top" title="編輯" data-original-title="Edit"><i class="fas fa-pencil-alt"></i></a>
													<a href="#" class="on-default remove-row" data-toggle="tooltip" data-placement="top" title="刪除" data-original-title="Delete"><i class="far fa-trash-alt"></i></a>
													<a href="#" class="hidden on-editing save-row" data-toggle="tooltip" data-placement="top" title="儲存" data-original-title="Save"><i class="fa fa-save"></i></a>
													<a href="#" class="hidden on-editing cancel-row" data-toggle="tooltip" data-placement="top" title="取消" data-original-title="Cancel"><i class="fa fa-times"></i></a>
											</td>
									</tr>
								</tbody>
							</table>
				</div>
			</div>
		</div>
	</div>

	<!-- Modal -->
	<div class="modal fade" id="comfirmModal" tabindex="-1" role="dialog" aria-labelledby="comfirmModalLabel" aria-hidden="true">
	  <div class="modal-dialog" role="document">
	    <div class="modal-content">
	      <div class="modal-header">
	        <h5 class="modal-title" id="comfirmModalLabel">確認刪除視窗</h5>
	      </div>
	      <div class="modal-body">
					<p>您確定要刪除這筆紀錄嗎? </p>

	      </div>
	      <div class="modal-footer">
	        <button type="button" class="btn btn-secondary" data-dismiss="modal">取消</button>
	        <button type="button" class="btn btn-danger" onclick="delRow()">刪除</button>
	      </div>
	    </div>
	  </div>
	</div>



</div>
</div>

<script type="text/javascript">
	$('#comfirmModal').modal('hide');
	var taskList = <?=json_encode($tasks)?>;
	var selRowId = null;
	var selRow = null;

//	//#	遊戲	專案名稱	案件數量	處理者	建立日期	回覆日期
	var cloneAction =$(".actions").clone(true);
	var mode ="處理中";
	for (var i = 0; i < taskList.length; i++) {
		if (taskList[i].status =="4")
		{
			mode ="已完成(立即結案)";
		}
		else if (taskList[i].status =="7") {
			mode ="已完成(預約結案)";
		}

		var $tr = $(`<tr><td>${taskList[i].id}</td>
			<td>${taskList[i].game_name}</td>
			<td><a href="./batch_handler/${taskList[i].id}">${taskList[i].title}</a></td>
			<td>${taskList[i].count}</td>
			<td>${taskList[i].admin_name}</td>
			<td>${taskList[i].create_time}</td>
			<td>${taskList[i].update_time}</td>
			<td>${mode}</td>
			</tr>`);
      if (taskList[i].status=="1" && taskList[i].is_editable=="1"){
          cloneAction.clone().appendTo($tr);
      }

		$("#datatable-editable").append($tr);


	}

	// var actionTd = $(".actions").cloneNode(true)
	// var clone = actionTd.cloneNode(true); // copy children too
	//       clone.id = "newID"; // change id or other attributes/contents
	//       table.appendChild(clone); // add new row to end of table


	document.getElementById("addToTable").addEventListener("click", showInput);



	var gameSelect = document.createElement('select');
	var games = <?=json_encode($games)?>;
	//console.log(games);
	var option;
	for (var i = 0; i < games.length; i++) {
		option = document.createElement( 'option' );
		option.value = option.textContent = games[i].game_id;
		option.text =  games[i].name;
		gameSelect.appendChild( option );
	}
	gameSelect.setAttribute('id', 'selGames');

	var inputField = document.createElement('input');
	inputField.setAttribute('type', 'text');
	inputField.setAttribute('id', 'inputTaskName');
	var rowForInsert = document.getElementById("rowForInsert");
	r = rowForInsert.getElementsByTagName("td")[1];
	r.appendChild(gameSelect);
	r2 = rowForInsert.getElementsByTagName("td")[2];
	r2.appendChild(inputField);
	rowForInsert.style.display="none";

	function showInput(){

		rowForInsert.style.display = "";


	}



	var cancelBtns = document.getElementsByClassName("cancel-row");
	for (var i = 0; i < cancelBtns.length; i++) {
    cancelBtns[i].addEventListener('click', cancelEdit, false);
	}

	var saveBtns = document.getElementsByClassName("save-row");
	for (var i = 0; i < saveBtns.length; i++) {
    saveBtns[i].addEventListener('click', addRow, false);
	}

	var editBtns = document.getElementsByClassName("edit-row");
	for (var i = 0; i < editBtns.length; i++) {
    editBtns[i].addEventListener('click', editMode, false);
	}



	var delBtns = document.getElementsByClassName("remove-row");
	for (var i = 0; i < delBtns.length; i++) {
		delBtns[i].addEventListener('click', showConfirm, false);
	}






	function cancelEdit(event){
		var tr = event.target.closest( "tr" );

		if (tr.id =="rowForInsert"){ //新增的
			tr.style.display="none";
		}
		else
		{
			selRowId = $(tr).children().first().text();
			const selTask = taskList.filter(task => task.id == selRowId)[0];
			$(tr).find("td:nth-child(2)").html(selTask.game_name);
			$(tr).find("td:nth-child(3)").html(selTask.title);
		}

	}

	function editMode(event) {
		selRow = event.target.closest( "tr" );
		selRowId = $(selRow).children().first().text();
		const selTask = taskList.filter(task => task.id == selRowId)[0];

		var gameText = $(selRow).find("td:nth-child(2)").text();
		//$(gameSelect.clone()).children('[text="gameText"]').attr('selected', true);
		//$(selRow).find("td:nth-child(2)").html("");
		var editSelect = $(gameSelect).clone();
		$(editSelect).children('[value="'+selTask.game_id+'"]').attr('selected', true)
		$(selRow).find("td:nth-child(2)").html("").append(editSelect);

		var editInput = $(inputField).clone();
		$(editInput).val(selTask.title);
		$(selRow).find("td:nth-child(3)").html("").append(editInput)

		console.log(gameText);
	}

	function showConfirm(event){
		selRow = event.target.closest( "tr" );

		//console.log($(selRow).children().first().text());
		selRowId = $(selRow).children().first().text();
		const selTask = taskList.filter(task => task.id == selRowId)[0];
		$('#comfirmModalLabel').text("準備刪除批次項目 [" +String(selRowId) +"]" + selTask.title);
		$('#comfirmModal').modal('show');

	}

	function delRow(){
		//delete_batch_task
		console.log("delRow",selRowId);
		let url = "./delete_batch_task/" + selRowId;
	  $.ajax({
	    type: "POST",
			dataType: "json",
			contentType: 'application/json; charset=UTF-8',
	    url: url,
	  }).done(function(result) {

			if (result.status == 'success') {
					//console.log( "Request done: " + result );
				$(selRow).remove();
				$('#comfirmModal').modal('hide');
			}
			//location.reload();
	  })
	  .fail(function( jqXHR, textStatus ) {
	    console.log( "Request failed: " + textStatus );
	  })
	  .always(function() {
	    //alert( "complete" );
	  });;
	}

	// "game_id" => $this->input->post("game_id"),
	// 'title' => nl2br(htmlspecialchars($this->input->post("title"))),
	// 'admin_uid' => $_SESSION['admin_uid'],
	// "status" => $this->input->post("status"),

	function addRow(){
	  //alert('id=' + id);
		selRow = event.target.closest( "tr" );
		var id = null;
		let data = null;
		if (selRow.id =="rowForInsert"){ //新增的
			var game_id = gameSelect.options[gameSelect.selectedIndex].value;
			var title = inputField.value;
			var status = 1;
			data = `game_id=${game_id}&title=${title}&status=${status}`;
		}
		else
		{
			id = $(selRow).children().first().text();
			var game_id =$(selRow).find("select").find(":selected").val()
			var title =$(selRow).find("input").val();
			var status = 1;
			data = `game_id=${game_id}&title=${title}&status=${status}&id=${id}`;
		}

		console.log(game_id);
		console.log(title);
		//return;
	  let url = "./batch_add_row";

	  $.ajax({
	    type: "POST",
	    url: url,
	    data:data ,
	  }).done(function(result) {
	    console.log( "Request done: " + result );
			location.reload();
	    // $("#tr" + id).css("background-color","silver");
	    // $("#tr" + id).hide();
	    // if (result.status == 'success') {
	    //   $("#tr" + id).css("background-color","silver");
	    //   $("#tr" + id).hide();
	    // }
	    // else {
			//
	    // }

	  })
	  .fail(function( jqXHR, textStatus ) {
	    console.log( "Request failed: " + textStatus );

	  })
	  .always(function() {
	    //alert( "complete" );
	    console.log("complete")
	  });;
	}


	//batch_add_row

</script>
