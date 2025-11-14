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
		$credit_acc_facility    		= request()->getPost("credit_acc_facility");	
		$offers_and_info    			= request()->getPost("offers_and_info");	
		$self_service    				= request()->getPost("self_service");	
		$click_and_collect    			= request()->getPost("click_and_collect");	
		$delivered    					= request()->getPost("delivered");	
		$confirm_legal_owner_director 	= request()->getPost("confirm_legal_owner_director");
		$prefered_branch 				= request()->getPost("prefered_branch");
		$sell_alcohol 					= request()->getPost("sell_alcohol");
		$sell_tobacco 					= request()->getPost("sell_tobacco");
		$sell_vapes 					= request()->getPost("sell_vapes");

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
			'credit_account_facility' 		=> $credit_acc_facility,
			'offers_and_info' 				=> $offers_and_info,
			'self_service' 					=> $self_service,
			'click_and_collect' 			=> $click_and_collect,
			'delivered' 					=> $delivered,
			'confirm_legal_owner_director'	=> $confirm_legal_owner_director,
			'date_created' 					=> $date_created,
			'prefered_branch' 				=> $prefered_branch,
			'sell_alcohol' 					=> $sell_alcohol,
			'sell_tobacco' 					=> $sell_tobacco,
			'sell_vapes' 					=> $sell_vapes
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

		if ($pref_payment_method == "cash") {
			$pref_payment_method = "Cash";
		} else if ($pref_payment_method == "bank_transfer") {
			$pref_payment_method = "Bank Transfer";
		} else if ($pref_payment_method == "echo_pay") {
			$pref_payment_method = "EchoPay";
		} else if ($pref_payment_method == "card") {
			$pref_payment_method = "Card";
		} else if ($pref_payment_method == "credit_facility") {
			$pref_payment_method = "Credit Facility";
		}
		// Send email to customer
		// send email
		$db = \Config\Database::connect();
		$result = $db->table('epos_app_config')->where('key' , 'email')->get()->getRow();
		$mail_addr = $result->value;

		$result = $db->table('epos_app_config')->where('key' , 'company')->get()->getRow();
		$company_name = $result->value;

		$result = $db->table('epos_app_config')->where('key' , 'seller_mail_addr')->get()->getRow();
		$seller_mail_addr = $result->value;

		$mail_subject = "New Account Application Request";

		$message = "<html><head><style>
			.customer-register-panel-for-email {
				width: 1000px;
			}
			.customer-register-panel-for-email table {
				border-collapse: collapse;
				width: 100%;
				font-family: Arial, sans-serif;
				font-size: 12px;
				color: #545454;
			}
			.customer-register-panel-for-email th.section-title {
				text-align: left;
				background: #e5e5e5;
				font-size: 14px;
				padding: 6px 8px;
				border: 1px solid #ccc;
			}
			.customer-register-panel-for-email td {
				border: 1px solid #ccc;
				padding: 4px 6px;
				vertical-align: top;
			}
			.customer-register-panel-for-email td.label {
				width: 22%;
				white-space: nowrap;
				font-weight: bold;
			}
			.customer-register-panel-for-email td.value input,
			.customer-register-panel-for-email td.value select {
				width: 100%;
				box-sizing: border-box;
			}
			.customer-register-panel-for-email .checkbox-row label,
			.customer-register-panel-for-email .checkbox-column label {
				display: inline-block;
				margin-right: 14px;
			}
			.customer-register-panel-for-email .checkbox-column label {
				display: block;
			}
			.customer-register-panel-for-email .required::after {
				content: '(*)';
				color: #c00;
				padding-left: 2px;
			}
		</style></head><body>";
		
		$message .= '<div class="customer-register-panel-for-email">
			<table class="email-table" cellpadding="4" cellspacing="0" width="100%">
				<tr>
					<th colspan="4" class="section-title">Business</th>
				</tr>
				<tr>
					<td class="label required">Business Legal Name:</td>
					<td class="value"><input id="busi_legal_nm_for_email" type="text" value="'.$busi_legal_nm.'" /></td>
					<td class="label required">Address Line 1:</td>
					<td class="value"><input id="addr_line1_for_email" type="text" value="'.$addr_line1.'" /></td>
				</tr>
				<tr>
					<td class="label required">Business Trading Name:</td>
					<td class="value"><input id="busi_trad_nm_for_email" type="text" value="'.$busi_trad_nm.'" /></td>
					<td class="label">Address Line 2:</td>
					<td class="value"><input id="addr_line2_for_email" type="text" value="'.$addr_line2.'" /></td>
				</tr>
				<tr>
					<td class="label required">Business Start Date:</td>
					<td class="value"><input id="busi_start_dt_for_email" type="text" value="'.$busi_start_dt.'" /></td>
					<td class="label required">County:</td>
					<td class="value"><input id="county_for_email" type="text" value="'.$county.'" /></td>
				</tr>
				<tr>
					<td class="label">Preferred Branch:</td>
					<td class="value">
						<select id="prefered_branch_for_email" value="'.$prefered_branch.'">
						<option value="'.$prefered_branch.'">'.$prefered_branch.'</option>
						</select>
					</td>
					<td class="label required">City:</td>
					<td class="value"><input id="city_for_email" type="text" value="'.$city.'" /></td>
				</tr>
				<tr>
					<td colspan="2" class="checkbox-cell">
						<label>
						<input type="checkbox" id="confirm_legal_owner_director_for_email" checked="checked" />
						I confirm I am the Legal Owner or Director
						</label>
					</td>
					<td class="label required">Post Code:</td>
					<td class="value"><input id="post_code_for_email" type="text" value="'.$post_code.'" /></td>
				</tr>

				<tr>
					<th colspan="4" class="section-title">Contact</th>
				</tr>
				<tr>
					<td class="label required">Contact Name:</td>
					<td class="value"><input id="contact_nm_for_email" type="text" value="'.$contact_nm.'" /></td>
					<td class="label">Contact Telephone Landline:</td>
					<td class="value"><input id="contact_phone_ll_for_email" type="text" value="'.$contact_phone_ll.'" /></td>
				</tr>
				<tr>
					<td class="label required">Contact Telephone Mobile:</td>
					<td class="value"><input id="contact_phone_mb_for_email" type="text" value="'.$contact_phone_mb.'" /></td>
					<td class="label required">Contact email address:</td>
					<td class="value"><input id="contact_email_for_email" type="text" value="'.$contact_email.'" /></td>
				</tr>

				<tr>
					<th colspan="4" class="section-title">Company</th>
				</tr>
				<tr>
					<td class="label">Company Number:</td>
					<td class="value"><input id="company_no_for_email" type="text" value="'.$company_no.'" /></td>
					<td class="label">VAT Number:</td>
					<td class="value"><input id="vat_number_for_email" type="text" value="'.$vat_number.'" /></td>
				</tr>
				<tr>
					<td class="label required">For how many years has the business been trading?</td>
					<td class="value"><input id="busi_trad_years_for_email" type="text" value="'.$busi_trad_years.'" /></td>
					<td class="label">&nbsp;</td>
					<td class="value">&nbsp;</td>
				</tr>

				<tr>
					<th colspan="4" class="section-title">Sell</th>
				</tr>
				<tr>
					<td colspan="4" class="checkbox-column">
						<label><input type="checkbox" id="sell_alcohol_for_email" '.($sell_alcohol==1?'checked="checked"':'').'/> Do you sell Alcohol?</label>
						<label><input type="checkbox" id="sell_tobacco_for_email" '.($sell_tobacco==1?'checked="checked"':'').'/> Do you sell Tobacco?</label>
						<label><input type="checkbox" id="sell_vapes_for_email" '.($sell_vapes==1?'checked="checked"':'').'/> Do you sell Vapes?</label>
					</td>
				</tr>

				<tr>
					<th colspan="4" class="section-title">Store</th>
				</tr>
				<tr>
					<td class="label required">Store size in square feet?</td>
					<td class="value"><input id="store_sz_for_email" type="text" value="'.$store_sz.'" /></td>
					<td class="label required">Store average turnover weekly?</td>
					<td class="value"><input id="store_avg_for_email" type="text" value="'.$store_avg.'" /></td>
				</tr>
				<tr>
					<td colspan="4" class="checkbox-column">
						<label><input type="checkbox" id="self_service_for_email" '.($self_service==1?'checked="checked"':'').'/> I will visit the store to purchase goods</label>
						<label><input type="checkbox" id="click_and_collect_for_email" '.($click_and_collect==1?'checked="checked"':'').'/> I will order online then come in to collect goods</label>
						<label><input type="checkbox" id="delivered_for_email" '.($delivered==1?'checked="checked"':'').'/> I will order online and require a delivery of goods</label>
						<label><input type="checkbox" id="offers_and_info_for_email" '.($offers_and_info==1?'checked="checked"':'').'/> Do you wish to receive marketing offers and info?</label>
					</td>
				</tr>

				<tr>
					<th colspan="4" class="section-title">Payment &amp; Offer</th>
				</tr>
				<tr>
					<td class="label">What is your preferred payment method?</td>
					<td class="value">
						<select id="pref_payment_method_for_email" value="'.$pref_payment_method.'">
						<option value="'.$pref_payment_method.'">'.$pref_payment_method.'</option>
						</select>
					</td>
					<td class="label">Do you require a credit account facility?</td>
					<td class="">
						<label>
						<input type="checkbox" id="credit_acc_facility_for_email" '.($credit_acc_facility==1?'checked="checked"':'').'/>
						Yes
						</label>
					</td>
				</tr>
			</table>
		</div>';

		$message .= "</body></html>";

		// $email = "test_ac_1234@ecso.co.uk";
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