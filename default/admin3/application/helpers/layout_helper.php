<?php

function output_result($msg=array())
{
	if (empty($msg)) {
		echo '<div class="alert alert-success"><i class="icon-ok-circle"></i> 成功</div>';
	}
	else {
		if ( ! is_array($msg)) $msg = array($msg);
		echo '<div class="alert alert-error">';
		foreach ($msg as $m) { 
			echo '<i class="icon-remove-circle"></i> '.$m.'<br>';
		}	
		echo '</div>';
	}
}