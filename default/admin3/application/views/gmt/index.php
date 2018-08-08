<fieldset>
	<legend>指令視窗</legend>

	<form id="form1" method="POST">

	<div>
		<div class="control-group">
			指令類型：<select id="action_type" name="action_type" class="span2 required">
		    <option value="">--</option>
		    <? foreach($gmt_action as $key => $type):?>
		    <option value="<?=$type["code"]?>"><?=$type["title"]?></option>
		    <? endforeach;?>
		  </select>
			<font color="gray" size="small">* 請選擇指令類型並且填入適當的值進行查詢或執行指令。</font>
		</div>
		<br>

	  <div id="input_area">

	  </div>


		<table id="tbl_cart" class="table table-striped table-bordered" style="width:auto;">
			<tr>
					<th colspan="3">附件清單</th>
			</tr>
			<tr>
					<th>類別</th><th>物品</th><th>數量</th>
			</tr>

		</table>

	</div>

	<hr />


			<div class="form-actions">
	  		<button type="submit" class="btn btn-primary">確認送出</button>
	  	</div>
	</form>

</fieldset>





<hr />

<fieldset>
	<legend>本次指令執行結果</legend>
	<div id="result_window">

	</div>
	<font color="gray" size="small">* status=0 通常代表執行成功，其他status 則會出現錯誤訊息(errmsg)。</font>
</fieldset>


<hr />
<fieldset>
	<legend>歷史紀錄</legend>
	<div id="log_window">

	</div>

</fieldset>



