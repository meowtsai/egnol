<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Manage extends MY_Controller {
	
	function __construct() 
	{
		parent::__construct();	
		$this->zacl->check_login(true);			
	}
	
	function init_mamage_layout()
	{
		return $this->_init_layout()
			->add_breadcrumb("系統管理", "manage");
	}
	
	function index()
	{
		header("location:".site_url("manage/user"));
	}
	
	function user()
	{
		$this->zacl->check("manage_user", "read");
		$query = $this->DB2->from("admin_users u")
					->join("admin_roles r", "u.role=r.role", "left")->order_by("u.uid desc")->get();
		$this->init_mamage_layout()
			->add_breadcrumb("使用者管理")
			->set("query", $query)
			->render();
	}
		
	function permission($role)
	{
		$this->zacl->check("manage_permission", "read");
		
		$this->init_mamage_layout();
		
		if ($post = $this->input->post()) 
		{
			unset($post['submit']);
			$this->DB1->delete("admin_permissions", array("role"=>$role));
			foreach($post as $rs => $pms_arr) {
				$this->DB1->insert("admin_permissions", array(
							"role" => $role,
							"resource" => $rs,
							"operations" => implode(",", $pms_arr)
						));
			}
			$this->g_layout->set("result", $this->DB1->affected_rows()>0);
		}
		
		//$this->zacl->check("manage/permission", "read");
		
		$role_row = $this->DB2->from("admin_roles r")->where("r.role", $role)->get()->row();
		$permission = $this->DB2->from("admin_permissions p")
						->join("admin_roles r", "p.role=r.role")
						->where("p.role", $role)
						->get();

		if ( ! empty($role_row->parent)) {
			$parent_permission = $this->DB2->from("admin_permissions p")
									->join("admin_roles r", "p.role=r.role")
									->where("p.role", $role_row->parent)
									->get(); 
		} else $parent_permission = false;
		
		$resource = $this->DB2->get("admin_resources");
		
		$this->g_layout
			->add_breadcrumb("權限設定", "manage/role")
			->add_breadcrumb($role_row->role_desc)
			->set("permission", $permission)
			->set("parent_permission", $parent_permission)
			->set("resource", $resource)
			->render();
	}
	
	function role()
	{
		$this->zacl->check("manage_role", "read");
		
		$this->init_mamage_layout()->add_breadcrumb("權限設定");
		//$this->zacl->check("manage/role", "read");
		
		$query = $this->DB2->get("admin_roles");
		
		$this->g_layout
			->set("query", $query)
			->render();
	}
	
	function resource()
	{
		$this->zacl->check("manage_resource", "read");
		
		$this->init_mamage_layout()->add_breadcrumb("功能設定");
		//$this->zacl->check("manage/role", "read");
		
		$query = $this->DB2->get("admin_resources");
		
		$this->g_layout
			->set("query", $query)
			->render();
	}	
	
	function modify_user($uid='')
	{
		$this->zacl->check("manage_user", "modify");
		$this->init_mamage_layout();
		
		if ($this->input->post()) {
			
			if ($this->input->post("key")) {
				$this->DB1->where("uid", $this->input->post("key"))
					->update("admin_users", array(
								"name" => $this->input->post("name"),
								"role" => $this->input->post("role"),
							));
				$this->g_layout->set("result", $this->DB1->affected_rows()>0);
			}
			else {
				$this->DB1->insert("admin_users", array(
								"account" => $this->input->post("account"),
								"password" => $this->zacl->encode($this->input->post("password")),
								"name" => $this->input->post("name"),
								"role" => $this->input->post("role"),
							));		
				header("location:".site_url("manage/user"));
				exit();
			}			
		}
		//$this->zacl->check("manage/role", "read");	
		
		if ($uid) {
			$row = $this->DB2->get_where("admin_users", array("uid"=>$uid))->row();
		}
		else {
			$row = false;	
		}
		
		$all_role = $this->DB2->get("admin_roles");
		
		$this->g_layout
			->add_breadcrumb("使用者管理", "manage/user")
			->add_breadcrumb("編輯使用者")
			->set("row", $row)
			->set("all_role", $all_role)
			->render();
	}	
	
	function modify_role($role='')
	{
		$this->zacl->check("manage_role", "modify");
		
		$this->init_mamage_layout();
		
		if ($this->input->post()) {
			if ($this->input->post("key")) {
				$this->DB1->where("role", $this->input->post("key"))
					->update("admin_roles", array(
								"role_desc" => $this->input->post("role_desc"),
								"parent" => $this->input->post("parent"),
							));
				$this->g_layout->set("result", $this->DB1->affected_rows()>0);
			}
			else {
				$this->DB1->insert("admin_roles", array(
								"role" => $this->input->post("role"),
								"role_desc" => $this->input->post("role_desc"),
								"parent" => $this->input->post("parent"),
							));
				header("location:".site_url("manage/role"));
				exit();		
			}			
		}
		//$this->zacl->check("manage/role", "read");	
		
		if ($role) {
			$row = $this->DB2->get_where("admin_roles", array("role"=>$role))->row();
			$child_num = $this->DB2->where("parent", $role)->from("admin_roles")->count_all_results();
		}
		else {
			$row = false;	
			$child_num = 0;
		}
		
		$all_role = $this->DB2->where("parent", "")->where("role <>", $role)->get("admin_roles");	
		
		$this->g_layout
			->add_breadcrumb("權限設定", "manage/role")
			->add_breadcrumb("編輯群組")
			->set("row", $row)
			->set("child_num", $child_num)
			->set("all_role", $all_role)
			->render();
	}			
	
	function modify_resource($resource='')
	{
		$this->zacl->check("manage_resource", "modify");
		$this->init_mamage_layout();
		
		if ($this->input->post()) {
			if ($this->input->post("key")) {
				$this->DB1->where("resource", $this->input->post("key"))
					->update("admin_resources", array(
								"resource_desc" => $this->input->post("resource_desc"),
								"operation_list" => $this->input->post("operation_list"),
								"parent" => $this->input->post("parent"),
							));
				$this->g_layout->set("result", $this->DB2->affected_rows()>0);
			}
			else {
				$this->DB1->insert("admin_resources", array(
								"resource" => $this->input->post("resource"),
								"resource_desc" => $this->input->post("resource_desc"),
								"operation_list" => $this->input->post("operation_list"),
								"parent" => $this->input->post("parent"),
							));		
				header("location:".site_url("manage/resource"));
				exit();
			}			
		}
		//$this->zacl->check("manage/role", "read");	
		
		if ($resource) {
			$row = $this->DB2->get_where("admin_resources", array("resource"=>$resource))->row();
			$child_num = $this->DB2->where("parent", $resource)->from("admin_resources")->count_all_results();
		}
		else {
			$row = false;
			$child_num = 0;
		}
		
		$all_resource = $this->DB2->where("parent", "")->where("resource <>", $resource)->get("admin_resources");	
		
		$this->g_layout
			->add_breadcrumb("功能設定", "manage/resource")
			->add_breadcrumb("編輯")
			->set("row", $row)
			->set("child_num", $child_num)
			->set("all_resource", $all_resource)
			->render();
	}		

	function delete_user($uid)
	{
		if ( ! $this->zacl->check_acl("manage_user", "delete")) die(json_failure("沒有權限"));
		
		$this->DB1->delete("admin_users", array("uid"=>$uid));
		echo $this->DB1->affected_rows()>0 ? json_success() : json_failure();
	}	
	
	function delete_resource($resource)
	{
		if ( ! $this->zacl->check_acl("manage_resource", "delete")) die(json_failure("沒有權限"));
		
		$this->DB1->delete("admin_resources", array("resource"=>$resource));
		echo $this->DB1->affected_rows()>0 ? json_success() : json_failure();
		$this->DB1->set("parent", "null", false)->where("parent", $resource)->update("admin_resources");
	}
	
	function delete_role($role)
	{
		if ( ! $this->zacl->check_acl("manage_role", "delete")) die(json_failure("沒有權限"));
		
		$this->DB1->delete("admin_roles", array("role"=>$role));
		echo $this->DB1->affected_rows()>0 ? json_success() : json_failure();
		$this->DB1->set("role", "")->where("role", $role)->update("admin_users");		
	}		
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */