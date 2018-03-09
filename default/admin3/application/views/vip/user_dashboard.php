<? if ($vip):?>

<fieldset>
  <legend>角色基本資料</legend>
  <table class="table table-bordered" style="width:auto;">
    <tr>
      <th>帳號 UID</th>
      <td><?=$vip->uid?></td>
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
      <td></td>
      <th>加入Line時間</th>
      <td></td>
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
  </table>
</fieldset>

<fieldset>
  <legend>服務紀錄</legend>
</fieldset>

<fieldset>
  <legend>重點對話節錄</legend>
</fieldset>

  <?else: echo '<div class="none">查無資料</div>'; ?>
<?endif;?>
