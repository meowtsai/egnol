<?php

function output_result($msg=array(), $msg_type='error')
{
	if (empty($msg) || $msg_type='success') {
		echo '<div class="alert alert-success"><i class="icon-ok-circle"></i> 成功</div>';
	}
	else {
		if ( ! is_array($msg)) $msg = array($msg);
		echo '<div class="alert alert-'.$msg_type.'">';
		foreach ($msg as $m) { 
			echo '<i class="icon-remove-circle"></i> '.$m.'<br>';
		}	
		echo '</div>';
	}
}