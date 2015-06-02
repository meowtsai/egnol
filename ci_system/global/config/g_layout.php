<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');

$config['template_dir'] = 'templates';
$config['active_template'] = 'default';

$config['css_path'] = 'p/css/';
$config['js_path'] = 'p/js/';
$config['img_path'] = 'p/img/';

$config['default_css_link'] = array('http://'.base_url().'/p/css/common.css', 'default', 'jquery-ui-1.8.22.custom');
$config['default_js_include'] = array('jquery-1.7.2.min', 'jquery-ui-1.8.22.custom.min');

$config['default_meta'] = array(
			"title"	=> "龍邑",
			"description" => "龍邑",
			"robots" => "all"
		);

/* End of file template.php */
/* Location: ./system/application/config/template.php */