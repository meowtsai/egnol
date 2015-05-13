<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Guide extends MY_Controller 
{	
	
	function get_list($id=0, $record=0)
	{				
		$this->_init_layout();
		
		$this->load->model("g_guides");		
		$this->load->library('pagination');
		
		$guide = $this->g_guides->get_row($this->game, $id);
		if ($guide == false) {
			die("無此記錄");
		}
		
		$config['base_url'] = site_url("guide/get_list/{$id}?");
		$config['total_rows'] = $this->g_guides->get_count($this->game, $id);
		$config['uri_segment'] = 4;
		$config['per_page'] = 20;		
		$this->pagination->initialize($config);

		$this->g_layout
			->set_breadcrumb($this->_get_guide_path_arr($id))
			->set("guide", $guide)
			->set("query", $this->g_guides->get_list($this->game, $id, 20, $record))		
			->render();
	}
	
	function detail($id)
	{
		$this->_init_layout();
		$this->load->model("g_guides");
		$row = $this->g_guides->get_row($this->game, $id);
		if ($row == false) {die("無此記錄");}

		$this->g_layout
			->set_breadcrumb($this->_get_guide_path_arr($id))
			->set("row", $row)
			->render();
	}
	
	function _get_guide_path_arr($parent_id)
	{
		$this->load->model("g_guides");
		$guide_path_arr = array();
		if ($parent_id) {
			$tmp = array();
			$i = 0;
			while($row = $this->g_guides->get_row($this->game, $parent_id)) {
				if ($i++ == 0) $ary = array("name" => $row->guide_title, "url" => "");
				else {
					if (empty($row->guide_content)) {
						$ary = array("name" => $row->guide_title, "url" => "");
					}
					else {
						$ary = array(
							"name" => $row->guide_title,
							"url" => "guide/detail/{$row->id}"
						);
					}
				}
				$tmp[] = $ary;				
				$parent_id = $row->parent_id;
			}
			while($arr = array_pop($tmp)) {
				$guide_path_arr[$arr['name']] = $arr['url'];
			}
		}
		return $guide_path_arr;
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */