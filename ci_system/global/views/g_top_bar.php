<div id="le_header">
  <div id="nav_bar">
    <div class="nav_frm"><a class="nav_link" href="<?=$game_url?>">回到首頁</a>
	</div><div class="nav_frm"><a class="nav_link" href="/payment?site=<?=$site?>">儲值中心</a>
	</div><div class="nav_frm"><a class="nav_link" href="<?=$fan_page?>">粉絲團</a>
	</div><div class="nav_frm"><a class="nav_link" href="/service?site=<?=$site?>">客服中心</a>
	</div><div class="nav_frm"><a class="nav_link" href="/member?site=<?=$site?>&redirect_url=<?=urlencode($redirect_url)?>"><?= $this->g_user->is_login() ? "會員資料" : "會員登入" ?></a></div>
  </div>
</div>