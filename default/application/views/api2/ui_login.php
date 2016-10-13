<div class="site_container">
    <div class="grid_container">
        <div id="left">
	        <h1><img src="<?=$api_url?>/p/image/api2/img.png" class="img"></h1>
            <div id="footer2">
                <div id="line"></div>
                <div id="longe2"><img src="<?=$api_url?>/p/image/api2/longe.png" width="66" height="30"></div>
            </div>
        </div>
        <div id="line2"></div>
        <div id="right">
            <div id="line_tt"></div>
            <form id="login_form" method="post" action="<?=$api_url?>api2/ui_login_json?site=<?=$site?>">
                <input type="hidden" name="partner" value="<?=$partner?>">
                <input type="hidden" name="game_key" value="<?=$game_key?>">
                <input type="text" name="account" id="name" maxlength="128" class="form__input" placeholder="請輸入手機號碼或E-mail" required />
                <input type="password" name="pwd" id="name" maxlength="18" class="form__input" placeholder="請輸入密碼" required />
                
				<input name="doLogin" type="submit" id="doSubmit" value="" style="display:none;" />
                <button type="submit" id="sign-in-btn" class="btn btn-primary btn-block btn-large" onclick="javascript:$('#doSubmit').trigger('click')">登入</button>
            </form>
            <div style="height:10px;"></div>
            <p class="text_05">
                <a href="<?=$api_url?>api2/ui_forgot_password?site=<?=$site?>" title="login">忘記密碼</a><span style="padding:0 10px;color:#FFF;">|</span><a href="<?=$api_url?>api2/ui_register?site=<?=$site?>" title="login">快速註冊</a>
            <div id="elseID">
                <div id="elseID_title"></div>
                    <ul id="play_bt">
                        <li><a id="login-btn-quick" href="#" parm1="<?=$device_id?>" parm2="<?=$site?>"><img src="<?=$api_url?>/p/image/api2/play.png"/></a></li>
                        <?
                            // 產生所有第三方登入按鈕
                            $back_url = urlencode($redirect_url);
                            foreach ($channel_item as $channel)
                            {
                                if ($channel['channel'] == "facebook")
                                {
                                    echo "<li><a id='login-btn-facebook' href='#'><img src='{$api_url}/p/image/api2/fb_play.png'/></a></li>"; 
                                }
                                else if($channel['channel'] == "google")
                                {
                                    echo "<li><a  id='login-btn-google' href='#' parm=\"{$api_url}api2/ui_channel_login?site={$site}&channel={$channel['channel']}\"><img src='{$api_url}/p/image/api2/google_play.png'/></a></li>";
                                }
                            }
                        ?>
                    </ul>
                <div class="clear"></div>
            </div>
        </div>
        <div class="clear"></div>
        <div id="footer1">
            <div id="line"></div>
            <div id="longe"><img src="<?=$api_url?>/p/image/api2/longe.png" width="66" height="30"></div>
        </div>
    </div>
</div>