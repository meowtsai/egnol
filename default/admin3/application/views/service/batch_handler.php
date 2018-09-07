<?
$question_type = $this->config->item('question_type');

if (count($task) > 0):?>
<style media="screen">
.fa-trash-alt{
	color: #d03f3f;
}
#q_area li { margin: 0 10px; display: inline; }
</style>

<div id="func_bar">
</div>



	<table class="table table-bordered" style="width:auto;">
			<caption>批次處理項目</caption>
				<tbody>
					<tr>
						<th style="width:120px">遊戲</th>
						<td style="width:150px"><?=$task[0]->game_name?></td>
						<th  style="width:120px">項目名稱</th>
						<td  style="width:150px"><?=$task[0]->title?></td>
					</tr>
				<tr>
					<th style="width:120px">建立時間</th>
					<td style="width:150px"><?=$task[0]->create_time?></td>
					<th>建立人員</th>
					<td><?=$task[0]->admin_name?></td>
				</tr>

			</tbody>
		</table>


<div id="q_area" class="well" style="background-color:pink;width:50%">
	<span class="label label-info">提問單號</span>
	<? if ($q_list && $task[0]->status =="1" && $task[0]->is_editable=="1"): ?>
	<a href="javascript:;" url="<?=site_url("service/remove_batch_q/{$task[0]->id}")?>" class="json_post btn btn-small"><i class="icon-remove"></i> 全部移除</a>
	<?endif;?>


</div>

<? if ($task[0]->status =="1" && $task[0]->is_editable=="1"): ?>
<form id="reply_form" method="post" action="<?=site_url("service/batch_reply_json")?>">
	<input type="hidden" id="batch_id" name="batch_id" value="<?=$task[0]->id?>">

問題類型
	<select id="new_type" name="new_type" style="width:100px">
			<? foreach($question_type as $key => $type):?>
			<option value="<?=$key?>" ><?=$type?></option>
			<? endforeach;?>
	</select>

	<br />
	回覆
	<br />
	<textarea id="content" name="content" rows="28" style="width:50%" class="required">親愛的玩家您好，
感謝您使用龍邑客服中心線上回報系統


倘若有任何疑問或是需要進一步瞭解的事項，請直接透過客服中心線上回報，我們將會竭誠為您服務。
***龍邑客服中心敬上***</textarea>

	<div class="form-actions">
		<a href="javascript:;" onclick="confirm_before_submit()" class="btn btn-primary">下一步</a>




		</div>
</form>
<?endif;?>
<!-- Modal -->
<div class="modal fade" id="comfirmModal" tabindex="-1" role="dialog" aria-labelledby="comfirmModalLabel" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="comfirmModalLabel">確認</h5>
			</div>
			<div class="modal-body well">
				<p><!----送出表單前先顯示內容-----></p>

			</div>
			<div class="alert alert-error">

			</div>

			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-dismiss="modal">取消</button>
				<a href="javascript:;" onclick="reply_n_close('4')" class="pull-right btn btn-danger">送出回覆並立即結案</a>
				<a href="javascript:;" onclick="reply_n_close('7')" class="pull-right btn btn-warning">送出回覆並預約結案</a>
				<a href="javascript:;" onclick="reply_n_close('2')" class="pull-right btn btn-primary">送出回覆</a>
			</div>
		</div>
	</div>
</div>


<script type="text/javascript">
	$('#comfirmModal').modal('hide');
var q_list =<?=json_encode($q_list)?>;
showQCount();

$("#q_area").append("<ul class='list-group'>")
for (var i = 0; i < q_list.length; i++) {
	$("#q_area ul").append("<li class='list-group-item'>")
	$("#q_area ul li:last").append("<a href='../view/"+ q_list[i].question_id +"'>" +  q_list[i].question_id + " </a>")

	<? if ($q_list && $task[0]->status =="1" && $task[0]->is_editable=="1"): ?>
	$("#q_area ul li:last").append("<a href='#' class='remove-row' title='移除此提問單' data-qid='"+ q_list[i].question_id+"'><i   class='far fa-trash-alt'></i></a>");
	<?endif;?>
	//$("#q_area").append("</li>");
}
//$("#q_area").append("</ul>")


