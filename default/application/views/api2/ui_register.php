<div class="site_container">
    <div class="grid_container">
        <h1>用戶註冊</h1>
        <div id="line"></div>
        <form id="register_form" method="post" action="<?=$api_url?>api2/ui_register_json?site=<?=$site?>">
            <div class="inputbox"><input type="text" name="mobile" id="mobile" class="form__input mobile isMobile" placeholder="請輸入手機號碼"/></div>
            <div class="inputbox"><input type="text" name="email" id="email" class="form__input email" placeholder="請輸入Email"/></div>
            <div class="clear"></div>
            <p class="text_01">E-mail與手機號碼至少需填寫其中一個</p>
            <div class="inputbox"><input type="password" id="pwd" name="pwd" class="form__input" placeholder="請輸入密碼(至少六碼以上)" required /></div>
            <div class="inputbox"><input type="password" id="pwd2" name="pwd2" class="form__input" placeholder="確認密碼" required /></div>
            <div class="inputbox"><input type="text" name="captcha" class="form__input" placeholder="請輸入驗證碼" required /></div>
            <div id="captcha_area" style="display:inline-block;vertical-align:middle;width:49%;text-align: center;"></div>
            <div class="clear"></div>
            <p class="text_02"><input type="checkbox" name="chk" class="required" id="check">我已閱讀並同意<a href="<?=$api_url?>api2/ui_service_agreement?site=<?=$site?>">會員服務條款</a>、<a href="<?=$api_url?>api2/ui_member_agreement?site=<?=$site?>">個資同意書</a>與<a href="<?=$api_url?>api2/ui_privacy_agreement?site=<?=$site?>">隱私政策</a></p>
            <input name="doLogin" type="submit" id="doSubmit" value="" class="button_submit" style="display:none;" />
            <button type="button" class="btn btn-primary btn-block btn-large" onclick="javascript:$('#doSubmit').trigger('click')">立即註冊</button>
             <div style="height:10px;"></div>
            <button id="cancel-btn" type="button" class="btn btn-primary btn-block btn-large button_submit" onclick="javascript:location.href='/api2/ui_login?site=<?=$site?>';">取消</button>
        </form>
        <div id="line"></div>
        <div id="longe"><img src="<?=$api_url?>/p/image/api2/longe.png" width="66" height="30"></div>
    </div>
</div>