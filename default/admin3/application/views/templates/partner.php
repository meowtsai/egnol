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
	echo $js_include;
		
	$partner_def_menu = $this->config->item("partner_def_menu");
	$partner_menu = $this->config->item("partner_menu");
?>
</head>
<body>

<div class="container-fluid" style="padding-top:60px; min-width:1000px;">

	<div class="navbar navbar-fixed-top" >
	  <div class="navbar-inner">  
		<ul class="nav">
			<li>
				<a href="<?=site_url("partner/{$partner}")?>">
					<?=array_key_exists($partner, $partner_menu) ? $partner_menu[$partner]['name'] : $partner;?>
				</a>
			</li>
	    </ul>
	    <? if ($this->zacl->check_login()):?>
	    <ul class="nav pull-right">
	    	<li class="disabled">
	    		<p class="navbar-text"><?=$_SESSION['admin_account']?></p>
	    	</li>
	    	<li>
	    		<a href="http://www.long_e.com.tw/admin3/platform/partner_modify_password/<?=$partner?>">修改密碼</a>
	    	</li>
	    	<li>
	    		<a href="<?=site_url("platform/logout")?>">登出</a>
	    	</li>
	    </ul>
	    <? endif;?>
	  </div>
	</div>
	
	<? if ($this->zacl->check_acl('partner', $partner)):?>
	<div class="row-fluid">   
		<div class="span2">			
	
			<ul class="nav nav-tabs nav-stacked">
				<? foreach($partner_def_menu as $arr):
						if ( ! $this->zacl->check_acl($arr['auth'], "read")) continue;
				?>
				<li class="<?=(strpos($this->uri->uri_string, $arr['path']) !== false ? 'active' : '')?>">
					<a href="<?=site_url($arr['path'])?>/<?=$partner?>"><?=$arr['name']?></a></li>
				<? endforeach;?>
						
		    	<? if (array_key_exists($partner, $partner_menu)):?>		    			
					<? foreach($partner_menu[$partner]['menu'] as $arr):?>			
					<li class="<?=(strpos($this->uri->uri_string, $arr['path']) !== false ? 'active' : '')?>">
						<a href="<?=site_url($arr['path'])?>/<?=$partner?>"><?=$arr['name']?></a></li>			
					<? endforeach;?>				    
		    	<? endif;?>
		    </ul>
			    	
		</div> 
		<div class="span10">
			<?= !empty($layout_breadcrumb) ? tran_breadcrumb($layout_breadcrumb) : ''?>
   			<?=$layout_content?>
   		</div>    
	</div>
	<? endif;?>

</div>


</body>
</html>
