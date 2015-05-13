<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');

class G_Layout
{
    var $CI;
    var $template, $template_dir;
    var $data, $template_data;
    var $meta;
    
    var $css_link, $js_include, $css_path, $js_path, $img_path, $breadcrumb;

    function __construct($params)
    {    
    	$this->CI =& get_instance();  
    	$this->template = $params['active_template'];
    	$this->template_dir = $params['template_dir'];
    	
    	$this->css_link = $params['default_css_link'];
    	$this->js_include = $params['default_js_include'];
    	$this->css_path = $params['css_path'];
    	$this->js_path = $params['js_path'];
    	$this->img_path = $params['img_path'];
    	
    	$this->breadcrumb = '';
    	
    	$this->meta = $params["default_meta"];
    	$this->data = array();
    }
    
    function img_url($img='')
    {
    	return $img ? $this->CI->config->base_url($this->img_path.$img) : $this->CI->config->base_url($this->img_path)."/";
    }
    
    function set_meta($key, $value)
    {
    	$this->meta[$key] = $value;
    	return $this;
    }
    
    function set($key, $value)
    {
    	$this->data[$key] = $value;
    	return $this;
    }
    
    function append($key, $value)
    {
    	$this->data[$key] .= $value;
    	return $this;
    }
    
    function set_template_data($key, $value)
    {
    	$this->template_data[$key] = $value;
    	return $this;
    }
    
    function append_template_data($key, $value)
    {
    	$this->template_data[$key] .= $value;
    	return $this;
    }
    
    function add_css_link($item)
    {
    	if (is_array($item)) $this->css_link = array_merge($this->css_link, $item);
    	else array_push($this->css_link, $item);
    	return $this;
    }

    function add_js_include($item)
    {
    	if (is_array($item)) $this->js_include = array_merge($this->js_include, $item);
    	else array_push($this->js_include, $item);
    	return $this;
    }    
    
    function produce_css_link()
    {
    	$str = "";
    	$path = $this->CI->config->site_url($this->css_path);
    	foreach($this->css_link as $item) {
    		if (strpos($item, "http://") === false) {
    			$href = "{$path}/{$item}.css";
    		} else $href = $item;
    		$str .= "<link rel='stylesheet' type='text/css' href='{$href}?5'>";
    	}
    	return $str;
    }
    
    function produce_js_include()
    {
    	$str = "";
    	$path = $this->CI->config->site_url($this->js_path);
    	foreach($this->js_include as $item) {
    		if (strpos($item, "http://") === false) {
    			$href = "{$path}/{$item}.js";
    		} else $href = $item;    		
    		$str .= "<script src='{$href}?8'></script>";
    	}
    	return $str;
    }
    
    function add_breadcrumb($name, $url='')
    {    	
    	if ($url) $str = "<a href='".site_url($url)."'>{$name}</a> » ";
    	else $str = "{$name} » ";
    	$this->breadcrumb .= $str;
    	return $this;
    }
    
    function set_breadcrumb($arr)
    {
    	$str = "<a href='".site_url("/")."'>首頁</a> » ";
    	foreach($arr as $key => $val) {
    		if ($val) $str .= "<a href='".site_url($val)."'>{$key}</a> » ";
    		else $str .= "{$key} » ";
    	}
    	$this->breadcrumb = $str;
    	return $this;
    }    
    
    function view($view="")
    {
    	if (empty($view)) {
    		$view = $this->CI->router->directory . $this->CI->router->class."/".$this->CI->router->method;
    	}
    	
    	$this->data['meta'] = $this->meta;
    	$this->data['css_link'] = $this->produce_css_link();
    	$this->data['js_include'] = $this->produce_js_include();
    	$this->data['layout_breadcrumb'] = $this->breadcrumb;

    	$this->CI->load->view($view, $this->data);    
    }
    
    function render($view="", $template="")
    {
    	if (empty($view)) {
    		$view = $this->CI->router->directory . $this->CI->router->class."/".$this->CI->router->method;
    	}

    	$this->data['layout_breadcrumb'] = $this->breadcrumb;
    	
    	$this->template_data['meta'] = $this->meta;
    	$this->template_data['layout_content'] = $this->CI->load->view($view, $this->data, true);
    	$this->template_data['css_link'] = $this->produce_css_link();
    	$this->template_data['js_include'] = $this->produce_js_include();
    	
    	if (empty($template)) $template = $this->template;
		$template_file = $this->template_dir."/".$template;
		
    	echo $this->CI->load->view($template_file, $this->template_data, true);
    }
}

/* End of file Template.php */
/* Location: ./system/application/libraries/Template.php */