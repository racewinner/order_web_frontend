<?php
namespace App\Controllers;
use App\Models\Manager;
use App\Models\Module;
use App\Models\Admin;
use App\Models\Product;

class Admin_area extends BaseController 
{
	
	public $data = [];

	/*
	Controllers that are considered secure extend Secure_area, optionally a $module_id can
	be set to also check if a user can access a particular module in the system.
	*/
	function __construct($module_id=null)
	{
		
		$Manager = new Manager();
		$Module = new Module();
		$Admin = new Admin();
		$Product = new Product();
		
		// parent::__construct();	
		
		if(!$Manager->is_logged_in())
		{
			return redirect()->to(base_url('cpanel'));
		}
		
		if(!$Manager->has_permission($module_id,$Manager->get_logged_in_manager_info()->manager_id))
		{
			redirect('no_access/'.$module_id);
		}
		
		//load up global data
		$logged_in_manager_info = $Manager->get_logged_in_manager_info();
		$this->data['allowed_modules'] = $Module->get_allowed_modules($logged_in_manager_info->manager_id);
		$this->data['user_info'] = $logged_in_manager_info;
		
	}
}
?>