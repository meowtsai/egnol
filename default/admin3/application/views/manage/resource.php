<?
$obj = (object) array('value' => '', 'children' => array());
$table = $root =  array();
foreach($query->result() as $row) {
	$node = clone $obj;
	$node->value = $row;
	$table[$row->resource] = $node;
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
			<?=$row->resource?> <small class="text-success"><?=$row->resource_desc?></small></td>
		<td><?=$row->operation_list?></td>
		<td>
			<a href="<?=site_url("manage/modify_resource/{$row->resource}")?>" class="btn btn-mini">編輯</a>
			<? if (count($node->children)==0):?>
			<a href="javascript:;" url="<?=site_url("manage/delete_resource/{$row->resource}")?>" class="btn btn-mini json_del">刪除</a>
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
	<a class="btn btn-primary" href="<?=site_url("manage/modify_resource")?>">
		<i class="icon-plus icon-white"></i>
		新增</a>
</div>

<table class="table table-striped table-bordered" style="width:auto">
	<caption></caption>
	<thead>
		<tr>
			<th style="width:260px;">功能</th>
			<th style="width:200px;">操作</th>
			<th></th>
		</tr>
	</thead>
	<tbody>
		<? foreach($root as $node) {
			output($node);
		}?>
	</tbody>
</table>