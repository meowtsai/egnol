<? $member_title = $this->g_user->is_login() ? "會員資料" : "會員登入"; ?>
<div id="header">
<div class="header-ins">
<div class="header-logo">

<h1><?=$meta["title"]?></h1>
<a href="<?=$game_url?>" title="<?=$meta["title"]?>"><img src="<?=$game_url?>p/image/header-logo.png" alt="<?=$meta["title"]?>" /></a>
</div>
<div class="nav">
<ul class="cf">
</ul>

<ul id="menu" class="cf" typeof="v:menu">

</ul>
</div>
</div>
</div>
<script>
$(function()
{
	$('.not_ready').click(function()
	{
		leOpenDialog('龍邑遊戲','敬請期待！',leDialogType.MESSAGE);
	});
});
</script>
