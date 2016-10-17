<div class="site_container">
    <div class="grid_container">
        <h1>忘記密碼</h1>
        <div id="line"></div>
        <form id="forgot_form" action="<?=$api_url?>api2/ui_reset_password_json?site=<?=$site?>" method="post">
            <div class="inputbox2"><input type="text" name="account" class="form__input" placeholder="請輸入Email或手機號碼" required /></div>
            <div class="clear"></div>
            <div style="height:10px;"></div>
            <div id="text_area">
            <p>請輸入註冊的手機號碼或E-mail。<br>系統將會發送密碼重置郵件至信箱或手機簡訊中。
    </p>
            </div>
            <br>
            <input name="doLogin" type="submit" id="doSubmit" value="" style="display:none;" />
            <button id="submit-btn" type="button" class="btn btn-primary btn-block btn-large button_submit" onclick="javascript:$('#doSubmit').trigger('click');">確認送出</button>          <div style="height:10px;"></div>
            <button id="cancel-btn" type="button" class="btn btn-primary btn-block btn-large button_submit" onclick="javascript:location.href='/api2/ui_login?site=<?=$site?>';">取消</button>
        </form>
        <div id="line"></div>
        <div id="longe"><img src="<?=$api_url?>/p/image/api2/longe.png" width="66" height="30"></div>
    </div>
</div>