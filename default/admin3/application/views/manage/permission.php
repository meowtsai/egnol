<? 

$GLOBALS['chk'] = $GLOBALS['parent_chk'] = array();
foreach($permission->result() as $row) {
	$spt = explode(",", $row->operations);		
	foreach($spt as $s) {
		$GLOBALS['chk'][$row->resource][] = $s;	
	}	
}
if ($parent_permission) {
	foreach($parent_permission->result() as $row) {
		$spt = explode(",", $row->operations);		
		foreach($spt as $s) {
			$GLOBALS['parent_chk'][$row->resource][] = $s;	
		}	
	}
}

$obj = (object) array('value' => '', 'children' => array());
$table = $root =  array();
foreach($resource->result() as $row) {
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

function list_operation($row) 
{	
	global $chk, $parent_chk;
	$html = '';
	if ($row->operation_list) {
		$spt = explode(",", $row->operation_list);
		foreach($spt as $s) {
			if (isset($parent_chk[$row->resource]) && in_array($s, $parent_chk[$row->resource])) $checked = " checked='checked' disabled='disabled' ";
			else if (isset($chk[$row->resource]) && in_array($s, $chk[$row->resource])) $checked = " checked='checked' ";
			else $checked = '';
			$html .= "<label class='checkbox inline'>
    	  		<input type='checkbox' name='{$row->resource}[]' value='{$s}' {$checked}>{$s}</label>";
		}		
	}	
	return $html;
}

function output($node, $level=1)
{
	$row = $node->value;
	if ($row->resource == 'manage') return;
?>
	<tr>
		<td><? for ($i=1; $i<$level; $i++) echo "　　";?>	
			<?=$row->resource?> <small class="text-success"><?=$row->resource_desc?></small></td>
		<td><?=list_operation($row)?></td>		
	</tr>
<? 
	foreach ($node->children as $node2) {
		output($node2, $level+1);
	}
}
?>

<? if (isset($result)):?>
	<? if ($result):?>
	<div class="alert alert-success"><i class="icon-ok-circle"></i> 修改成功</div>
	<? else:?>
	<div class="alert alert-error"><i class="icon-remove-circle"></i> 失敗</div>
	<? endif;?>
<? endif;?>

<form method="post">
<table class="table table-striped table-bordered" style="width:760px;">
	<caption></caption>
	<thead>
		<tr>
			<th style="width:35%">功能</th>
			<th style="width:65%">權限</th>
		</tr>
	</thead>
	<tbody>
		<? foreach($root as $node) output($node);?>
	</tbody>
</table>

<div class="form-actions">
	<button type="submit" class="btn" name="submit">確認送出</button> 
</div>
</form>