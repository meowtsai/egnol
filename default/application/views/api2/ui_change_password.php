<div class="site_container">
    <div class="grid_container">
	<h1>修改密碼</h1>
    <div id="line"></div>
        <form id="change_form" action="<?=$api_url?>api2/ui_change_password_json?site=<?=$site?>" method="post">
            <div class="inputbox2"><input type="password" id="old" name="old" maxlength="18" minlength="6" size="33" class="form__input" placeholder="舊密碼" required /></div>
            <div class="inputbox2"><input type="password" id="pwd" name="pwd" maxlength="18" minlength="6" size="33" class="form__input" placeholder="新密碼" required /></div>
            <div class="inputbox2"><input type="password" id="pwd2" name="pwd2" maxlength="18" minlength="6" size="33" class="form__input" placeholder="新密碼確認" required /></div>
            <div class="clear"></div>
            <br>
            <input name="doLogin" type="submit" id="doSubmit" value="" style="display:none;" />
            <button id="submit-btn" type="submit" class="btn btn-primary btn-block btn-large button_submit"  onclick="javascript:$('#doSubmit').trigger('click');">確認送出</button>
            <div style="height:10px;"></div>
            <button id="cancel-btn" type="submit" class="btn btn-primary btn-block btn-large button_submit" onclick="javascript:location.href='/api2/ui_login?site=<?=$site?>';">取消</button>
        </form>
        <div id="line"></div>
        <div id="longe"><img src="<?=$api_url?>/p/image/api2/longe.png" width="66" height="30"></div>
    </div>
</div>