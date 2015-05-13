<!DOCTYPE HTML>
<html lang="zh-tw">
<head>
<title><?=$meta["title"]?></title>
<meta charset="utf-8">
<? 
	unset($meta["title"]);
	foreach($meta as $key => $content) {
		echo "<meta name='{$key}' content='{$content}'>";
	}
	echo $css_link;
	
	$partner_menu = $this->config->item("partner_menu");
?>
</head>
<body>

<div class="container-fluid" style="padding-top:60px; min-width:1000px;">

	<div class="navbar navbar-fixed-top" >
	  <div class="navbar-inner">  
	    <? if ($this->zacl->check_login()):?>
	    <ul class="nav pull-right">
	    	<li class="disabled">
	    		<p class="navbar-text"><?=$_SESSION['admin_account']?></p>
	    	</li>
	    	<li>
	    		<a href="<?=site_url("platform/logout")?>">登出</a>
	    	</li>
	    </ul>
	    <? else:?>
	    <ul class="nav pull-right">
	    	<li><a href="<?=site_url("platform/login")?>">登入</a></li>
	    </ul>
	    <? endif;?>
	  </div>
	</div>
	
	<div class="row-fluid">
		<?= !empty($layout_breadcrumb) ? tran_breadcrumb($layout_breadcrumb) : ''?>
  		<?=$layout_content?>   
	</div>

</div>

<?=$js_include?>
</body>
</html>
