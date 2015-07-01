<a href="<?=site_url("member/index")?>" style="display:block">
	<span class="hd" style="display:inline-block;"></span>	
</a>
<div class="bd">
	<ul>
		<li><a href="<?=site_url("member/update_profile")?>" class="sm1 <?=uri_string()=="member/update_member_data" ? 'active' : ''?>"></a></li>
		<li><a href="<?=site_url("member/change_password")?>" class="sm2 <?=uri_string()=="member/change_password" ? 'active' : ''?>"></a></li>
	</ul>
</div>