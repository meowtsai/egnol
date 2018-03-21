
<fieldset>
  <legend>VIP服務紀錄</legend>

  <div class="well">
    <select name="service_type" id="service_type">
      <option value="1" >專屬服務</option>
      <option value="2" >對話節錄</option>
    </select>

    <input type="text" name="start_date" id="start_date"  class="date required"  style="width:120px"> 至
    <input type="text" name="end_date" id="end_date"  style="width:120px" placeholder="現在">

    <button type="button" class="btn btn-success btn-sm" onclick="get_vip_requests_log('<?=$game_id?>')">查詢</button>
  </div>

    <table class="table table-bordered" id="report-table">
      <thead>
        <tr>
          <th scope="col">#</th>
          <th scope="col">角色序號</th>
          <th scope="col">角色</th>
          <th scope="col">類別</th>
          <th scope="col">內容</th>
          <th scope="col">時間</th>
          <th scope="col">專員</th>
        </tr>
      </thead>
      <tbody>

      </tbody>
    </table>


<a href='#' onclick='downloadCSV({ filename: "requests-data.csv" });' id="btn-download">下載檔案</a>
</fieldset>
