<style>
#menu li {border:1px solid #ccc; background:#ddd; margin:3px;}
#menu a, #menu p {padding:20px 15px; display:block;}
#menu a:hover {background:#fff;}
</style>

<div id="menu">

<ul>
	<li><p>Hellow, <?=$this->g_user->display_account(false)?></p></li>
	<li><a href="<?=site_url("member/bind_account")?>">帳號綁定</a></li>	
	<li><a href="<?=site_url("service?game=".$this->game)?>">線上提問</a></li>
	
</ul>

</div>