<!-- {
struct

{
    E_ACTION action = E_GM_ACTION_SEND_MAIL;  //200
    int32 touid;            //收件人ID
    string title;           //邮件标题
    string msg;             //邮件内容

    MailAttach
    {
        int32 type;         //物品类型
        int32 id;           //物品id
        int32 count;        //物品个数
    }
    MailAttach attach[];    //邮件附件，格式：[{"type":1,"id":1,"count":1},{"type":2,"id":2,"count":2}]
    string expireddate;     //过期日期，格式："2018-06-29 00:00:00"
}

{
    int32 status;
    int32 mailid;    //邮件id
}-->


<SCRIPT LANGUAGE="JavaScript">

function setMultipleAttributes(el, attributes) {
   for (var attr in attributes)
   {

     if (attr!=="options")
     {
       el.setAttribute(attr, attributes[attr]);
     }
     else {

       $.each(attributes[attr], function (i, item) {

            var option = document.createElement("option");
            option.text = item.text;
            option.value = item.value;
            el.appendChild(option);
        });
     }
   }


}

function getToolSet(act_code){
  var toolSet = [];
  switch (act_code) {
    case 100: //增加金幣
    case 101: //扣除金幣
    //console.log("hi");
      toolSet = [{"param":"uid","type":"input"},{"param":"rid","type":"select"},{"param":"num","type":"input"}];
      break;
    case 150: //增加普通物品
    //console.log("hi");
      toolSet = [{"param":"uid","type":"input"},{"param":"bid","type":"input"},{"param":"num","type":"input"}];
      break;
    case 151: //扣除普通物品
    //console.log("hi");
      toolSet = [{"param":"uid","type":"input"},{"param":"cid","type":"input"}];
      break;
    case 200: //发送系统邮件
      toolSet = [{"param":"touid","type":"input"},{"param":"title","type":"input"},{"param":"msg","type":"input"},{"param":"expireddate","type":"input"},{"param":"MailAttach","type":"attach"}];
      break;

    case 300: //禁止玩家登陸
      //console.log("hi");
        toolSet = [{"param":"uid","type":"input"},{"param":"lockseconds","type":"input"},{"param":"reason","type":"input"}];
        break;

    case 301: //解除玩家登陸
    case 302: //查詢玩家登陸
    //console.log("hi");
      toolSet = [{"param":"uid","type":"input"}];
      break;
    case 350: //禁止設備登陸
    case 351: //解除設備禁止登陸
    case 352: //查詢設備登陸狀態
    //console.log("hi");
      toolSet = [{"param":"uid","type":"input"}];
      break;
    case 450: //獲取小鎮訊息
    //console.log("hi");
      toolSet = [{"param":"townid","type":"input"}];
      break;
    case 500: //獲取玩家訊息
    //console.log("hi");
      toolSet = [{"param":"uid","type":"input"}];
      break;
    default:

  }
  return toolSet;
}

//發送物品的購物車
//MailAttach attach[];    //邮件附件，格式：[{"type":1,"id":1,"count":1},{"type":2,"id":2,"count":2}]
var my_cart = [];
$("#tbl_cart").hide();

  var input_list = {
    "uid":{
      "type":"text",
      "id":"input_uid",
      "placeholder":"請輸入玩家數字ID",
    },
		"townid":{
      "type":"text",
      "id":"input_townid",
      "placeholder":"請輸入小鎮數字ID",
    },
    "rid":{
      "id":"sel_rid",
      "options":[{'value':'1', 'text':'1-馬蹄金'},{'value':'2', 'text':'2-鈔票'}]
    },
    "num":{
      "type":"text",
      "id":"input_num",
      "placeholder":"數量",
    },
    "bid":{
      "type":"text",
      "id":"input_bid",
      "placeholder":"物品 ItemID",
    },
    "cid":{
      "type":"text",
      "id":"input_cid",
      "placeholder":"物品的唯一client id",
    },
    "lockseconds":{
      "type":"text",
      "id":"input_lockseconds",
      "placeholder":"禁止登錄秒數",
    },
    "reason":{
      "type":"text",
      "id":"input_reason",
      "placeholder":"禁止登錄原因",
    },
    "udid":{
      "type":"text",
      "id":"input_udid",
      "placeholder":"設備號",
    },
    "touid":{
      "type":"text",
      "id":"input_udid",
      "placeholder":"收件人uid",
    },
    "expireddate":{
      "type":"text",
      "id":"input_expireddate",
      "placeholder":"過期日期 ，格式：2018-06-29 00:00:00",
    },
    "title":{
      "type":"text",
      "id":"input_title",
      "placeholder":"郵件標題",
    },
    "msg":{
      "type":"text",
      "id":"input_msg",
      "placeholder":"郵件內容",
    },
		"MailAttach":{
      "type":"array",
      "id":"input_attach",
      "placeholder":"附件列表",
    },

  } ;


	class attach{
	  constructor(item_type, item_id,item_count) {
	    this.type = item_type;
	    this.id = item_id;
			this.count = item_count;

	  }
	}

$('#action_type').change(function() {
  // set the window's location property to the value of the option the user has selected
  //window.location = $(this).val();

	$("#tbl_cart").hide();
	$("#result_window").text('');
  var action_code = parseInt( $( this ).val());
  console.log(action_code);
  $("#input_area").html('');
  var toolSet = getToolSet(action_code);


  for (var i = 0; i < toolSet.length; i++) {
		if (toolSet[i]["param"] ==="MailAttach")
		{
			//附件必需要帶出可選的下拉
			var form_object = document.createElement("select");
			form_object.setAttribute("id","item_type");
			var option_empty = document.createElement("option");
			option_empty.text = "=選擇物品分類=";
			option_empty.value = "";
			form_object.appendChild(option_empty);
       $.each(ma71ItemCategoryData, function (i, item) {
            var option = document.createElement("option");
            option.text = item.name + "("+ item.eng +")";
            option.value = item.id;
            form_object.appendChild(option);
        });
				$("#input_area").append("<br />");
				$("#input_area").append(form_object);

				var form_object2 = document.createElement("select");
				form_object2.setAttribute("id","item_id");
				var option_empty = document.createElement("option");
				option_empty.text = "=選擇物品=";
				option_empty.value = "";
				form_object2.appendChild(option_empty);

				$('#item_type').change(function() {
					console.log("change detected");
					try {
						//{itemId:196, itemName:'共用物品架',ItemNameEng:'Shared Shelving'},
						$("#item_id").children('option:not(:first)').remove();
							var item_type = parseInt( $( this ).val());
							//console.log("select item type=", item_type);
							$.each(ma71ItemData[item_type], function (i, item) {
									 var option = document.createElement("option");
									 option.text = item.itemName + "("+ item.ItemNameEng +")";
									 option.value = item.itemId;
									 $("#item_id").append(option);
							 });

					} catch (e) {
						console.log("Not select any type" );
					}


				});

					$("#input_area").append("<br />");
					$("#input_area").append(form_object2);

					var form_object3 = document.createElement("select");

					form_object3.setAttribute("id","item_count");
					var option_empty = document.createElement("option");
					option_empty.text = "=發送數量=";
					option_empty.value = "";
					form_object3.appendChild(option_empty);
					for (var i = 1; i < 6; i++) {
						var option = document.createElement("option");
						option.text = i;
						option.value = i;
						form_object3.appendChild(option);
					}

					$("#input_area").append("<br />");
					$("#input_area").append(form_object3);


					var form_object4 = document.createElement("button");
					form_object4.setAttribute("type","button");
					form_object4.setAttribute("id","item_add");
					form_object4.innerHTML = '加入 +';


					$("#input_area").append(form_object4);

					form_object4.addEventListener("click", function(){
						//{"type":1,"id":1,"count":1}
						var item_info = {};
						var itemtype = parseInt($("#item_type").val());
						var itemid = parseInt($("#item_id").val());
						var itemcount = parseInt($("#item_count").val());
						item_info = {"type":itemtype,"id":itemid,"count":itemcount};
						my_cart.push(item_info);
						//console.log(my_cart);

						var type_text  = $('#item_type :selected').text();
						var id_text  = $('#item_id :selected').text();


						$("#tbl_cart").append('<tr><td>'+ type_text +'</td><td>'+ id_text +'</td><td>'+ itemcount +'</td></tr>');
						$("#tbl_cart").show();

					});













		}
		else {
			var form_object = document.createElement(toolSet[i]["type"]);
      setMultipleAttributes(form_object, input_list[toolSet[i]["param"]]);
      $("#input_area").append(form_object);
		}
  }

});





$( "#form1" ).submit(function( event ) {
  //alert( $("#input_townid").val() );

  event.preventDefault();
  var action_code = parseInt($('#action_type').val());
  //console.log(action_code);
  var data = {"action": action_code};
  var toolSet = getToolSet(action_code);
//console.log(toolSet);
  for (var i = 0; i < toolSet.length; i++) {

    var param = toolSet[i]["param"];
    //console.log(param);
    var paramValue ;
    switch (param) {
      case "reason":
      case "title":
      case "msg":
      case "expireddate":
          paramValue = $("#" + input_list[param].id ).val();
        break;
			case "MailAttach":
				console.log("MailAttach",my_cart);
				paramValue = my_cart;
				break;

      default:
        paramValue = parseInt($("#" + input_list[param].id ).val());
				if (isNaN(paramValue))
				{
					alert(param + '請填入數字喔!');
					return;
					break;
				}

        break;
    }
			data = {...data, [param]:paramValue };




    }

//console.log(JSON.stringify(data));
  $.ajax({
      type: "POST",
      url: "./gmt/ma71tw_action",
      data: JSON.stringify(data),
    }).done(function(result) {
      //console.log(result);
			get_logs();
      $("#result_window").text(result);

    });


});



function get_logs(){
	$("#log_window").html('');
	$.ajax({
			type: "GET",
			url: "./log/get_gm_logs",
		}).done(function(result) {
			//console.log(result);
			//$("#func_bar").text(JSON.stringify(result));

			for (var i = 0; i < result.length; i++) {
				if (i === 10) { break; }
				var act_code = result[i].action;
				var action_text = $("#action_type option[value="+ act_code +"]").text();
				$("#log_window").append(`<div> ${result[i].name} 在 ${result[i].create_time} 執行 <font color='green'>[ ${action_text} ]</font> 指令,紀錄如下 <pre> ${result[i].desc}</pre></div>`);
			}


		});
}

get_logs();
</SCRIPT>

<!--ma71ItemCategoryData  {id:17, name:'禮包開出的物品',eng:'Wanted Items'}, {"errmsg":"inner error, please check error code","status":5} -->