var delBtns = document.getElementsByClassName("remove-row");
for (var i = 0; i < delBtns.length; i++) {
	delBtns[i].addEventListener('click', removeItem, false);
}

function showQCount(){

	if (q_list.length<1){
		$(".form-actions").html("");
		$("#q_area .label-info").html("目前沒有加入任何提問單喔!");
	}
	else {
		$("#q_area .label-info").html("提問單號(共"+ q_list.length +"筆)");
	}

}

function removeItem(event){
	var item = event.target.closest("a");
	var q_id_remove = $(item).attr("data-qid");
	//console.log(event.target);
	//console.log(q_id_remove);
	//return;
	//remove_from_batch

	let url = "../remove_from_batch/" + q_id_remove;
	$.ajax({
		type: "POST",
		url: url,
		dataType: "json",
		contentType: 'application/json; charset=UTF-8',
	}).done(function(result) {
		console.log( "Request done: " + JSON.stringify(result) );

		if (result.status=="success")
		{
			$("#q_area .list-group-item:contains('"+ q_id_remove +"')").remove();
			q_list = q_list.filter((q) => {
  				return q.question_id !== q_id_remove;
			});

			showQCount();

		}

		//location.reload();

	})
	.fail(function( jqXHR, textStatus ) {
		console.log( "Request failed: " + textStatus );
	})
	.always(function() {
		console.log("complete")
	});
}

function confirm_before_submit()
{
	var batch_id = $("#batch_id").val();
	var new_type = $( "select[name='new_type']").find(":selected").val();
	var new_type_text = $( "select[name='new_type']").find(":selected").text();
	var post_content = $("#content").val().replace(/(?:\r\n|\r|\n)/g, '<br>');

	var q_text = q_list.map(function(q){
			return q.question_id;
	}).reduce(function(a,b){
		return a + "," + b;
	})


	//console.log(q_text);
	$('#comfirmModalLabel').text("送出前請確認以下內容");
	$('#comfirmModal .modal-body').html(
		`
			<div><b>回應單號:</b>${q_text}</div>
			<div><b>變更問題類型:</b>${new_type_text}</div>
			<div><b>回應內容:</b>${post_content}</div>
		`
	);

	$(".alert-error").hide();
	$(".alert-error").text();
	$('#comfirmModal').modal('show');



}


//回覆結案
function reply_n_close(mode){
//`batch_id=${batch_id}&new_type=${new_type}&post_content=${post_content}`
//mode 4 = 立即結案 7 = 回覆並預約結案 2=只回覆
var batch_id = $("#batch_id").val();
var new_type = $( "select[name='new_type']").find(":selected").val();
var post_content = $("#content").val().replace(/(?:\r\n|\r|\n)/g, '<br>');
let url = "../batch_reply_json";
$(".alert-error").hide();
$(".alert-error").text();
//console.log( "reply_n_close: " + post_content );
//return;
$.ajax({
			type: "POST",
			url: url,
			dataType: "json",
			data: `batch_id=${batch_id}&new_type=${new_type}&post_content=${post_content}&mode=${mode}`,
		}).done(function(result) {
			console.log(result);
			if (result.status=="success")
			{
				$('#comfirmModal').modal('hide');
				location.reload();
			}else {
				$(".alert-error").text("失敗:" + result.message);
				$(".alert-error").show();
			}
			//failure

		})
		.fail(function( jqXHR, textStatus ) {
			console.log( "Request failed: " + textStatus );
		})
		.always(function() {
			console.log("complete")
		});
}
</script>
<?else:?>
沒有這個項目
<? endif;?>
