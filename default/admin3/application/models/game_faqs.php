<?php

class Game_faqs extends CI_Model {

	function get_faq_data($game_id, $bulletin_type, $limit, $offset=0, $keyword)
	{

		$this->DB2->query("SELECT f.* ,fg.games,ft.type_ids FROM faq f
			LEFT JOIN  (SELECT faq_id, group_concat(game_id) as games from faq_games group by faq_id) fg
			on f.id = fg.faq_id
			left join  (select faq_id, group_concat(type_id) as type_ids from faq_types group by faq_id) ft
			on f.id= ft.faq_id");

		//$bulletin_type && $this->db->where("type", $bulletin_type);
		//$keyword && $this->db->like("title", $keyword);

		if ($offset) {
			$this->DB2->limit($limit, $offset);
		} else $this->DB2->limit($limit);



		return $this->DB2->result();
	}

	function get_faq_count($game_id, $bulletin_type, $keyword)
	{
		//$bulletin_type && $this->db->where("type", $bulletin_type);
		//$keyword && $this->db->like("title", $keyword);
		return $this->db->select("count(*) cnt")
		    //->where("(target like '%{$game_id},%')", null, false)
		    //->where("game_id", $game_id)
			->from("faq")
			->get()->row()->cnt;
	}

	function get_bulletin($id)
	{
		return $this->db->where("id", $id)->from("bulletins")->get()->row();
	}

	function insert_bulletin($data)
	{
		isset($data['create_time']) or $data['create_time'] = now();
		isset($data['update_time']) or $data['update_time'] = now();
		isset($data['start_time']) or $data['start_time'] = now();
		isset($data['end_time']) or $data['end_time'] = '2038-01-01 00:00:00';

		$data['admin_uid'] = 0; //暫時

		$this->db->insert('bulletins', $data);
		return $this->db->insert_id();
	}

	function update_bulletin($id, $data)
	{
		$data['update_time'] = now();
		if (empty($data['start_time'])) {
			$data['start_time'] = now();
		}
		if (empty($data['end_time'])) {
			$data['end_time'] = '2038-01-01 00:00:00';
		}
		$this->db->where('id', $id)->update('bulletins', $data);
	}

	function delete_bulletin($id)
	{
		$this->db->where('id', $id)->delete('bulletins');
		return $this->db->affected_rows();
	}
}
