<?
if($event->num_rows() > 0)
{
	$event_row = $event->row();
?>
<div id="content">
	<div class="contenter">
	<ul class="icon">
	    <li><a href="#" title="LOGO"><img src="<?=$game_url?>p/image/content-logo.png" /></a></li>
		<div style="color:#fff;"><?=$event_row->name?></div>
	    <ul style="color:#fff;">
			<li>按讚加入粉絲團</li>
			<li>輸入E-MAIL或手機號碼</li>
			<li>輸入登入密碼</li>
			<li style="width:90%">立即預約</li>
	    </ul>
	    <ul style="color:#fff;">
			<li>&nbsp;</li>
			<li><input name="account" class="required" maxlength="128" type="text" size="18"" /></li>
			<li><input name="pwd" type="password"  class="required" id="txtbox" maxlength="32"  size="20" AUTOCOMPLETE='OFF'/></li>
			<li>查詢序號</li>
	    </ul>
	</ul>
	</div>
</div>
<?
}else{
?>
<div>沒有活動</div>
<?
}
?>