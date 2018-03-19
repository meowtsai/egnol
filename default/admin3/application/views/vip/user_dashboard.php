
<?
$service_request = $this->config->item('h35vip_service_request');
$service_feedback = $this->config->item('h35vip_service_feedback');

if ($vip):?>

<fieldset>
  <legend>角色基本資料</legend>

  <table class="table table-bordered" style="width:auto;">
    <tr>
      <th>帳號 UID</th>
      <td>
        <?=$vip->uid?>
        <input type="hidden" name="game_id" value="<?=$vip->site?>" id="game_id">
        <input type="hidden" name="vip_uid" value="<?=$vip->uid?>" id="vip_uid">
        <input type="hidden" name="role_id" value="<?=$vip->char_in_game_id?>" id="role_id">
      </td>
      <th>伺服器</th>
      <td><?=$vip->server_name?></td>
    </tr>
    <tr>
      <th>角色ID</th>
      <td><?=$vip->char_in_game_id?></td>
      <th>角色名稱</th>
      <td><?=$vip->char_name?></td>
    </tr>
    <tr>
      <th>Line ID</th>
      <td>
        <label name="lbl_line_id" id="lbl_line_id"><?=$vip->line_id?></label>
        <input type="text" name="line_id" value="<?=$vip->line_id?>" id="line_id">

      </td>
      <th>加入Line時間</th>
      <td>
        <label name="lbl_line_date" id="lbl_line_date"><?=$vip->line_date?></label>
        <input type="text" name="line_date" value="<?=$vip->line_date?>" id="line_date">

      </td>
    </tr>
    <tr>
      <th>手機</th>
      <td></td>
      <th>VIP 分層</th>
      <td><?=$vip->vip_ranking?></td>
    </tr>
    <tr>
      <th>居住地區</th>
      <td><?=$vip->country?></td>
      <th>性別</th>
      <td></td>
    </tr>
    <tr>
      <th>操作</th>
      <td colspan="3">
        <button name="cmdAction" id="cmdAction" onclick="update_vip_info()">送出</button>
        <div id="div-alert">

        </div>
      </td>

    </tr>


  </table>

  <a href="#" id="cmd_edit" onclick="editMode()"></a>
</fieldset>

<fieldset>
  <legend><a id='a_request'>服務紀錄</a></legend>

  <ul class="nav nav-tabs"  id="myTab1">
    <li  class="active"><a href="#divQuery" data-toggle="tab">查詢</a></li>
    <li><a href="#divAdd" data-toggle="tab">新增</a></li>
  </ul>

  <div class="tab-content">
    <div class="tab-pane active" id="divQuery">
      <div class="well">
        <select name="serviceOptionSelectorQuery" id="serviceOptionSelectorQuery">
          <option value="">快選</option>
          <? foreach($service_request as $key => $request):?>
          <option value="<?=$key?>" ><?=$request?></option>
          <? endforeach;?>
        </select>
        <input type="text" id="inputServiceInfoQuery" name="inputServiceInfoQuery" placeholder="搜尋備註">

        <select name="adminSelectorQuery" id="adminSelectorQuery">
          <option value="">--專員--</option>
          <? foreach($admins->result() as $row):?>
          <option value="<?=$row->admin_uid?>" ><?=$row->name?></option>
          <? endforeach;?>
        </select>

        時間
    		<input type="text" name="start_date" id="start_date"  class="date required"  style="width:120px"> 至
        <input type="text" name="end_date" id="end_date" style="width:120px" placeholder="現在">


        <button type="button" class="btn btn-success btn-sm" onclick="get_vip_requests_log('1',1)">查詢</button>
      </div>
    </div>
    <div class="tab-pane" id="divAdd">
      <div class="well">
      <select name="serviceOptionSelector" id="serviceOptionSelector">
        <option value="">快選</option>
        <? foreach($service_request as $key => $request):?>
        <option value="<?=$key?>" ><?=$request?></option>
        <? endforeach;?>
      </select>
      <input type="text" id="inputServiceInfo" name="inputServiceInfo" placeholder="備註(選填)" maxlength="120">
      <button type="button" class="btn btn-primary btn-sm" onclick="add_vip_request('1')">新增</button>
      </div>
    </div>
  </div>
    <table class="table table-bordered" id="request-log">
      <thead>
        <tr>
          <th scope="col">#</th>
          <th scope="col">時間</th>
          <th scope="col">類別</th>
          <th scope="col">備註</th>
          <th scope="col">專員</th>
        </tr>
      </thead>
      <tbody>




      </tbody>
    </table>
    <div class="pagination">
      <ul id="service_pages">
      </ul>
    </div>

</fieldset>


<fieldset>
  <legend><a id='a_feedback'>重點對話節錄</a></legend>

  <ul class="nav nav-tabs"  id="myTab2">
    <li class="active"><a href="#divFeedbackQuery" data-toggle="tab">查詢</a></li>
    <li><a href="#divFeedbackAdd" data-toggle="tab">新增</a></li>
  </ul>

  <div class="tab-content">
    <div class="tab-pane active" id="divFeedbackQuery">
      <div class="well">
        <select name="serviceFeedbackOptionSelectorQuery" id="serviceFeedbackOptionSelectorQuery">
          <option value="">快選</option>
          <? foreach($service_feedback as $key => $feedback):?>
          <option value="<?=$key?>" ><?=$feedback?></option>
          <? endforeach;?>
        </select>
        <input type="text" id="inputServiceFeedbackInfoQuery" name="inputServiceFeedbackInfoQuery" placeholder="搜尋備註">
        <select name="adminFeedbackSelectorQuery" id="adminFeedbackSelectorQuery">
          <option value="">--專員--</option>
          <? foreach($admins->result() as $row):?>
          <option value="<?=$row->admin_uid?>" ><?=$row->name?></option>
          <? endforeach;?>
        </select>

        <input type="text" name="start_date2" id="start_date2"  class="date required"  style="width:120px"> 至
        <input type="text" name="end_date2" id="end_date2"  style="width:120px" placeholder="現在">

        <button type="button" class="btn btn-success btn-sm" onclick="get_vip_requests_log('2',1)">查詢</button>
      </div>
    </div>
    <div class="tab-pane" id="divFeedbackAdd">
      <div class="well">
      <select name="serviceFeedbackOptionSelector" id="serviceFeedbackOptionSelector">
        <option value="">快選</option>
        <? foreach($service_feedback as $key => $feedback):?>
        <option value="<?=$key?>" ><?=$feedback?></option>
        <? endforeach;?>
      </select>
      <input type="text" id="inputServiceFeedbackInfo" name="inputServiceFeedbackInfo" placeholder="對話摘要" style="width:550px" maxlength="120">
      <button type="button" class="btn btn-primary btn-sm" onclick="add_vip_request('2')">新增</button>
      </div>
    </div>
  </div>
    <table class="table table-bordered" id="feedback-log">
      <thead>
        <tr>
          <th scope="col">#</th>
          <th scope="col">時間</th>
          <th scope="col">類別</th>
          <th scope="col">內容</th>
          <th scope="col">專員</th>
        </tr>
      </thead>
      <tbody>

      </tbody>
    </table>
    <div class="pagination">
      <ul id="feedback_pages">
      </ul>
    </div>

</fieldset>

  <?else: echo '<div class="none">查無資料</div>'; ?>
<?endif;?>
<script type="text/javascript">
$('#myTab1 a').click(function (e) {
  e.preventDefault();
  $(this).tab('show');
  })
</script>
