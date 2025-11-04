<?php
namespace App\Controllers;

use App\Controllers\Secure_area;
use App\Models\Admin;
use App\Models\Employee;
use App\Models\Hom;
use App\Models\Product;
use App\Models\PriceList;
use App\Models\Branch;
use App\Models\Cms;

class Customer extends BaseController
{
	// private $xxx = [];

	function __construct()
	{
		parent::__construct('customer');

		// $XXX = new XXX();
		// $this->xxx = $XXX->get_xxx();
	}

	function index()
	{
		
	}

	function mobile() {
		$is_mobile = request()->getPost('is_mobile');
		session()->set('is_mobile', $is_mobile);
		echo 'success';
	}
		
	function get_register()
	{
		echo view('v2/pages/customer_register'); // after you stored the query results inside the $data array, send the array to the view 
	}

	function post_register()
	{
		$db = \Config\Database::connect();
		$busi_legal_nm = request()->getPost("busi_legal_nm");	
		$busi_start_dt  = request()->getPost("busi_start_dt");	
		$busi_trad_nm  = request()->getPost("busi_trad_nm");	
		$addr_line1  = request()->getPost("addr_line1");	
		$addr_line2   = request()->getPost("addr_line2");	
		$country  = request()->getPost("country");	
		$city  = request()->getPost("city");	
		$post_code  = request()->getPost("post_code");	
		$contact_nm  = request()->getPost("contact_nm");	
		$contact_phone_ll   = request()->getPost("contact_phone_ll");	
		$contact_phone_mb   = request()->getPost("contact_phone_mb");	
		$contact_email   = request()->getPost("contact_email");	
		$company_no   = request()->getPost("company_no");	
		$vat_number   = request()->getPost("vat_number");	
		$busi_trad_years   = request()->getPost("busi_trad_years");	
		$store_sz   = request()->getPost("store_sz");	
		$store_avg    = request()->getPost("store_avg");	
		$pref_payment_method    = request()->getPost("pref_payment_method");	
		$sell_taxes    = request()->getPost("sell_taxes");	
		$credit_acc_facility    = request()->getPost("credit_acc_facility");	
		$offers_and_info    = request()->getPost("offers_and_info");	

		// $query = $db->table('epos_customer')
		// 			->where('email', $email)
		// 			->where('username', $username)
		// 			->orderBy('id','desc')	
		// 			->get();

		// if ($query->getNumRows() == 0) {
		// 	return response()->setJSON([
		// 		'success' => 0,
		// 		'msg' => "Confirm number doesn't exist"
		// 	]);
		// }

		$customer_data_to_save = array(
			'busi_legal_nm'     => $busi_legal_nm,
			'busi_start_dt'     => $busi_start_dt,
			'busi_trad_nm'     => $busi_trad_nm,
			'addr_line1'     => $addr_line1,
			'addr_line2'     => $addr_line2,
			'country'     => $country,
			'city'     => $city,
			'post_code'     => $post_code,
			'contact_nm'     => $contact_nm,
			'contact_phone_ll'     => $contact_phone_ll,
			'contact_phone_mb'     => $contact_phone_mb,
			'contact_email'     => $contact_email,
			'company_no'     => $company_no,
			'vat_number'     => $vat_number,
			'busi_trad_years'     => $busi_trad_years,
			'store_sz'     => $store_sz,
			'store_avg'     => $store_avg,
			'pref_payment_method'     => $pref_payment_method,
			'sell_taxes' => $sell_taxes,
			'credit_acc_facility' => $credit_acc_facility,
			'offers_and_info' => $offers_and_info,

		);		

		$db->table('epos_customer')->insert($customer_data_to_save);
	}

}

?>