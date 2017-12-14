
<div id="content-login">
  <form action="https://game.longeplay.com.tw/user_complaint/report"  method="post">

  <div class="form-group">
    <label>遊戲</label>
    <select class="form-control" id="selectGameId" name="game_id">
      <option value="h35naxx1hmt">光明之戰</option>
    </select>
  </div>
  <div class="form-group">
    <label>伺服器</label>
    <select class="form-control" id="selectServer" name="server_name">
      <option value="黎明誓約">黎明誓約</option>
    </select>
  </div>
  <div class="form-group">
    <label>舉報人</label>
    <input type="text" value="15501641" name="reporter_uid" />
    <input type="text" value="119178" name="reporter_char_id" />
    <input type="text" value="喵捲" name="reporter_name" />


  </div>
  <div class="form-group">
    <label>被舉報人</label>
    <input type="text" value="20008" name="flagged_player_uid" />
    <input type="text" value="13982409" name="flagged_player_char_id" />
    <input type="text" value="狼者知乎" name="flagged_player_name" />

  </div>

  <div class="form-check">
  <label class="form-check-label">
    <input class="form-check-input" type="radio" name="category" id="category1" value="1" checked>
    言行不雅
  </label>
</div>
<div class="form-check">
  <label class="form-check-label">
    <input class="form-check-input" type="radio" name="category" id="category2" value="2">
    暱稱不雅
  </label>
</div>
<div class="form-check">
  <label class="form-check-label">
    <input class="form-check-input" type="radio" name="category" id="category3" value="3">
    使用外掛
  </label>
</div>
<div class="form-check">
  <label class="form-check-label">
    <input class="form-check-input" type="radio" name="category" id="category4" value="4">
    利用bug
  </label>
</div>
<div class="form-check">
  <label class="form-check-label">
    <input class="form-check-input" type="radio" name="category" id="category5" value="5">
    線下交易
  </label>
</div>
<div class="form-check">
  <label class="form-check-label">
    <input class="form-check-input" type="radio" name="category" id="category6" value="6">
    欺詐行為
  </label>
</div>
<div class="form-check">
  <label class="form-check-label">
    <input class="form-check-input" type="radio" name="category" id="category7" value="7">
    其他
  </label>
    <input type="text" value="" name="reason" />


</div>

<div class="form-group">
  <label>token</label>
  <input type="text" value="1eaf518107cabc00831a8dbdfd5f8b00" name="token" />

</div>

  <button type="submit" class="btn btn-primary">Submit</button>
</form>
</div>
