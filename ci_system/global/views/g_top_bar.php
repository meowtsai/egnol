<? $member_title = $this->g_user->is_login() ? "會員資料" : "會員登入"; ?>
<div id="header">
<div class="header-ins">
<div class="header-logo">

<h1><?=$meta["title"]?></h1>
<a href="<?=$game_url?>" title="<?=$meta["title"]?>"><img src="<?=$longe_url?>p/image/header-logo.png" alt="<?=$meta["title"]?>" /></a>
</div>



<div class="nav">
<ul class="cf">
<li><a href="<?=$longe_url?>member?site=<?=$site?>&redirect_url=<?=urlencode($redirect_url)?>" title="會員登入"></a></li>
<li><a href="<?=$longe_url?>service?site=<?=$site?>" title="客服中心"></a></li>
<li><a href="<?=$fan_page?>" target="_blank" title="粉絲團NEWS"></a></li>
<li><a href="<?=$longe_url?>payment?site=<?=$site?>" title="儲值中心"></a></li>
<li><a href="<?=$game_url?>" title="回到首頁"></a></li>
</ul>

<ul id="menu" class="cf" typeof="v:menu">
<li><a href="<?=$game_url?>" title="回到首頁" rel="v:url" property="v:title">回到首頁</a></li>
<li><a href="<?=$longe_url?>payment?site=<?=$site?>" title="儲值中心" rel="v:url" property="v:title">儲值中心</a></li>
<li><a href="<?=$fan_page?>" target="_blank" title="粉絲團NEWS" rel="v:url" property="v:title">粉絲團NEWS</a></li>
<li><a href="<?=$longe_url?>service?site=<?=$site?>" title="客服中心" rel="v:url" property="v:title">客服中心</a></li>
<li><a href="<?=$longe_url?>member?site=<?=$site?>&redirect_url=<?=urlencode($redirect_url)?>" title="<?=$member_title?>" rel="v:url" property="v:title"><?=$member_title?></a></li>

</ul>

</div>
</div>
</div>

