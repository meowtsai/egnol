<?php

function get_guide_path_str($parent_id, $game_id)
{
	$CI =& get_instance();
	$guide_path_str = '';
	if ($parent_id) {			
		while($row = $CI->guides->get_guide($parent_id)) {
			$ext = "<span style='font-size:12px;'>(<a href='".server_site_url($game_id, "guide/detail/{$parent_id}")."' target='_blank'>檢視</a>)</span>";
			$guide_path_str = "<a href='".site_url("guide/get_list/{$parent_id}?game_id={$game_id}")."'>$row->guide_title</a> {$ext} » ".$guide_path_str;
			$parent_id = $row->parent_id;
		}
	}
	return "<a href='".site_url("guide/get_list?game_id={$game_id}")."'>首頁</a> » ".$guide_path_str;
}