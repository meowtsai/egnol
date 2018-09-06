<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class MY_Controller extends CI_Controller {

	var $global_dir;
	var $game_id;
	var $DB1;
	var $DB2;

	function __construct()
	{
		parent::__construct();

		error_reporting(E_ALL);
		ini_set('display_errors','On');

		$this->global_dir = BASEPATH.'../global/';
		$this->load->add_package_path($this->global_dir);
		$this->load->helper("g_common");
		$this->DB1 = $this->load->database('long_e', TRUE);
        $this->DB2 = $this->load->database('long_e_2', TRUE);
		$this->load->library(array("session", "g_user", "FirePHP", "Fb", "zacl"));

		$this->game_id = $this->input->get("game_id");

		if (ENVIRONMENT == 'development' && ! $this->input->is_ajax_request()) {
			$this->output->enable_profiler(TRUE);
		}
	}

	function _init_layout()
	{
		$this->load->library("g_layout");
		$this->load->helper("layout");

		$allocate_count = 0;
		if ($this->zacl->check_login()) {
			$allocate_count = $this->DB1->from("questions q")->where("q.allocate_admin_uid", $_SESSION['admin_uid'])->where("q.allocate_status", "1")->count_all_results();
		}

		$favor_count = 0;
		if ($this->zacl->check_login()) {
			$favor_count = $this->DB1->from("question_favorites")->where("admin_uid", $_SESSION['admin_uid'])->count_all_results();
		}

		$batch_count = 0;
		if ($this->zacl->check_login()) {
			$batch_count = $this->DB1->from("batch_questions a")->join("batch_tasks b","a.batch_id=b.id")->where("b.admin_uid", $_SESSION['admin_uid'])->where("b.status", '1')->count_all_results();
		}
		return $this->g_layout
			->add_css_link(array('bootstrap.min', 'jquery.autocomplete', 'jquery-ui-1.8.22.custom'))
			->add_js_include(array('jquery-1.7.2.min', 'jquery.validate.min', 'jquery.form', 'jquery.blockUI', 'bootstrap.min', 'jquery.placeholder.min', 'jquery.autocomplete.pack', 'jquery-ui-1.8.22.custom.min', 'default'))
			->set_meta("title", "龍邑 :: 後端管理平台")
			->set('allocate_count', $allocate_count)
			->set('batch_count', $batch_count)
			->set('favor_count', $favor_count);
	}

	function _chk_game_id()
	{
		if (empty($this->game_id)) {
			redirect("/");
		}
	}
}

function tran_breadcrumb($bc)
{
	$CI =& get_instance();
	$str = '<ul class="breadcrumb">';
	$spt = explode("»", $bc);
	foreach($spt as $s) {
		if (trim($s) == '') continue;
		$str .= '<li>'.$s.' <span class="divider">/</span></li>';
	}
	$str .= '</ul>';
	return $str;
}

function tran_pagination($pn)
{
	$CI =& get_instance();
	$str = '<div class="pagination"><ul>';
	$spt = explode("&nbsp;", $pn);
	if (count($spt) > 1) {

		foreach($spt as $s) {
			$s = strtr($s, array("strong"=>"span", "</div>"=>"", '<div class="pagination">'=>''));
			if ( ! empty($s)) $str .= '<li>'.$s.'</li>';
		}
		$str .= '</ul></div>';
		return $str;
	}
	else return '';
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */
