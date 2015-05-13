<?
$obj = (object) array('value' => '', 'children' => array());
$table = $root =  array();
foreach($query->result() as $row) {
	$node = clone $obj;
	$node->value = $row;
	$table[$row->role] = $node;
}
foreach($table as $node) {
	if ($node->value->parent) {
		array_push($table[$node->value->parent]->children, $node);
	}
	else {
		$root[] = $node;
	}	
}

function output($node, $level=1)
{
	$row = $node->value;
?>
	<tr>
		<td><? for ($i=1; $i<$level; $i++) echo "　　";?>	
			<a href="<?=site_url("manage/permission/{$row->role}")?>"><?=$row->role?> <small class="text-success"><?=$row->role_desc?></small></a>
		</td>
		<td>
			<? if ($row->role !== 'admin'):?>
				<a href="<?=site_url("manage/modify_role/{$row->role}")?>" class="btn btn-mini">編輯</a>
				<? if (count($node->children)==0):?>
				<a href="javascript:;" url="<?=site_url("manage/delete_role/{$row->role}")?>" class="btn btn-mini json_del">刪除</a>
				<? endif;?>
			<? endif;?>
		</td>			
	</tr>
<? 
	foreach ($node->children as $node2) {
		output($node2, $level+1);
	}
}
?>

<div id="func_bar">
	<a class="btn btn-primary" href="<?=site_url("manage/modify_role")?>">
		<i class="icon-plus icon-white"></i>
		新增群組</a>
</div>

<table class="table table-striped table-bordered" style="width:auto">
	<caption></caption>
	<thead>
		<tr>
			<th style="width:260px;">群組</th>
			<th></th>
		</tr>
	</thead>
	<tbody>
		<? foreach($root as $node) {
			output($node);
		}?>
	</tbody>	
</table>