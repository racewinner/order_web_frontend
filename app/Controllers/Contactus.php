<?php
namespace App\Controllers;

use App\Models\Order;
use App\Models\Employee;
use App\Models\Admin;
use CodeIgniter\Email\Email;

class Contactus extends Secure_area /* implements iData_controller*/
{
	function __construct()
	{
		parent::__construct('contactus');
	}

	function index()
	{
		$Employee = new Employee();
		$Admin = new Admin();
		// $data['controller_name']=strtolower(get_class());
		$this->data['controller_name'] = $this->request->getUri()->getSegment(1);
		
		$user_info = $Employee->get_logged_in_employee_info();
		$this->data['user_info'] = $user_info;
	    
	    $this->data["slides"] = $Admin->get_scount('slides');
		return view('contactus/manage' , $this->data);
	}

	function send_message()
	{
		$Employee = new Employee();
		$Order = new Order();
		$user_info = $Employee->get_logged_in_employee_info();
		$phone_number = request()->getPost('phone_number');
		$msg = request()->getPost('msg');

		$addr_mail = $Order->from_addr_mail();
		$email = new Email();

		
		$mail_subject = "Your web order query";
		$message = "from : ".$user_info->email;
		$message .= "\r\nphone number : ".$phone_number;
		$message .= "\r\nusername : ".$user_info->username;
		$message .= "\r\n\r\n";
		$message .= $msg;

		$email->clear();

		$email->setFrom($user_info->email);
		$email->setTo($addr_mail['seller_mail_addr']);			
		$email->setSubject($mail_subject);
		$email->setMessage($message);

		$send_status_uws = $email->send();

		$email->clear();

		$message  = "\r\nThank you for your communication. We will contact you as soon as possible.";
		$message .= "\r\nfrom : ".$user_info->email;
		$message .= "\r\nphone number : ".$phone_number;
		$message .= "\r\nusername : ".$user_info->username;
		$message .= "\r\n\r\n";
		$message .= $msg;

		$email->setFrom($addr_mail['seller_mail_addr'] , $addr_mail['company_name']);
		$email->setTo($user_info->email);
		$email->setSubject('Re-'.$mail_subject);
		$email->setMessage($message);

		$send_status_cust = $email->send();
		

        if($send_status_uws && $send_status_cust)	echo 1;
		else echo -1;

	}
}
?>
