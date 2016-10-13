<div class="site_container">
    <div class="grid_container">
        <h1>綁定正式帳號</h1>
        <div id="line"></div>
        <form method="post" id="bind_form" action="<?=$api_url?>api2/ui_bind_account_json?site=<?=$site?>">
            <div class="inputbox"><input type="text" name="mobile" id="mobile" class="form__input" placeholder="請輸入手機號碼" required /></div>
            <div class="inputbox"><input type="text" name="email" id="account" class="form__input" placeholder="請輸入Email" required /></div>
            <div class="clear"></div>
            <p class="text_01">E-mail與手機號碼至少需填寫其中一個</p>
            <div class="inputbox"><input type="password" id="pwd" name="pwd" maxlength="35" class="form__input" placeholder="請輸入密碼(至少六碼以上)" required /></div>
            <div class="inputbox"><input type="password" id="pwd2" name="pwd2" maxlength="35" class="form__input" placeholder="確認密碼" required /></div>
            <div class="clear"></div>
            <p class="text_02"><input type="checkbox" name="chk" class="required" id="check">我已閱讀並同意<a href="<?=$api_url?>api2/ui_service_agreement?site=<?=$site?>">會員服務條款</a>、<a href="<?=$api_url?>api2/ui_member_agreement?site=<?=$site?>">個資同意書</a>與<a href="<?=$api_url?>api2/ui_privacy_agreement?site=<?=$site?>">隱私政策</a></p>
            <input name="doLogin" type="submit" id="doSubmit" value="" style="display:none;" />
            <button type="button" id="submit-btn" class="btn btn-primary btn-block btn-large" onclick="javascript:$('#doSubmit').trigger('click');">確認綁定</button>
        </form>
        <div id="line"></div>
        <div id="longe"><img src="<?=$api_url?>/p/image/api2/longe.png" width="66" height="30"></div>
    </div>
</div>