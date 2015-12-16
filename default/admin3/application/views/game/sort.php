<style type="text/css">
#sortable {list-style-type:none; margin:0; padding:0; }
#sortable li {margin:2px; padding:8px; background:#eee; border:1px solid #ccc; cursor:move; float:left;
			width:100px; height:45px; text-align:center;
}
</style>


  <ul id="sortable">
	<? foreach($query->result() as $row):?>
	<li id="<?=$row->game_id?>"><?=$row->name?></li>
	<? endforeach;?>
  </ul>
  
  	<div style="clear:both"></div>
  	
	<div class="form-actions">
  		<button type="button" class="btn" id="save">儲存</button>
  	</div>  

<script type="text/javascript">
$(function() { 
	$( "#sortable" ).sortable();
    $( "#sortable" ).disableSelection();

    $("#save").on("click", function(){
        var arr = [];
	     $('#sortable li').each(function(k,v){arr.push(v.id);});
	     $.post("<?=site_url("game/save_sort")?>", {data:arr.join(',')}, function(json){
				alert(json.message);
		     }, 'json');		 
	});
}); 
</script>