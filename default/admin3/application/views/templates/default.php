<? 
	$c_game_query = $this->db->from("games")->order_by("rank")->get();

	$c_game = array();
	$c_game_menu["獨代"] = array();
	$c_game_menu["聯運"] = array();
	$c_game_menu["關閉"] = array();
	
	foreach($c_game_query->result() as $row) {
		$c_game[$row->game_id] = $row;		
		if ($row->is_active == 0) {$c_game_menu["關閉"][] = $row; continue;}
		if (strpos($row->tags.",", "聯運,") !== false) {$c_game_menu["聯運"][] = $row; continue;}
		$c_game_menu["獨代"][] = $row;
	}
	
?>
<!DOCTYPE HTML>
<html lang="zh-tw">
<head>
<title><?=$meta["title"]?></title>
<meta charset="utf-8">
<style type="text/css">
li.dropdown li a {line-height:25px;}
</style>
<? 
	unset($meta["title"]);
	foreach($meta as $key => $content) {
		echo "<meta name='{$key}' content='{$content}'>";
	}
	echo $css_link;
	
	$main_menu = $this->config->item("main_menu");
	$servers = $this->config->item("servers");

	echo $js_include;
?>
</head>
<body>

<div class="container-fluid" style="padding-top:60px; min-width:1000px;">

<? if ($this->zacl->check_login()):?>
	<div class="navbar navbar-fixed-top" >
	  <div class="navbar-inner">  
	  
		<ul class="nav">
			<? $tmp = $servers['long_e'];
				unset($servers['long_e']);
			?>
			
			<? foreach($main_menu as $key => $arr):?>
			<li class="dropdown" >
				<a href="<?=site_url($key)?>" style="white-space:nowrap" class="dropdown-toggle" data-toggle="dropdown"><span style="padding-right:10px"><?=$arr['name']?></span><b class="caret"></b></a>
				<ul class="dropdown-menu">
					<? foreach($arr["menu"] as $arr2):?>
					    <?if (''<>$arr2["path"]):?>
						    <li class="<?=($row->game_id == $this->game_id ? 'active' : '')?>" >
							    <a href="<?=site_url($arr2["path"])?>"><?=$arr2["name"]?></a>						
						    </li>
						<? else:?>
						    <li class="divider"></li>
						<? endif;?>
					<? endforeach;?>
				</ul>						
			</li>	
			<? endforeach;?>	
			
			<? if ($this->game_id && isset($servers[$this->game_id])):
					$row = $c_game[$this->game_id];?>
			<li class="<?=($this->game_id && $this->game_id<>'long_e' ? 'active' : '')?> dropdown">
				<a href="#" style="white-space:nowrap" class="dropdown-toggle" data-toggle="dropdown"><span style="padding-right:10px"><?=$row->name?><b class="caret"></b></span></a>
				<ul class="dropdown-menu">
				<? foreach($servers[$this->game_id]['menu'] as $arr): ?>			
					<li class="<?=strpos($this->uri->uri_string, $arr['path']) !== false ? 'active' : ''?>">
						<a href="<?=site_url($arr['path'].(strpos($arr['path'],"?")===false?"?":"&")."game_id=".$this->game_id)?>">
							<?=$arr['name']?></a></li>			
				<? endforeach;?>
				</ul>
			</li>
            <? else:
					$row = $c_game[$this->game_id];?>
			<li class="<?=($this->game_id && $this->game_id<>'long_e' ? 'active' : '')?> dropdown">
				<a href="#" style="white-space:nowrap" class="dropdown-toggle" data-toggle="dropdown"><span style="padding-right:10px"><?=$row->name?><b class="caret"></b></span></a>
				<ul class="dropdown-menu">
				<? foreach($servers['default']['menu'] as $arr): ?>			
					<li class="<?=strpos($this->uri->uri_string, $arr['path']) !== false ? 'active' : ''?>">
						<a href="<?=site_url($arr['path'].(strpos($arr['path'],"?")===false?"?":"&")."game_id=".$this->game_id)?>">
							<?=$arr['name']?></a></li>			
				<? endforeach;?>
				</ul>
			</li>
			<? endif;?>
	    </ul>
	    
	    <ul class="nav pull-right">
	    	<li class="disabled">
	    		<a href="javascript:;"><?=$_SESSION['admin_account']?></a>
	    	</li>
	    	<? if ($allocate_count > 0):?>
	    	<li>
	    		<a style="color:#c00;" href="<?=site_url("service/my")?>">後送案件(<?=$allocate_count?>)</a>
	    	</li>
	    	<? endif;?>
	    	<li>
	    		<a href="<?=site_url("platform/modify_password")?>">修改密碼</a>
	    	</li>
	    	<li>
	    		<a href="<?=site_url("platform/logout")?>">登出</a>
	    	</li>
	    </ul>
	    
	  </div>
	</div>
<? endif;?>

	<div class="row-fluid">
	
		<? if (empty($full)):?>   
		<div class="span2">
			<? if ($this->uri->segment(1)<>"statistics" && $this->game_id):?>
			<ul class="nav nav-tabs nav-stacked">
		
				<? $server_menu = (isset($servers[$this->game_id])) ? $this->game_id : 'default';
                foreach($servers[$server_menu]['menu'] as $arr): ?>			
					<li class="<?=strpos($this->uri->uri_string, $arr['path']) !== false ? 'active' : ''?>">
						<a href="<?=site_url($arr['path'].(strpos($arr['path'],"?")===false?"?":"&")."game_id=".$this->game_id)?>">
							<?=$arr['name']?></a></li>			
				<? endforeach;?>
				
				<? if (isset($servers[$this->game_id]) && array_key_exists('extra_menu', $servers[$this->game_id])):?>
					<? foreach($servers[$this->game_id]['extra_menu'] as $arr):?>			
					<li><a href="<?=$arr['url']?>" target="_blank">
							<? if ( ! empty($arr["icon"])) echo '<i class="'.$arr["icon"].'"></i>';?>
							<?=$arr['name']?></a></li>
					<? endforeach;?>
				<? endif;?>				
					
				<li><a href="http://<?=$this->game_id?>.longeplay.com.tw" target="_blank" style="color:#777;">
					<i class="icon-home"></i> 檢視官網</a></li>				
			
		    </ul>
		    <? else:?>
		
			    <? if (array_key_exists($this->router->class, $main_menu)):?>
			    <ul class="nav nav-tabs nav-stacked">				
					<? foreach($main_menu[$this->router->class]['menu'] as $arr):?>			
					<li class="<?=($arr['path']==$this->uri->uri_string ? 'active' : '')?>">
						<a href="<?=site_url($arr['path'])?>"><?=$arr['name']?></a></li>			
					<? endforeach;?>		
			    </ul>
			    <? endif;?>
			    
			<? endif;?>		
		</div> 
		<? endif;?>
		
		<div class="<?=empty($full) ? "span10" : "span12"?>">
			<?= !empty($layout_breadcrumb) ? tran_breadcrumb($layout_breadcrumb) : ''?>
   			<?=$layout_content?>
   		</div>    
	</div>

</div>

</body>
</html>
