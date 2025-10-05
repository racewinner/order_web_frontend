<?php
namespace App\Controllers;
use App\Models\Employee;
use App\Models\Module;
use App\Models\Admin;
use App\Models\Product;

class Secure_area extends BaseController 
{
	public $request;
	/*
	Controllers that are considered secure extend Secure_area, optionally a $module_id can
	be set to also check if a user can access a particular module in the system.
	*/
	function __construct($module_id=null)
	{
		parent::__construct($module_id);

		$request = request();
		
		$Employee = new Employee();
		$Module = new Module();
		$Admin = new Admin();
		$Product = new Product();

		if(!$Employee->is_logged_in()) return redirect()->to('/');			
		
		$person_id = $Employee->get_logged_in_employee_info()->person_id;
		
		if(!$Employee->has_permission($module_id,$person_id)) redirect()->to('no_access/'.$module_id);
		        
		$Product = new Product();
	}
}
