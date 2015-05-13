<a href="<?=site_url("service")?>" style="display:block">
	<span class="hd" style="display:inline-block;"></span>	
</a>
<div class="bd">
	<ul>
		<li><a href="<?=site_url("service/question")?>" class="sm1 <?=uri_string()=="service/question" ? 'active' : ''?>"></a></li>
		<li><a href="<?=site_url("service/listing")?>" class="sm2 <?=uri_string()=="service/listing" ? 'active' : ''?>"></a></li>
		<li><a href="<?=site_url("service/download")?>" class="sm3 <?=uri_string()=="service/download" ? 'active' : ''?>"></a></li>
		<li><a href="<?=site_url("platform/game_rule")?>" class="sm4 <?=uri_string()=="platform/game_rule" ? 'active' : ''?>"></a></li>
	</ul>
</div>