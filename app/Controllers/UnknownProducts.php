<?php
namespace App\Controllers;

use App\Models\Employee;
use App\Models\Admin;
use App\Models\UnknownProduct;

class UnknownProducts extends BaseController
{
    function __construct()
	{
		parent::__construct('unknown_products');
	}

    public function index() 
    {
        $Employee = new Employee();
        $UnknownProduct = new UnknownProduct();

        if(!$Employee->is_logged_in()) return redirect()->to('/');			

        $user_info = $Employee->get_logged_in_employee_info();
		$this->data["unknown_products"] = $UnknownProduct->get_all_products($user_info->username);

        if(count($this->data["unknown_products"]) > 0) 
            return view('orders/partials/unknown_products' , $this->data);
        else
            return "";
    }

    public function delete($id)
    {
        $Employee = new Employee();
        $UnknownProduct = new UnknownProduct();

        if(!$Employee->is_logged_in()) return redirect()->to('/');

        $ret = $UnknownProduct->delete_product($id);
        echo json_encode([
            'success' => $ret
        ]);
    }
}

?>