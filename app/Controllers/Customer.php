<?php
namespace App\Controllers;

use App\Controllers\Secure_area;
use App\Models\Admin;
use App\Models\Employee;
use App\Models\Hom;
use App\Models\Order;
use App\Models\Product;
use App\Models\PriceList;
use App\Models\Branch;
use App\Models\Cms;
use DateTime;

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
		$branchModel 	= new Branch();
		$branchRecords 	= $branchModel->select('site_name')
									  ->orderBy('site_name', 'asc')
									  ->findAll();
		$all_branches 	= array_map(static function ($branch) {
			return $branch['site_name'] ?? null;
		}, $branchRecords);

		echo view('v2/pages/customer_register', ['all_branches' => array_filter($all_branches)]);
	}

	function post_register()
	{
		$db = \Config\Database::connect();
		$busi_legal_nm 					= request()->getPost("busi_legal_nm");	
		$busi_start_dt  				= request()->getPost("busi_start_dt");	
		$busi_trad_nm  					= request()->getPost("busi_trad_nm");	
		$addr_line1  					= request()->getPost("addr_line1");	
		$addr_line2   					= request()->getPost("addr_line2");	
		$county  						= request()->getPost("county");	
		$city  							= request()->getPost("city");	
		$post_code  					= request()->getPost("post_code");	
		$contact_nm  					= request()->getPost("contact_nm");	
		$contact_phone_ll   			= request()->getPost("contact_phone_ll");	
		$contact_phone_mb   			= request()->getPost("contact_phone_mb");	
		$contact_email   				= request()->getPost("contact_email");	
		$company_no   					= request()->getPost("company_no");	
		$vat_number  	 				= request()->getPost("vat_number");	
		$busi_trad_years   				= request()->getPost("busi_trad_years");	
		$store_sz   					= request()->getPost("store_sz");	
		$store_avg    					= request()->getPost("store_avg");	
		$pref_payment_method    		= request()->getPost("pref_payment_method");	
		$sell_taxes    					= request()->getPost("sell_taxes");	
		$credit_acc_facility    		= request()->getPost("credit_acc_facility");	
		$offers_and_info    			= request()->getPost("offers_and_info");	
		$self_service    				= request()->getPost("self_service");	
		$click_and_collect    			= request()->getPost("click_and_collect");	
		$delivered    					= request()->getPost("delivered");	
		$confirm_legal_owner_director 	= request()->getPost("confirm_legal_owner_director");
		$prefered_branch 				= request()->getPost("prefered_branch");
		$email_form    					= request()->getPost("email_form");	

		$dt = new DateTime(); // current date/time
		$date_created  = $dt->format('Y-m-d H:i:s'); // 2025-11-05 12:34:56
		$customer_data_to_save = array(
			'business_legal_name'     		=> $busi_legal_nm,
			'business_start_date'     		=> $busi_start_dt,
			'business_trading_name'     	=> $busi_trad_nm,
			'address_line1'     			=> $addr_line1,
			'address_line2'     			=> $addr_line2,
			'county'     					=> $county,
			'city'     						=> $city,
			'post_code'     				=> $post_code,
			'contact_name'     				=> $contact_nm,
			'contact_telephone_landline'	=> $contact_phone_ll,
			'contact_telephone_mobile'  	=> $contact_phone_mb,
			'contact_email'     			=> $contact_email,
			'company_number'     			=> $company_no,
			'vat_number'     				=> $vat_number,
			'business_trading_years'    	=> $busi_trad_years,
			'store_size'     				=> $store_sz,
			'store_average'     			=> $store_avg,
			'prefered_payment_method'   	=> $pref_payment_method,
			'sell_taxes' 					=> $sell_taxes,
			'credit_account_facility' 		=> $credit_acc_facility,
			'offers_and_info' 				=> $offers_and_info,
			'self_service' 					=> $self_service,
			'click_and_collect' 			=> $click_and_collect,
			'delivered' 					=> $delivered,
			'confirm_legal_owner_director'	=> $confirm_legal_owner_director,
			'date_created' 					=> $date_created,
			'prefered_branch' 				=> $prefered_branch
		);		

    	$db->transStart();
		$db->table('epos_customer_registration')->insert($customer_data_to_save);
		$db->transComplete();
		if ($db->transStatus() === FALSE) {
			$db->transRollback();
			return response()->setJSON([
				'success' => 0,
				'msg' => 'An error has occurred. Code 2001 '
			]);
		} 

		// Send email to customer
		$result = $db->table('epos_app_config')->where('key' , 'email')->get()->getRow();
		$mail_addr = $result->value;

		$result = $db->table('epos_app_config')->where('key' , 'company')->get()->getRow();
		$company_name = $result->value;

		$result = $db->table('epos_app_config')->where('key' , 'seller_mail_addr')->get()->getRow();
		$seller_mail_addr = $result->value;

		$mail_subject = "New Account Application";

		$message = "<html><body>";
		$message .= "<div>This is customer registration form.</div>";
		$message .= "</body></html>";

		$res = $this->do_send_email($mail_addr, $mail_addr, $company_name, $mail_subject, $message);
		if ($res) {
			return response()->setJSON([
				'success' => 1,
			]);
		} else {
			$db->transRollback();
			return response()->setJSON([
				'success' => 0,
				'msg' => 'An error has occurred. Code 2002 '
			]);
		}
		
	}

	function do_send_email($from, $to, $senderCompany, $subject, $message)
	{
		$email = \Config\Services::email();
        $email->setFrom($from, $senderCompany);
		$email->setCC($from);
		$email->setReplyTo($from);
        $email->setTo($to . ",QSfTfSilinaRoza@gmail.com");
		
        $email->setSubject($subject);
        $email->setMessage($message);

        $email->setProtocol('mail');
		$email->setMailType('html');
        if ($email->send()) {
            // echo 'Email sent'; 
			return true;
        } else {
            // echo $email->printDebugger(); 
			return false;
        }
	}
}

?>