
<fieldset>
  <legend>流失VIP用戶列表</legend>

  <div class="well">
    <select name="date_column" id="date_column">
      <option value="latest_topup_date" >最後付費</option>
      <option value="last_login" >最後登入</option>
      <option value="inactive_confirm_date" >確認流失</option>
    </select>

    <input type="text" name="start_date" id="start_date"  class="date required"  style="width:120px"> 至
    <input type="text" name="end_date" id="end_date"  style="width:120px" placeholder="現在">

    <button type="button" class="btn btn-success btn-sm" onclick="get_inactive_users_report('<?=$game_id?>')">查詢</button>
  </div>
  <div class="alert alert-info">

  </div>

    <table class="table table-bordered" id="report-table">
      <thead>
        <tr>
          <th scope="col" style="width:40px" nowrap="nowrap">主帳號</th>
          <th scope="col" style="width:40px" nowrap="nowrap">角色序號</th>
          <th scope="col" style="width:70px" nowrap="nowrap">角色</th>
          <th scope="col" style="width:70px" nowrap="nowrap">vip級別</th>
          <th scope="col" style="width:70px" nowrap="nowrap">最後付費</th>
          <th scope="col" style="width:70px" nowrap="nowrap">最後登入</th>
          <th scope="col" style="width:70px" nowrap="nowrap">確認流失</th>

        </tr>
      </thead>
      <tbody>

      </tbody>
    </table>


<a href='#' onclick='downloadCSV({ filename: "inactive-users.csv", rpt_type: "inactive_users" });' id="btn-download">下載檔案</a>
</fieldset>
