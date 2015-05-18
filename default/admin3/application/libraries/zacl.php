<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Zacl
{
	// Set the instance variable
	var $CI;
	var $allow_games=array();

	function __construct()
	{
		// Get the instance
		$this->CI =& get_instance();
		
		// Set the include path and require the needed files
		set_include_path(get_include_path() . PATH_SEPARATOR . dirname(__FILE__) . "/../libraries");
		require_once(dirname(__FILE__) . '/Zend/Acl.php');
		require_once(dirname(__FILE__) . '/Zend/Acl/Role.php');
		require_once(dirname(__FILE__) . '/Zend/Acl/Resource.php');
		$this->acl = new Zend_Acl();

		$query = $this->CI->db->query('select * from admin_resources order by concat(parent, resource)');
		foreach($query->result() AS $row) {
			$resource = new Zend_Acl_Resource($row->resource);
			empty($row->parent) ? 
				$this->acl->add($resource): 
				$this->acl->add($resource, $row->parent);
		}		
		
		// Get the ACL for the roles
		$query = $this->CI->db->query('select * from admin_roles order by concat(parent, role)');
		foreach($query->result() AS $row)
		{
			$role = new Zend_Acl_Role($row->role);
			empty($row->parent) ? 
				$this->acl->addRole($role): 
				$this->acl->addRole($role, $row->parent);			
		}
		
		
		$query = $this->CI->db->from('admin_permissions p')
			->join('admin_resources rs', 'p.resource=rs.resource')
			->join('admin_roles r', 'r.role=p.role')->get();
		foreach($query->result() AS $row) {
			$spt = explode(",", $row->operations);
			foreach($spt as $op) {
				$this->acl->allow($row->role, $row->resource, $op);
			}
		}
		
		fb($this->acl);
		
	}

	// Function to check if the current or a preset role has access to a resource
	function check_acl($resource, $operation, $role='')
	{		
		if (empty($role)) {
			if ( ! empty($_SESSION['admin_role'])) {
				$role = $_SESSION['admin_role'];
			}
		}
		if (empty($role)) return false;
		if ($role == 'admin') return true;
		
		if (!$this->acl->has($resource)) return false;
		return $this->acl->isAllowed($role, $resource, $operation);
	}
	
	function check_login($redirect_url='')
	{
		if ( ! empty($_SESSION['admin_uid'])) {
			return true;
		}
		else {
			if ($redirect_url) {
				$current_url = 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
				
				header('location:'.site_url("platform/login?redirect_url=".urlencode($current_url)));
				exit();
			}
			return false;
		}
	}
	
	//檢查權限，若無權限，顯示錯誤
	function check($resource, $operation='')
	{
		if ( ! $this->check_acl($resource, $operation)) {
			$this->no_auth();
		}
	}
	
	function no_auth()
	{
		$this->CI->_init_layout()
				->set("result", "error")
				->set("message", "沒有足夠的權限!")
				->render("templates/_message", "simple");
		exit();
	}
	
	function encode($str) 
	{
		return md5(md5($str."coozadmin"));
	}
}