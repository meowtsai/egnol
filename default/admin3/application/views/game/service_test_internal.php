<form method="get" name="test_service_form" id="test_service_form" class="validation" action="http://test-payment.longeplay.com.tw/service_quick">
	<fieldset>

    <input type="hidden" name="site" value="long_e">
    <select name="game_id" id="game_id"  style="width:200px;">
    <? $i=1; foreach($query->result() as $row):?>
    <option value="<?=$row->game_id?>"><?=$row->game_id?> - <?=$row->name?>(<?=$row->is_active ?>)</option>
    <? endforeach;?>
    </select>

    <label>帳號唯一id(partner_uid) </label>
		<input type="text" name="partner_uid" id="partner_uid"  value="15501641" class="required" style="width:150px">

    <label>角色唯一id (in_game_id)</label>
		<input type="text" name="in_game_id" id="in_game_id" value="119178" class="required" style="width:150px">

    <label>伺服器名稱 (server_name)</label>
		<input type="text" name="server_name" id="server_name" value="黎明誓約" class="required" style="width:150px">


    <label>角色名稱 (character_name)</label>
    <input type="text" name="character_name" id="character_name" value="喵捲" class="required" style="width:150px">

    <label>角色等級 (level)</label>
    <input type="text" name="level" id="level" value="44" class="required" style="width:150px">

    <label>玩家設備 (usr_device)</label>
    <input type="text" name="usr_device" id="usr_device" value="Windows" class="required" style="width:150px">


    <label>設備os版本 (os_ver)</label>
    <input type="text" name="os_ver" id="os_ver" value="6.1.7601" class="required" style="width:150px">

    <label>app版本 (app_ver)</label>
    <input type="text" name="app_ver" id="app_ver" value="1.0.0_1" class="required" style="width:150px">

    <label>時區 (time_zone)</label>
    <input type="text" name="time_zone"  id="time_zone" value=""  style="width:150px">

    <label>網路 (network)</label>
    <input type="text" name="network" id="network" value="-1"  style="width:150px">

    <label>key值 (key)</label>
    <input type="text" name="mykey" id="mykey" value="gmzzhmt"  style="width:150px">

    <input type="text" name="key"  id="key" value=""  style="width:150px">



   		<div class="form-actions">
   			<button name="submitForm"  id="submitForm" type="submit" class="btn"><i class="icon-ok"></i> 模擬遊戲中開啟回報</button>
   		</div>

	</fieldset>
</form>
