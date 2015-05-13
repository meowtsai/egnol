<style type="text/css">
<? if ( ! $this->input->is_ajax_request()):?>
body {background:#000;}
<? endif;?>

#server_list {height:320px; overflow-y:scroll; margin:30px 0 0; padding:20px; text-align:left;}
#server_list li {float:left; margin:4px;}
</style>

<div id="choose_server_form">

<div style="background:url(/p/img/server_bk.jpg) no-repeat #fff;  width:550px; position:relative; margin-top:20px; padding:8px;">
<!-- 
	<div style="position:absolute; top:438px; left:320px;">
    	<? foreach($this->server['list'] as $row):
               if ($url = chk_server_open($row)):?>
        <div style="float:left; margin:5px 7px;">
        	<a href="<?=$url?>" target="_self"><img src="/p/img/server/<?=$row->server_id?>_off.jpg" class="change"/></a>
        </div>
        <? endif; 
      	endforeach;?>	
	</div>
	 -->
	
	<div id="server_list">
		
		<ul>
    	<? foreach($this->server['list'] as $row):
               if ($url = chk_server_open($row)):?>		
			<li><a href="<?=$url?>" target="_self"><img src="/p/img/server/<?=$row->server_id?>_off.png" class="change" /></a></li>
			<? endif; 
      	endforeach;?>	
		</ul>
	
	</div>
		
</div>

</div>